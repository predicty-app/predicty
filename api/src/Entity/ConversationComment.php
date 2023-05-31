<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['conversationId'])]
#[ORM\Index(fields: ['createdAt'])]
class ConversationComment implements UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(type: UlidType::NAME, unique: false)]
    private Ulid $conversationId;

    #[ORM\Column]
    private string $comment;

    public function __construct(
        Ulid $id,
        Ulid $conversationId,
        Ulid $userId,
        Ulid $accountId,
        string $comment
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->conversationId = $conversationId;
        $this->comment = $comment;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getConversationId(): Ulid
    {
        return $this->conversationId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function changeComment(string $comment): void
    {
        $this->comment = $comment;
        $this->changedAt = Clock::now();
    }
}
