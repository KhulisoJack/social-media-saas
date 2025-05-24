<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
                    'content' => "Generate 3 social media posts about: $topic. Respond with JSON: { options: [ {title: '', content: ''}, ... ]}"
                ]
            ]
        ]);

        return json_decode($response->json('choices.0.message.content'), true)['options'];
    }
}