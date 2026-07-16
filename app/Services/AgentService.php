<?php

namespace App\Services;

use App\Tools\ToolManager;

use Illuminate\Support\Facades\Http;

class AgentService
{
    public function __construct(
        private LLMService $llm,
        private RAGService $rag,
        private ToolManager $toolManager,
    ) {}

    public function oldExecute(string $message): array
    {
        $responseType = $this->detectResponseType($message);

        $result = $this->llm->generate(
            prompt: $message,
            responseType: $responseType
        );

        if (!empty($result['plan'])) {
            foreach ($result['plan'] as $step) {
                $this->toolManager->execute($step);
            }
        }

        return $result;
    }


    public function execute(string $message): array
    {
        // Step 1: Retrieve relevant documents
        $documents = $this->rag->retrieve($message);

        //direct response from qudrant 
        // return [
        //     'success' => true,
        //     'response_type' => 'text',
        //     'message' => ['message' => $documents[0]['payload']['content'] ?? 'No information found.'],
        //     'plan' => [],
        // ];

        // Step 2: Convert them into context
        $context = $this->rag->buildContext($documents);

        // Step 3: Ask Gemini using that context
        $result = $this->llm->generateWithContext(
            question: $message,
            context: $context
        );

        return [
            'message' => $result,
        ];
    }

    private function detectResponseType(string $message): string
    {
        $message = strtolower($message);

        if (
            str_contains($message, 'email') ||
            str_contains($message, 'meeting') ||
            str_contains($message, 'task')
        ) {
            return 'json';
        }

        return 'text';
    }
}
