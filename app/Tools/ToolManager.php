<?php

namespace App\Tools;

class ToolManager
{
    public function __construct(
        private EmailTool $emailTool,
        private MeetingTool $meetingTool,
    ) {}

    public function execute(array $step): void
    {
        match ($step['type']) {

            'send_email' =>
                $this->emailTool->execute($step['arguments']),

            'create_meeting' =>
                $this->meetingTool->execute($step['arguments']),

            default => null,
        };
    }
}