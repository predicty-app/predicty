<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['conversationId'])]
#[ORM\Index(fields: ['createdAt'])]
class ConversationComment implements Ownable, BelongsToAccount
{
    use BelongsToAccountTrait;
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private int $conversationId;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column]
    private string $comment;

    public function __construct(int $conversationId, int $userId, int $accountId, string $comment)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->accountId = $accountId;
    }

    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    public function getUserId(): int
    {
        return $this->userId;
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

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->userId === $user->getId();
    }
}
