<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Validator as AssertCustom;
use Symfony\Component\Validator\Constraints as Assert;

class AddConversationComment
{
    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public int $conversationId;

    #[AssertCustom\UserExists]
    public int $userId;

    #[Assert\NotBlank(message: 'Comment cannot be empty')]
    public string $comment;

    #[AssertCustom\AccountExists]
    public int $accountId;

    public function __construct(int $conversationId, int $userId, int $accountId, string $comment)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->accountId = $accountId;
    }
}
