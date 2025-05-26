<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGptService
{
    public function generateContent(string $topic): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo-1106',
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a helpful assistant. Generate 3 creative social media posts about: $topic. Respond ONLY with valid JSON in this format: { \"options\": [ {\"title\": \"\", \"content\": \"\"}, ... ] }"
                ]
            ]
        ]);

        // Log the entire response for debugging
        Log::info('OpenAI full response:', ['response' => $response->json()]);
        Log::info('OpenAI status:', ['status' => $response->status()]);
        Log::info('OpenAI headers:', ['headers' => $response->headers()]);
        Log::info('OpenAI body:', ['body' => $response->body()]);

        // Check for API errors
        if (!$response->ok()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $content = $response->json('choices.0.message.content');
        Log::info('OpenAI raw content:', ['content' => $content]);
        $decoded = $content ? json_decode($content, true) : null;

        if (is_array($decoded) && isset($decoded['options'])) {
            return $decoded['options'];
        }

        throw new \Exception('OpenAI response did not contain options.');
    }
}