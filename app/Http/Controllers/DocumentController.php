<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    public function index()
    {
        return view('documents');
    }


    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
        ]);

        $result = $this->documentService->ingest(
            $request->file('document')
        );

        return response()->json($result);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         #'id'      => 'required|integer',
    //         'title'   => 'required|string',
    //         'content' => 'required|string',
    //     ]);

    //     $result = $this->documentService->store(
    //         id: random_int(1, PHP_INT_MAX),
    //         title: $request->title,
    //         content: $request->content,
    //     );

    //     return response()->json($result);
    // }
}
