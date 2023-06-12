<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Role;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeAccountRole
{
    #[AssertCustom\UserExists]
    public Ulid $actingUserId;

    #[AssertCustom\UserExists]
    public Ulid $affectedUserId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    #[Assert\Choice(choices: [Role::ROLE_ACCOUNT_MEMBER, Role::ROLE_ACCOUNT_OWNER], message: 'Invalid role')]
    public string $role;

    public function __construct(Ulid $actingUserId, Ulid $affectedUserId, Ulid $accountId, string $role)
    {
        $this->actingUserId = $actingUserId;
        $this->affectedUserId = $affectedUserId;
        $this->role = $role;
        $this->accountId = $accountId;
    }
}
