<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VectorService
{
    private string $url;

    public function __construct()
    {
        $this->url = config('services.qdrant.url');
    }

    public function createCollection()
    {
        return Http::put(
            "{$this->url}/collections/" . config('services.qdrant.collection'),
            [
                'vectors' => [
                    'size' => 3072,
                    'distance' => 'Cosine',
                ]
            ]
        )->json();
    }
}
