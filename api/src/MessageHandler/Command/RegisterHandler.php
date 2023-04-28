<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Extension\Messenger\DispatchCommandTrait;
use App\Extension\Messenger\EmitEventTrait;
use App\Entity\Factory\UserFactory;
use App\Message\Command\Register;
use App\Message\Command\RequestPasscode;
use App\Message\Event\UserRegistered;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterHandler
{
    use DispatchCommandTrait;
    use EmitEventTrait;

    public function __construct(
        private UserFactory $userFactory,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(Register $message): void
    {
        $user = $this->userRepository->findByUsername($message->email);

        if ($user === null) {
            $user = $this->userFactory->create($message->email);
            $this->userRepository->save($user);
            $this->emit(new UserRegistered($user->getId()));
        }

        $this->dispatch(new RequestPasscode($user->getId()));
    }
}
