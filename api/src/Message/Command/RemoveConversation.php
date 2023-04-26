<?php

declare(strict_types=1);

namespace App\Message\Command;

class RemoveConversation
{
    public int $userId;
    public int $conversationId;

    public function __construct(int $conversationId, int $userId)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
    }
}
