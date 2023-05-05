<?php

declare(strict_types=1);

namespace App\Entity;

use RuntimeException;

trait OwnableTrait
{
    public function isOwnedBy(UserWithId $user): bool
    {
        if (!property_exists($this, 'userId')) {
            throw new RuntimeException(sprintf('Ownable trait requires the "userId" property to be defined on %s', $this::class));
        }

        return $this->userId === $user->getId();
    }
}
