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
        $apiKey = config('services.openrouter.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Maaf, kunci API (OPENROUTER_API_KEY) belum diatur di sistem. Silakan hubungi administrator.'
            ], 200);
        }

        // System prompt konteks ERP
        $systemPrompt = "Anda adalah Asisten AI untuk Sistem ERP Cendrawasih Karsa Store (Manajemen Inventori dan Prediksi Penjualan). " .
            "Jawablah pertanyaan dengan ramah, profesional, ringkas, dan menggunakan bahasa Indonesia. " .
            "Jangan menggunakan format markdown rumit, gunakan teks sederhana dan list jika perlu. " .
            "Jika ditanya siapa Anda, katakan Anda adalah Asisten AI Cendrawasih Karsa.";

        try {
            $response = Http::withHeaders([
                'Authorization'  => 'Bearer ' . $apiKey,
                'Content-Type'   => 'application/json',
                'HTTP-Referer'   => config('app.url'),
                'X-Title'        => 'ERP Cendrawasih Karsa Store',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => config('services.openrouter.model', 'google/gemini-2.0-flash-exp:free'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userMessage],
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['choices'][0]['message']['content'])) {
                    $reply = $data['choices'][0]['message']['content'];
                    return response()->json(['reply' => trim($reply)]);
                }
            }

            Log::error('OpenRouter API Error: ' . $response->body());
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
