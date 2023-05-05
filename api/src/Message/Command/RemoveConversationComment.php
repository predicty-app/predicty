<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\ConversationComment;
use App\Validator as AssertCustom;

class RemoveConversationComment
{
    #[AssertCustom\EntityExists(entity: ConversationComment::class, message: 'Comment does not exist')]
    public int $commentId;

    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\AccountExists]
    public int $accountId;

    public function __construct(int $commentId, int $userId, int $accountId)
    {
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
