<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Ulid;

interface UserWithId
{
    public function getId(): Ulid;
}
