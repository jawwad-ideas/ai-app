<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private AgentService $agentService
    ) {}


    public function index()
    {
        return view('chat');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $reply = $this->agentService->execute($request->message);

       return response()->json($reply);
    }
}