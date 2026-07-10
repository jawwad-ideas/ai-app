<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiClient
{
    protected function post(string $endpoint, array $payload)
    {
        $apiKey = config('services.gemini.api_key');

        $url = "https://generativelanguage.googleapis.com/v1beta/{$endpoint}?key={$apiKey}";

        $response = Http::timeout(30)->post($url, $payload);
        
        if ($response->failed()) {
            throw new \Exception('Gemini API request failed.');
        }

        return $response;
    }
}