<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LLMService extends GeminiClient
{
    public function generate(string $prompt, string $responseType = 'text'): array
    {
        // Build the final prompt based on the expected response type
        $finalPrompt = $this->buildPrompt($prompt, $responseType);

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $finalPrompt
                        ]
                    ]
                ]
            ]
        ];


        $response = $this->post(
            'models/gemini-2.5-flash:generateContent',
            $payload
        );

        if ($response->failed()) {
            throw new \Exception('Gemini API request failed.');
        }

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if ($responseType === 'json') {
            return json_decode($text, true);
        }

        return [
            'success' => true,
            'response_type' => 'text',
            'message' => $text,
            'plan' => [],
        ];

        #return $response['candidates'][0]['content']['parts'][0]['text'] ?? 'No response.';
    }


    private function buildPrompt(string $prompt, string $responseType): string
    {
        if ($responseType !== 'json') {
            return $prompt;
        }

        return <<<PROMPT
You are an AI assistant.

Return ONLY valid JSON.

Do not include markdown.
Do not use triple backticks.
Do not add explanations.

Use EXACTLY this JSON structure:

{
  "response_type": "plan",
  "message": "",
  "plan": [
    {
      "type": "",
      "arguments": {}
    }
  ]
}

Rules:

- response_type must always be "plan"
- message should be a short human-friendly summary.
- plan must always be an array.
- Every plan item must contain:
  - type
  - arguments
- Never add extra fields.
- If no action is required:

{
  "response_type":"plan",
  "message":"No action required.",
  "plan":[]
}

User Request:

{$prompt}

PROMPT;
    }
}
