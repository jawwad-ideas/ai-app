<?php

namespace App\Tools;

class MeetingTool
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(array $arguments): array
    {
        return [
            'success' => true,
            'message' => 'Meeting would be created.',
            'arguments' => $arguments,
        ];
    }
}
