<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[SecurityAssert\UserPassword(message: 'Old password is not valid')]
    public string $oldPassword;

    #[Assert\NotCompromisedPassword(skipOnError: true)]
    #[Assert\NotBlank(message: 'New password should not be blank')]
    public string $newPassword;

    public function __construct(Ulid $userId, string $oldPassword, string $newPassword)
    {
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
        $this->userId = $userId;
    }

    #[Assert\IsTrue(message: 'The new password cannot match your old one')]
    public function isNewPasswordDifferent(): bool
    {
        return $this->oldPassword !== $this->newPassword;
    }
}
