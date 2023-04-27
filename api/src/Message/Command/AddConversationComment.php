<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Conversation;
use App\Entity\User;
use App\Validator as AssertCustom;
use Symfony\Component\Validator\Constraints as Assert;

class AddConversationComment
{
    #[AssertCustom\EntityExists(entity: Conversation::class, message: 'Conversation does not exist')]
    public int $conversationId;

    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    #[Assert\NotBlank(message: 'Comment cannot be empty')]
    public string $comment;

    public function __construct(int $conversationId, int $userId, string $comment)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->comment = $comment;
    }
}
