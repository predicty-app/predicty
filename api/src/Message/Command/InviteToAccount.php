<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class InviteToAccount
{
    #[AssertCustom\UserExists]
    public readonly Ulid $userId;

    #[AssertCustom\AccountExists]
    public readonly Ulid $accountId;

    #[Assert\Email]
    #[Assert\NotBlank(message: 'You must provide an email')]
    public readonly string $email;

    public function __construct(Ulid $userId, Ulid $accountId, string $email)
    {
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->email = $email;
    }
}
