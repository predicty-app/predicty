<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Entity\User;
use App\Validator as AssertCustom;

class RemoveConversation
{
    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public int $conversationId;

    public function __construct(int $conversationId, int $userId)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
    }
}
