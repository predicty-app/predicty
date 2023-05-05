<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\ConversationComment;
use App\Validator as AssertCustom;

class ChangeConversationComment
{
    #[AssertCustom\EntityExists(entity: ConversationComment::class, message: 'Comment does not exist')]
    public int $commentId;

    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\AccountExists]
    public int $accountId;

    public string $comment;

    public function __construct(int $commentId, int $userId, int $accountId, string $comment = '')
    {
        $this->commentId = $commentId;
        $this->comment = $comment;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
