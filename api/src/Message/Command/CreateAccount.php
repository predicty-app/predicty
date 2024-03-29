<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAccount
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[Assert\Length(max: 255)]
    public readonly ?string $name;

    public function __construct(Ulid $userId, ?string $name = null)
    {
        $this->name = $name;
        $this->userId = $userId;
    }
}
