<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Extension\Messenger\DispatchCommandTrait;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ChangePassword;
use App\Message\Event\UserChangedPassword;
use App\Repository\UserRepository;
use App\Service\Security\CurrentUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class ChangePasswordHandler
{
    use DispatchCommandTrait;
    use EmitEventTrait;

    public function __construct(
        private CurrentUser $currentUser,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(ChangePassword $message): void
    {
        $user = $this->currentUser->getUser();
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $message->newPassword));
        $this->userRepository->save($user);

        $this->emit(new UserChangedPassword($user->getId()));
    }
}
