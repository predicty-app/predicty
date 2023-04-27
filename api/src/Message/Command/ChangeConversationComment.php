<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\ConversationComment;
use App\Entity\User;
use App\Validator as AssertCustom;

class ChangeConversationComment
{
    #[AssertCustom\EntityExists(entity: ConversationComment::class, message: 'Comment does not exist')]
    public int $commentId;

    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    public string $comment;

    public function __construct(int $commentId, int $userId, string $comment = '')
    {
        $this->commentId = $commentId;
        $this->comment = $comment;
        $this->userId = $userId;
    }
}
