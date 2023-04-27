<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\ConversationComment;
use App\Entity\User;
use App\Validator as AssertCustom;

class RemoveConversationComment
{
    #[AssertCustom\EntityExists(entity: ConversationComment::class, message: 'Comment does not exist')]
    public int $commentId;

    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    public function __construct(int $commentId, int $userId)
    {
        $this->commentId = $commentId;
        $this->userId = $userId;
    }
}
