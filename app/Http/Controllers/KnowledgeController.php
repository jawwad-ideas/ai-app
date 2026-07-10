<?php

namespace App\Http\Controllers;

use App\Services\EmbeddingService;
use App\Services\VectorService;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    public function __construct(
        private VectorService $vectorService,
        private EmbeddingService $embeddingService
    ) {}

    public function createCollection()
    {
        return response()->json(
            $this->vectorService->createCollection()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);

        $embedding = $this->embeddingService->embed(
            $request->content
        );

        $result = $this->vectorService->store(
            id: $request->id,
            embedding: $embedding,
            payload: [
                'title'   => $request->title,
                'content' => $request->content,
            ]
        );

        return response()->json($result);
    }

    public function search(Request $request)
    {
        //
        // Week 3 - Next Lesson
        //
    }

    public function delete(int $id)
    {
        //
        // Later
        //
    }
}