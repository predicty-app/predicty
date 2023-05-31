<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class AddConversationComment
{
    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public Ulid $conversationId;

    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[Assert\NotBlank(message: 'Comment cannot be empty')]
    public string $comment;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $conversationId, Ulid $userId, Ulid $accountId, string $comment)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->accountId = $accountId;
    }
}
