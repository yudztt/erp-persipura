<?php

namespace App\Http\Controllers;

use App\Models\PrediksiPenjualan;
use App\Models\RestockRekomendasi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PrediksiPenjualanController extends Controller
{
    /**
     * Display the web-based forecasting dashboard
     */
    public function webIndex(Request $request)
    {
        $produks = Produk::orderBy('nama_produk', 'asc')->get();
        $selectedProductId = $request->query('product_id');
        
        // Subquery to get only the latest prediction ID for each product
        $latestPredictionSubquery = function($q) {
            $q->select(\DB::raw('MAX(id_prediksi)'))
              ->from('prediksi_penjualans')
              ->groupBy('id_produk');
        };

        // Fetch only the latest prediction per product for global KPI and chart calculations
        $allPrediksis = PrediksiPenjualan::with(['produk', 'restockRekomendasis'])
            ->whereIn('id_prediksi', $latestPredictionSubquery)
            ->get();

        // Calculate Stock KPI values
        $kritisCount = 0;
        $totalRestockUnits = 0;

        foreach ($allPrediksis as $pred) {
            $rec = $pred->restockRekomendasis->first();
            if ($rec) {
                if ($rec->status === 'stok rendah') {
                    $kritisCount++;
                }
                $totalRestockUnits += $rec->stok_disarankan;
            } elseif ($pred->produk && $pred->produk->stok <= $pred->produk->stok_minimum) {
                $kritisCount++;
            }
        }

        // Fetch predictions for the table display (only latest per product, optionally filtered)
        $query = PrediksiPenjualan::with(['produk', 'restockRekomendasis'])
            ->whereIn('id_prediksi', $latestPredictionSubquery)
            ->orderBy('tanggal_prediksi', 'desc');

        if ($selectedProductId && $selectedProductId !== 'all') {
            $query->where('id_produk', $selectedProductId);
        }

        $prediksis = $query->paginate(10)->appends($request->query());

        return view('intelligence.forecast', compact(
            'produks', 
            'prediksis', 
            'allPrediksis', 
            'kritisCount', 
            'totalRestockUnits',
            'selectedProductId'
        ));
    }

    /**
     * Generate dynamic AI sales predictions on demand using OpenRouter
     */
    public function generate(Request $request)
    {
        $request->validate([
            'id_produk' => 'required', // Can be an integer or 'all'
        ]);

        $idProdukInput = $request->input('id_produk');
        $results = [];

        try {
            if ($idProdukInput === 'all') {
                // To prevent timeouts and rate-limits, select top 5 products with lowest stock ratio
                $selectedProducts = Produk::orderByRaw('stok / stok_minimum asc')->take(5)->get();
            } else {
                $selectedProducts = Produk::where('id_produk', $idProdukInput)->get();
            }

            if ($selectedProducts->isEmpty()) {
                return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
            }

            foreach ($selectedProducts as $produk) {
                $res = $this->generateSinglePrediction($produk);
                if ($res) {
                    $results[] = $res;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Prediksi AI berhasil digenerate!',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Forecast Generator Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memproses prediksi AI: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to run forecast for a single product
     */
    private function generateSinglePrediction($produk)
    {
        // 1. Gather historical sales metrics for the last 30, 60, and 90 days from DetailPenjualan
        $sales30 = \App\Models\DetailPenjualan::where('id_produk', $produk->id_produk)
            ->whereHas('penjualan', function($q) {
                $q->where('tanggal', '>=', now()->subDays(30));
            })->sum('qty') ?? 0;

        $sales60 = \App\Models\DetailPenjualan::where('id_produk', $produk->id_produk)
            ->whereHas('penjualan', function($q) {
                $q->where('tanggal', '>=', now()->subDays(60));
            })->sum('qty') ?? 0;

        $sales90 = \App\Models\DetailPenjualan::where('id_produk', $produk->id_produk)
            ->whereHas('penjualan', function($q) {
                $q->where('tanggal', '>=', now()->subDays(90));
            })->sum('qty') ?? 0;

        // 2. Call OpenRouter AI
        $apiKey = config('services.openrouter.api_key');
        $model = config('services.openrouter.model', 'google/gemma-4-31b-it:free');
        $baseUrl = 'https://openrouter.ai/api/v1';

        $hasil_prediksi = 0;
        $stok_disarankan = 0;
        $status = 'stok sedang';
        $analisis = 'Prediksi otomatis berdasarkan riwayat pergerakan barang.';

        if (!empty($apiKey)) {
            $prompt = "Anda adalah pakar analisis persediaan ERP olahraga Persipura. Analisis data produk berikut:\n" .
                "- Nama Produk: {$produk->nama_produk}\n" .
                "- SKU: {$produk->kode_produk}\n" .
                "- Kategori: " . ($produk->kategori->nama_kategori ?? 'Umum') . "\n" .
                "- Stok Saat Ini: {$produk->stok} unit\n" .
                "- Batas Minimum Stok: {$produk->stok_minimum} unit\n" .
                "- Penjualan 30 hari terakhir: {$sales30} unit\n" .
                "- Penjualan 60 hari terakhir (kumulatif): {$sales60} unit\n" .
                "- Penjualan 90 hari terakhir (kumulatif): {$sales90} unit\n\n" .
                "Berdasarkan pola penjualan historis ini, prediksikan jumlah unit produk yang kemungkinan besar akan terjual pada bulan depan (30 hari ke depan) sebagai 'hasil_prediksi' (integer).\n" .
                "Hitung juga jumlah unit yang disarankan untuk dipesan/di-restock sekarang sebagai 'stok_disarankan' (integer). Gunakan rumus: max(0, (Batas Minimum Stok * 2) - Stok Saat Ini) atau sesuaikan dengan tren penjualan jika permintaannya sangat tinggi.\n" .
                "Klasifikasikan status stok saat ini ke dalam salah satu status berikut secara ketat:\n" .
                "1. 'stok banyak' -> Jika sisa stok saat ini sangat melimpah dibanding tren penjualan dan jauh di atas stok minimum.\n" .
                "2. 'stok sedang' -> Jika stok mencukupi untuk bulan depan tapi perlu dipantau.\n" .
                "3. 'stok rendah' -> Jika sisa stok berada di bawah/mendekati batas minimum, atau tidak mencukupi untuk memenuhi prediksi permintaan bulan depan.\n\n" .
                "Berikan respons Anda hanya dalam format JSON murni tanpa format markdown (jangan gunakan blok ```json) dengan struktur berikut:\n" .
                "{\n" .
                "  \"hasil_prediksi\": [integer],\n" .
                "  \"stok_disarankan\": [integer],\n" .
                "  \"status\": [string: 'stok banyak' | 'stok sedang' | 'stok rendah'],\n" .
                "  \"analisis\": [string: 1-2 kalimat analisis ringkas berbahasa Indonesia]\n" .
                "}";

            try {
                $response = Http::timeout(25)
                    ->withHeaders([
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Bearer ' . $apiKey,
                        'HTTP-Referer'  => config('app.url', 'http://localhost'),
                        'X-Title'       => 'ERP Cendrawasih Karsa',
                    ])
                    ->post($baseUrl . '/chat/completions', [
                        'model'    => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.3,
                    ]);

                if ($response->successful()) {
                    $rawContent = trim($response->json()['choices'][0]['message']['content'] ?? '');
                    
                    // Strip markdown wrappers if the AI returned them
                    if (strpos($rawContent, '```') !== false) {
                        $rawContent = preg_replace('/```(?:json)?|```/', '', $rawContent);
                        $rawContent = trim($rawContent);
                    }

                    $aiData = json_decode($rawContent, true);

                    if (isset($aiData['hasil_prediksi'])) {
                        $hasil_prediksi = (int)$aiData['hasil_prediksi'];
                        $stok_disarankan = (int)($aiData['stok_disarankan'] ?? 0);
                        
                        // Strict validation of the status
                        $rawStatus = strtolower(trim($aiData['status'] ?? ''));
                        if (in_array($rawStatus, ['stok banyak', 'stok sedang', 'stok rendah'])) {
                            $status = $rawStatus;
                        } else {
                            // Fallback categorizer if the AI outputs anything else
                            if ($produk->stok <= $produk->stok_minimum) {
                                $status = 'stok rendah';
                            } elseif ($produk->stok >= $produk->stok_minimum * 1.5) {
                                $status = 'stok banyak';
                            } else {
                                $status = 'stok sedang';
                            }
                        }

                        $analisis = trim($aiData['analisis'] ?? $analisis);
                    }
                }
            } catch (\Exception $ex) {
                Log::error('Forecast AI API Call failed: ' . $ex->getMessage());
            }
        }

        // Fallback calculations in case API key is empty or call failed
        if ($hasil_prediksi <= 0) {
            $hasil_prediksi = max(10, (int)$sales30);
            $stok_disarankan = max(0, ($produk->stok_minimum * 2) - $produk->stok);
            if ($produk->stok <= $produk->stok_minimum) {
                $status = 'stok rendah';
            } elseif ($produk->stok >= $produk->stok_minimum * 1.5) {
                $status = 'stok banyak';
            } else {
                $status = 'stok sedang';
            }
            $analisis = "Prediksi cadangan otomatis (koneksi AI tertunda). Tren penjualan 30 hari: {$sales30} unit.";
        }

        // 3. Save into the database
        // Delete any existing prediction for the same product in the current month/year to prevent duplicate spam
        PrediksiPenjualan::where('id_produk', $produk->id_produk)
            ->where('bulan', now()->month)
            ->where('tahun', now()->year)
            ->delete();

        $prediksi = PrediksiPenjualan::create([
            'id_produk'        => $produk->id_produk,
            'bulan'            => now()->month,
            'tahun'            => now()->year,
            'hasil_prediksi'   => $hasil_prediksi,
            'tanggal_prediksi' => now(),
        ]);

        $restock = RestockRekomendasi::create([
            'id_produk'       => $produk->id_produk,
            'id_prediksi'     => $prediksi->id_prediksi,
            'stok_saat_ini'   => $produk->stok,
            'stok_disarankan' => $stok_disarankan,
            'status'          => $status,
        ]);

        return [
            'id_prediksi'      => $prediksi->id_prediksi,
            'nama_produk'      => $produk->nama_produk,
            'sku'              => $produk->kode_produk,
            'kategori'         => $produk->kategori->nama_kategori ?? 'Umum',
            'stok_saat_ini'    => $produk->stok,
            'stok_minimum'     => $produk->stok_minimum,
            'hasil_prediksi'   => $hasil_prediksi,
            'stok_disarankan'  => $stok_disarankan,
            'status'           => $status,
            'analisis'         => $analisis,
            'tanggal_prediksi' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Existing JSON API endpoint implementations below (for mobile/external API consumers)
     */
    public function index()
    {
        $prediksis = PrediksiPenjualan::with('produk')->get();
        return response()->json($prediksis);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk'        => 'required|exists:produks,id_produk',
            'bulan'            => 'required|integer|min:1|max:12',
            'tahun'            => 'required|integer|min:2000',
            'hasil_prediksi'   => 'required|numeric|min:0',
            'tanggal_prediksi' => 'required|date',
        ]);

        $prediksi = PrediksiPenjualan::create($validated);
        return response()->json($prediksi->load('produk'), 201);
    }

    public function show(PrediksiPenjualan $prediksiPenjualan)
    {
        return response()->json($prediksiPenjualan->load(['produk', 'restockRekomendasis']));
    }

    public function update(Request $request, PrediksiPenjualan $prediksiPenjualan)
    {
        $validated = $request->validate([
            'id_produk'        => 'sometimes|exists:produks,id_produk',
            'bulan'            => 'sometimes|integer|min:1|max:12',
            'tahun'            => 'sometimes|integer|min:2000',
            'hasil_prediksi'   => 'sometimes|numeric|min:0',
            'tanggal_prediksi' => 'sometimes|date',
        ]);

        $prediksiPenjualan->update($validated);
        return response()->json($prediksiPenjualan->load('produk'));
    }

    public function destroy(PrediksiPenjualan $prediksiPenjualan)
    {
        $prediksiPenjualan->delete();
        return response()->json(['message' => 'Prediksi penjualan berhasil dihapus.']);
    }
}
