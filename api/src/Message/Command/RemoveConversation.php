<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Validator as AssertCustom;

class RemoveConversation
{
    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public int $conversationId;

    #[AssertCustom\AccountExists]
    public int $accountId;

    public function __construct(int $conversationId, int $userId, int $accountId)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
        $this->accountId = $accountId;
    }
}
