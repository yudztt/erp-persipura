<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PrediksiPenjualan;
use App\Models\RestockRekomendasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total SKU
        $totalSku = Produk::count();

        // 2. Penjualan Bulan Ini
        $penjualanBulanIni = Penjualan::whereMonth('tanggal', now()->month)
                                      ->whereYear('tanggal', now()->year)
                                      ->sum('total');

        // 3. Stok Rendah
        $stokRendahCount = Produk::whereColumn('stok', '<=', 'stok_minimum')->count();

        // 4. Total Stok
        $totalStok = Produk::sum('stok');

        // 5. Chart Penjualan (6 bulan terakhir)
        $salesChart = [];
        $maxSales = 0;
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = Penjualan::whereMonth('tanggal', $date->month)
                              ->whereYear('tanggal', $date->year)
                              ->sum('total');
            if ($total > $maxSales) {
                $maxSales = $total;
            }
            $salesChart[] = [
                'month' => $date->translatedFormat('M'),
                'total' => $total
            ];
        }

        // SVG Mapping
        // Y coordinates: maxSales = 32, 0 = 100.
        // X coordinates: 55, 121, 187, 253, 319, 385.
        $xCoords = [55, 121, 187, 253, 319, 385];
        $chartData = [];
        $svgPath = "";
        
        // Prevent division by zero
        $maxSalesForCalc = $maxSales > 0 ? $maxSales : 1; 

        foreach ($salesChart as $index => $data) {
            $x = $xCoords[$index];
            $y = 100 - (($data['total'] / $maxSalesForCalc) * (100 - 32));
            $chartData[] = [
                'x' => $x,
                'y' => $y,
                'month' => $data['month'],
                'total' => $data['total']
            ];
            $svgPath .= ($index === 0 ? "{$x},{$y} " : "{$x},{$y} ");
        }
        
        $highestMonth = collect($chartData)->sortByDesc('total')->first()['month'] ?? '-';

        // 6. AI Forecast Card
        // Mengambil bulan dan tahun terupdate dari data prediksi di database untuk menjaga keselarasan
        $latestPredRecord = PrediksiPenjualan::latest('tanggal_prediksi')->first();
        if ($latestPredRecord) {
            $predMonth = \Carbon\Carbon::parse($latestPredRecord->tanggal_prediksi)->month;
            $predYear = \Carbon\Carbon::parse($latestPredRecord->tanggal_prediksi)->year;
            $forecastMonth = \Carbon\Carbon::parse($latestPredRecord->tanggal_prediksi)->translatedFormat('F Y');
            
            $prediksiBulanDepan = PrediksiPenjualan::with('produk')
                ->whereMonth('tanggal_prediksi', $predMonth)
                ->whereYear('tanggal_prediksi', $predYear)
                ->get();
        } else {
            $forecastMonth = now()->translatedFormat('F Y');
            $prediksiBulanDepan = collect();
        }

        // Kalkulasi Prediksi Omzet (Unit * Harga Jual Produk)
        $totalPrediksiUnits = $prediksiBulanDepan->sum('hasil_prediksi');
        $totalPrediksiOmzet = 0;
        foreach ($prediksiBulanDepan as $pred) {
            if ($pred->produk) {
                $totalPrediksiOmzet += $pred->hasil_prediksi * $pred->produk->harga_jual;
            }
        }
        $totalPrediksi = $totalPrediksiOmzet; // Menggunakan nama variabel yang diharapkan oleh Blade view
        
        $topPrediksi = $prediksiBulanDepan->sortByDesc('hasil_prediksi')->first();
        $topProduk = $topPrediksi && $topPrediksi->produk ? $topPrediksi->produk->nama_produk : '-';
        
        // Mengambil status 'stok rendah' untuk SKU kritis
        $kritisRestock = 0;
        if ($latestPredRecord) {
            $kritisRestock = RestockRekomendasi::whereHas('prediksiPenjualan', function($q) use ($predMonth, $predYear) {
                $q->whereMonth('tanggal_prediksi', $predMonth)
                  ->whereYear('tanggal_prediksi', $predYear);
            })->where('status', 'stok rendah')->count();
        }

        // 7. Stok Rendah Table
        $stokRendahs = Produk::whereColumn('stok', '<=', 'stok_minimum')
                             ->orderBy('stok', 'asc')
                             ->take(4)
                             ->get();

        // 8. Transaksi Terbaru
        $penjualans = Penjualan::withSum('detailPenjualans', 'qty')->latest('tanggal')->take(4)->get();
        $pembelians = Pembelian::withSum('detailPembelians', 'qty')->latest('tanggal')->take(4)->get();
        
        $recentActivities = collect();
        
        foreach ($penjualans as $p) {
            $recentActivities->push((object)[
                'id' => '#SO-' . str_pad($p->id_penjualan, 3, '0', STR_PAD_LEFT),
                'type' => 'Keluar',
                'qty' => $p->detail_penjualans_sum_qty ?? 0,
                'date' => $p->tanggal->translatedFormat('d M'),
                'timestamp' => $p->tanggal->timestamp
            ]);
        }
        
        foreach ($pembelians as $p) {
            $recentActivities->push((object)[
                'id' => '#SI-' . str_pad($p->id_pembelian, 3, '0', STR_PAD_LEFT),
                'type' => 'Masuk',
                'qty' => $p->detail_pembelians_sum_qty ?? 0,
                'date' => $p->tanggal->translatedFormat('d M'),
                'timestamp' => $p->tanggal->timestamp
            ]);
        }
        
        $recentActivities = $recentActivities->sortByDesc('timestamp')->take(4);

        return view('dashboard', compact(
            'totalSku',
            'penjualanBulanIni',
            'stokRendahCount',
            'totalStok',
            'chartData',
            'svgPath',
            'maxSales',
            'highestMonth',
            'forecastMonth',
            'totalPrediksi',
            'topProduk',
            'kritisRestock',
            'stokRendahs',
            'recentActivities'
        ));
    }
}
