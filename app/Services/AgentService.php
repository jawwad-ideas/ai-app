<?php

namespace App\Services;
use App\Tools\EmailTool;

use Illuminate\Support\Facades\Http;

class AgentService
{
    public function __construct(private LLMService $llm, private EmailTool $emailTool) {}

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

        return $this->sendEmail($result);
    }

    private function sendEmail($result=null)
    {
        $result = [
            'success'       => true,
            'response_type' => $result['response_type'] ?? 'plan',
            'message'       => $result['message'] ?? '',
            'plan'          => $result['plan'] ?? [],
        ];

        // Execute every step
        foreach ($result['plan'] as $step) {

                    switch ($step['type']) {

                        case 'send_email':

                            $this->emailTool->execute($step['arguments']);

                            break;
                    }
        }

        return $result;

    }

   

    private  function detectResponseType(string $message): string
    {
        if (str_contains(strtolower($message), ' send email')) {
            return 'json';
        }

        if (str_contains(strtolower($message), 'meeting')) {
            return 'json';
        }


        return 'text';
    }
}
