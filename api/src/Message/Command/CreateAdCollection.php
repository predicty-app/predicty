<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAdCollection
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[Assert\NotBlank(message: 'You must provide a collection name')]
    public readonly string $name;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $userId, Ulid $accountId, string $name)
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
