<?php

declare(strict_types=1);

namespace App\Message\Command;

class RemoveConversationComment
{
    public int $commentId;
    public int $userId;

    public function __construct(int $commentId, int $userId)
    {
        $this->commentId = $commentId;
        $this->userId = $userId;
    }
}
