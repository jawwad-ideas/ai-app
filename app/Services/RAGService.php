<?php

namespace App\Services;

class RAGService
{
    public function __construct(
        private EmbeddingService $embeddingService,
        private VectorService $vectorService,
    ) {}

    public function retrieve(string $question): array
    {
        $embedding = $this->embeddingService->embed($question);

        $result = $this->vectorService->search($embedding);

        return $result['result'] ?? [];
    }

    public function buildContext(array $documents): string
    {
        if (empty($documents)) {
            return '';
        }

        $context = '';

        foreach ($documents as $document) {

            $payload = $document['payload'];

            $context .= "Title: {$payload['title']}\n";
            $context .= "Content: {$payload['content']}\n";
            $context .= "--------------------------\n";
        }

        return trim($context);
    }
}