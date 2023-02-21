<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Message\Command\Register;
use App\Repository\UserRepository;
use App\Service\User\UserFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterHandler
{
    public function __construct(private UserFactory $userFactory, private UserRepository $userRepository)
    {
    }

    public function __invoke(Register $message): void
    {
        $user = $this->userFactory->create($message->email, $message->password);
        $this->userRepository->saveAndFlush($user);
    }
}
