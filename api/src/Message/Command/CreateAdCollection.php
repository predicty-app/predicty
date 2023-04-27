<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\User;
use App\Validator as AssertCustom;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAdCollection
{
    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    #[Assert\NotBlank(message: 'You must provide a collection name')]
    public readonly string $name;

    public function __construct(int $userId, string $name)
    {
        $this->name = $name;
        $this->userId = $userId;
    }
}
