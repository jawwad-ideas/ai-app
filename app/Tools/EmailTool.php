<?php

namespace App\Tools;

use App\Mail\AgentMail;
use Illuminate\Support\Facades\Mail;

class EmailTool
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(array $arguments): void
    {
        $to = $arguments['to'] ?? null;
        $subject = $arguments['subject'] ?? 'No Subject';
        $body = $arguments['body'] ?? '';

        if (!$to) {
            throw new \Exception('Recipient email is required.');
        }

        Mail::to($to)->send(
            new AgentMail(
                subjectLine: $subject,
                body: $body
            )
        );
    }
}
