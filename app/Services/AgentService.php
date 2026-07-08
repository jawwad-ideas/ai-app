<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AgentService
{
    public function __construct(private LLMService $llm) {}

    public function execute(string $message): array
    {
        $responseType = $this->detectResponseType($message);

        $result = $this->llm->generate(
            prompt: $message,
            responseType: $responseType
        );

        // Normal chat response
        if ($responseType === 'text') {
            return [
                'success' => true,
                'response_type' => 'text',
                'message' => $result,
                'plan' => [],
            ];
        }

        // JSON response from Gemini
        return [
            'success'       => true,
            'response_type' => $result['response_type'] ?? 'plan',
            'message'       => $result['message'] ?? '',
            'plan'          => $result['plan'] ?? [],
        ];
    }

    private  function detectResponseType(string $message): string
    {
        if (str_contains(strtolower($message), 'email')) {
            return 'json';
        }

        if (str_contains(strtolower($message), 'meeting')) {
            return 'json';
        }


        return 'text';
    }
}
