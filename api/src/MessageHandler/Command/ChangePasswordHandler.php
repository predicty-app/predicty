<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Extension\Messenger\DispatchCommandTrait;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ChangePassword;
use App\Message\Event\UserChangedPassword;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as UserPasswordHasher;

#[AsMessageHandler]
class ChangePasswordHandler
{
    use DispatchCommandTrait;
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasher $userPasswordHasher,
    ) {
    }

    public function __invoke(ChangePassword $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $message->newPassword));
        $this->userRepository->save($user);

        $this->emit(new UserChangedPassword($user->getId()));
    }
}
