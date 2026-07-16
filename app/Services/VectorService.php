<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VectorService
{
    private string $url;
    private string $collection;

    public function __construct()
    {
        $this->url = config('services.qdrant.url');
        $this->collection = config('services.qdrant.collection');
    }


    private function request(
    string $method,
    string $endpoint,
    array $data = []
): array {

    $response = Http::$method(
        "{$this->url}{$endpoint}",
        $data
    );

    \Log::info('Qdrant URL: ' . "{$this->url}{$endpoint}");
    \Log::info('Status: ' . $response->status());
    \Log::info('Body: ' . $response->body());

    return $response->json();
}

    public function createCollection(): array
    {
        return $this->request(
            'put',
            "/collections/{$this->collection}",
            [
                'vectors' => [
                    'size' => config('services.qdrant.vector_size'),
                    'distance' => 'Cosine',
                ]
            ]
        );
    }

    public function store(
        int|string $id,
        array $embedding,
        array $payload
    ): array {

        return $this->request(
            'put',
            "/collections/{$this->collection}/points",
            [
                'points' => [
                    [
                        'id' => $id,
                        'vector' => $embedding,
                        'payload' => $payload,
                    ]
                ]
            ]
        );
    }


    public function search(array $embedding, int $limit = 2): array
    {
        return $this->request(
            'post',
            "/collections/{$this->collection}/points/search",
            [
                'vector' => $embedding,
                'limit' => $limit,
                'with_payload' => true,
            ]
        );
    }
}
