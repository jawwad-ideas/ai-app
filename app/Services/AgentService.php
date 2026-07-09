<?php

namespace App\Services;

use App\Tools\ToolManager;

use Illuminate\Support\Facades\Http;

class AgentService
{
    public function __construct(
        private LLMService $llm,
        private ToolManager $toolManager,
    ) {}

    public function execute(string $message): array
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
