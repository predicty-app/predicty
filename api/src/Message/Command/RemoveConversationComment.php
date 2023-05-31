<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\ConversationComment;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class RemoveConversationComment
{
    #[AssertCustom\EntityExists(entity: ConversationComment::class, message: 'Comment does not exist')]
    public Ulid $commentId;

    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $commentId, Ulid $userId, Ulid $accountId)
    {
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
