<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmbeddingService extends GeminiClient
{
    public function embed(string $text): array
    {
        $payload = [
            'content' => [
                'parts' => [
                    [
                        'text' => $text,
                    ]
                ]
            ]
        ];

        $response = $this->post(
            'models/gemini-embedding-001:embedContent',
            $payload
        );

        if ($response->failed()) {
            throw new \Exception('Failed to generate embedding.');
        }

        return $response['embedding']['values'] ?? [];
    }
}