<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $msg = trim((string) $request->input('message', ''));

        if ($msg === '') {
            return response()->json([
                'reply' => 'Bạn hãy nhập câu hỏi nhé.'
            ], 200);
        }

        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        if (!$apiKey) {
            return response()->json([
                'reply' => 'Thiếu API key (GEMINI_API_KEY).'
            ], 500);
        }

        try {
            $res = Http::withHeaders([
                'Content-Type'   => 'application/json',
                'x-goog-api-key' => $apiKey,
            ])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
                    'contents' => [['parts' => [['text' => $msg]]]],
                ]);

            if (!$res->ok()) {
                // Thường gặp 429 (quota), 400 (payload), 401 (key sai)...
                return response()->json([
                    'reply' => 'Xin lỗi, không thể gọi mô hình lúc này (' . $res->status() . ').'
                ], 200);
            }

            $data = $res->json();

            // Lấy text an toàn
            $text = Arr::get($data, 'candidates.0.content.parts.0.text');
            if (!$text) {
                // Dự phòng nếu parts nhiều segment
                $parts = Arr::get($data, 'candidates.0.content.parts', []);
                $text  = collect($parts)->pluck('text')->filter()->implode("\n");
            }

            return response()->json([
                'reply' => $text ?: 'Xin lỗi, mình chưa hiểu ý bạn.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'reply' => 'Có lỗi khi gọi mô hình: ' . $e->getMessage()
            ], 200);
        }
    }
}
