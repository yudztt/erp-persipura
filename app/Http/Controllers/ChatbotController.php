<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Maaf, kunci API (GEMINI_API_KEY) belum diatur di sistem. Silakan hubungi administrator.'
            ], 200);
        }

        // Context / System Prompt
        $systemPrompt = "Anda adalah Asisten AI untuk Sistem ERP Cendrawasih Karsa Store (Manajemen Inventori dan Prediksi Penjualan). " .
            "Jawablah pertanyaan dengan ramah, profesional, ringkas, dan menggunakan bahasa Indonesia. " .
            "Jangan menggunakan format markdown rumit, gunakan teks sederhana dan list jika perlu. " .
            "Jika ditanya siapa Anda, katakan Anda adalah Asisten AI Cendrawasih Karsa.";

        $fullPrompt = $systemPrompt . "\n\nPertanyaan pengguna: " . $userMessage;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                    return response()->json(['reply' => trim($reply)]);
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json([
                'reply' => 'Maaf, saya sedang mengalami kendala teknis saat menghubungi server AI. Silakan coba beberapa saat lagi.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Terjadi kesalahan sistem. Tidak dapat terhubung ke AI.'
            ], 200);
        }
    }
}
