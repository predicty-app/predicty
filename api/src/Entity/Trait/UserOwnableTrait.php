<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Entity\UserWithId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

trait UserOwnableTrait
{
    #[ORM\Column(type: UlidType::NAME, unique: false)]
    private Ulid $userId;

    public function getUserId(): Ulid
    {
        return $this->userId;
    }

    public function isOwnedBy(UserWithId $user): bool
    {
        return $this->userId->equals($user->getId());
    }
}
