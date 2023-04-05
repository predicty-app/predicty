<?php

declare(strict_types=1);

namespace App\Message\EventHandler;

use App\Message\Event\UserRegistered;
use App\Notification\RegistrationNotification;
use App\Repository\UserRepository;
use App\Service\User\RandomPasswordGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class UserRegisteredHandler
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
        private RandomPasswordGenerator $randomPasswordGenerator,
        private NotifierInterface $notifier
    ) {
    }

    public function __invoke(UserRegistered $message): void
    {
        $password = $this->randomPasswordGenerator->generate();
        $user = $this->userRepository->getById($message->userId);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $this->userRepository->save($user);

        $this->notifier->send(new RegistrationNotification($password), $user);
    }
}