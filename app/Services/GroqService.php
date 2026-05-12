<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    protected string $apiKey;
    protected string $chatModel;
    protected string $visionModel;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey      = config('services.groq.api_key');
        $this->chatModel   = config('services.groq.chat_model');
        $this->visionModel = config('services.groq.vision_model');
        $this->baseUrl     = config('services.groq.base_url');
    }

    /**
     * Send a chat message to Groq and return the AI response.
     */
    public function chat(array $messages, string $systemPrompt = ''): string
    {
        $payload = [
            'model'       => $this->chatModel,
            'messages'    => array_merge(
                $systemPrompt ? [['role' => 'system', 'content' => $systemPrompt]] : [],
                $messages
            ),
            'max_tokens'  => 1024,
            'temperature' => 0.7,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(30)->post($this->baseUrl . '/chat/completions', $payload);

        if ($response->failed()) {
            return 'Maaf, Truevera sedang tidak bisa menjawab saat ini. Coba lagi ya! 🌸';
        }

        return $response->json('choices.0.message.content', 'Maaf, aku tidak bisa menjawab saat ini.');
    }

    /**
     * Analyze a face photo using Groq Vision and return structured skin analysis.
     */
    public function analyzeImage(string $base64Image, string $mimeType = 'image/jpeg'): array
    {
        $prompt = 'Kamu adalah ahli dermatologi dan skincare profesional. Analisis kulit wajah dari foto ini secara detail.

Berikan response HANYA dalam format JSON berikut (tanpa teks lain, tanpa markdown):
{
  "skin_type": "Normal|Kering|Berminyak|Kombinasi|Sensitif",
  "skin_score": 85,
  "score_label": "Kulitmu sangat sehat!",
  "conditions": [
    {"name": "Hidrasi", "status": "baik|cukup|kurang", "detail": "..."},
    {"name": "Pori-pori", "status": "baik|cukup|kurang", "detail": "..."},
    {"name": "Produksi Minyak", "status": "baik|cukup|kurang", "detail": "..."},
    {"name": "Elastisitas", "status": "baik|cukup|kurang", "detail": "..."},
    {"name": "Hiperpigmentasi", "status": "baik|cukup|kurang", "detail": "..."}
  ],
  "good_ingredients": [
    {"name": "Niacinamide", "benefit": "..."},
    {"name": "Hyaluronic Acid", "benefit": "..."}
  ],
  "bad_ingredients": [
    {"name": "Alkohol", "reason": "..."},
    {"name": "Fragrance", "reason": "..."}
  ],
  "morning_routine": ["Gentle Cleanser", "Toner", "Serum", "Moisturizer", "Sunscreen SPF50"],
  "night_routine": ["Double Cleansing", "Toner", "Treatment", "Moisturizer"],
  "tips": ["Tip 1...", "Tip 2...", "Tip 3..."],
  "summary": "Penjelasan singkat tentang kondisi kulit secara keseluruhan"
}';

        $payload = [
            'model'    => $this->visionModel,
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => [
                        [
                            'type'      => 'image_url',
                            'image_url' => ['url' => "data:{$mimeType};base64,{$base64Image}"],
                        ],
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
            'max_tokens' => 2048,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(60)->post($this->baseUrl . '/chat/completions', $payload);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('Groq Vision API Error: ' . $response->body());
            return ['error' => 'API request failed: ' . $response->status()];
        }

        $content = $response->json('choices.0.message.content', '{}');

        // Strip markdown code fences if present
        $content = preg_replace('/```(?:json)?\s*|\s*```/', '', trim($content));
        
        \Illuminate\Support\Facades\Log::info('Groq Vision Raw Output: ' . $content);

        // Try to extract JSON object from wherever it appears in the string
        if (preg_match('/\{.+\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $result = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Illuminate\Support\Facades\Log::error('JSON Decode Error: ' . json_last_error_msg() . ' | Raw: ' . substr($content, 0, 300));
            return ['error' => 'Gagal mem-parse response AI'];
        }

        return $result ?? ['error' => 'Response kosong dari AI'];
    }
}
