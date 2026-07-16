<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;

class DocumentService
{
    public function __construct(
        private EmbeddingService $embeddingService,
        private VectorService $vectorService,
    ) {}

    private function chunk(string $text, int $size = 500): array
    {
        return str_split($text, $size);
    }

    private function extractText(UploadedFile $document): string
    {
        $parser = new Parser();

        $pdf = $parser->parseFile($document->getRealPath());

        return $pdf->getText();
    }

    public function ingest(UploadedFile $document): array
    {
        // Extract text from PDF
        $content = $this->extractText($document);

        // Store chunks in Qdrant
        $this->store(
            documentId: Str::uuid()->toString(),
            title: $document->getClientOriginalName(),
            content: $content
        );

        return [
            'success' => true,
            'name'    => $document->getClientOriginalName(),
            'size'    => $document->getSize(),
            'mime'    => $document->getMimeType(),
        ];
    }

    public function store(
        int|string $documentId,
        string $title,
        string $content
    ): void {

        $chunks = $this->chunk($content);
        $this->vectorService->createCollection();

        foreach ($chunks as $index => $chunk) {

            $embedding = $this->embeddingService->embed($chunk);

            $this->vectorService->store(
                id: Str::uuid()->toString(),
                embedding: $embedding,
                payload: [
                    'document_id' => $documentId,
                    'title'       => $title,
                    'chunk_index' => $index,
                    'content'     => $chunk,
                ]
            );
        }
    }
}
