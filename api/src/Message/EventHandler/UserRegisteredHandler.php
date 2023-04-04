<?php

declare(strict_types=1);

namespace App\Message\EventHandler;

use App\Message\Event\UserRegistered;
use App\Repository\UserRepository;
use App\Service\Notifier\NotifierService;
use App\Service\User\RandomPasswordGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisteredHandler
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
        private RandomPasswordGenerator $randomPasswordGenerator,
        private NotifierService $notifierService
    ) {
    }

    public function __invoke(UserRegistered $message): void
    {
        $password = $this->randomPasswordGenerator->generate();
        $user = $this->userRepository->getById($message->userId);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $this->userRepository->save($user);

        $this->notifierService->sendPassword($user, $password);
    }
}
