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
        return Http::$method(
            "{$this->url}{$endpoint}",
            $data
        )->json();
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
        int $id,
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
}
