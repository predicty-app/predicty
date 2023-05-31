<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class RemoveConversation
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public Ulid $conversationId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $conversationId, Ulid $userId, Ulid $accountId)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
        $this->accountId = $accountId;
    }
}
