<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\IdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
class Account implements UserOwnable
{
    use IdTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column]
    private ?string $name;

    public function __construct(Ulid $id, Ulid $userId, string $name = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name ?? '';
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
