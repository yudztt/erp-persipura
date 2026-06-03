<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Kirim pesan ke AI via OpenRouter (https://openrouter.ai)
     * Model gratis: deepseek/deepseek-r1:free, meta-llama/llama-3.3-70b-instruct:free
     * Base URL: https://openrouter.ai/api/v1
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $apiKey      = config('services.openrouter.api_key');
        $model       = config('services.openrouter.model', 'deepseek/deepseek-r1:free');
        $baseUrl     = 'https://openrouter.ai/api/v1';

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Maaf, kunci API belum diatur (OPENROUTER_API_KEY). Daftar gratis di https://openrouter.ai lalu masukkan key ke file .env'
            ], 200);
        }

        // Mengambil data produk real-time dari database
        try {
            $products = \App\Models\Produk::with('kategori')->get();
            $stockContext = "Berikut adalah data stok dan harga produk real-time saat ini di database ERP:\n";
            foreach ($products as $p) {
                $catName = $p->kategori->nama_kategori ?? 'Umum';
                $stockContext .= "- {$p->nama_produk} (SKU: {$p->kode_produk}, Kategori: {$catName}) -> Stok: {$p->stok} unit, Harga: Rp" . number_format($p->harga_jual, 0, ',', '.') . " (Stok Minimum: {$p->stok_minimum})\n";
            }
        } catch (\Exception $e) {
            Log::error('Chatbot: Gagal mengambil data produk: ' . $e->getMessage());
            $stockContext = "Data produk saat ini tidak tersedia.";
        }

        // System Prompt untuk konteks ERP Cendrawasih Karsa
        $systemPrompt = "Anda adalah Asisten AI untuk Sistem ERP Cendrawasih Karsa Store " .
            "(Manajemen Inventori dan Prediksi Penjualan). " .
            "Jawablah pertanyaan dengan ramah, profesional, ringkas, dan menggunakan bahasa Indonesia. " .
            "Gunakan teks sederhana dan poin/list jika perlu. Hindari format markdown yang rumit.\n\n" .
            "Anda memiliki akses real-time ke data stok produk berikut di toko:\n" .
            $stockContext . "\n" .
            "Gunakan data di atas untuk menjawab pertanyaan pengguna tentang stok, ketersediaan barang, harga, sisa kuantitas, kategori, atau produk yang persediaannya hampir habis (stok <= stok minimum) secara akurat. Jangan pernah mengarang data produk yang tidak ada di daftar di atas. Jika ditanya siapa Anda, katakan: Saya adalah Asisten AI Cendrawasih Karsa Store.";

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type'   => 'application/json',
                    'Authorization'  => 'Bearer ' . $apiKey,
                    'HTTP-Referer'   => config('app.url', 'http://localhost'),
                    'X-Title'        => 'ERP Cendrawasih Karsa',
                ])
                ->post($baseUrl . '/chat/completions', [
                    'model'    => $model,
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role'    => 'user',
                            'content' => $userMessage,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 800,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['choices'][0]['message']['content'])) {
                    $reply = $data['choices'][0]['message']['content'];
                    return response()->json(['reply' => trim($reply)]);
                }

                Log::warning('Chatbot: Unexpected response structure', ['body' => $data]);
                return response()->json([
                    'reply' => 'Maaf, respons AI tidak dapat diproses. Coba ulangi pertanyaan Anda.'
                ], 200);
            }

            $httpCode = $response->status();

            if ($httpCode === 401) {
                Log::error('Chatbot: OpenRouter API Key tidak valid', ['status' => $httpCode]);
                return response()->json([
                    'reply' => 'API Key tidak valid. Periksa OPENROUTER_API_KEY di file .env'
                ], 200);
            }

            if ($httpCode === 429) {
                Log::warning('Chatbot: Rate limit OpenRouter', ['status' => $httpCode]);
                return response()->json([
                    'reply' => 'Terlalu banyak permintaan. Silakan tunggu sebentar lalu coba lagi.'
                ], 200);
            }

            Log::error('Chatbot API Error', [
                'status' => $httpCode,
                'body'   => $response->body(),
            ]);
            return response()->json([
                'reply' => 'Maaf, terjadi kendala teknis (Error ' . $httpCode . '). Silakan coba beberapa saat lagi.'
            ], 200);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Chatbot: Gagal koneksi ke OpenRouter', ['error' => $e->getMessage()]);
            return response()->json([
                'reply' => 'Tidak dapat terhubung ke server AI. Periksa koneksi internet Anda.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 200);
        }
    }
}
