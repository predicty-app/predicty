<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Message\Command\CompleteOnboarding;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CompleteOnboardingHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(CompleteOnboarding $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $user->setOnboardingComplete();
        $this->userRepository->save($user);
    }
}
