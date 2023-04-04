<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Extension\Messenger\EmitEventTrait;
use App\Factory\UserFactory;
use App\Message\Command\Register;
use App\Message\Event\UserRegistered;
use App\Repository\UserRepository;
use App\Service\Notifier\NotifierService;
use App\Service\Security\PasscodeGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterHandler
{
    use EmitEventTrait;

    public function __construct(
        private UserFactory $userFactory,
        private UserRepository $userRepository,
        private NotifierService $notifier,
        private PasscodeGenerator $passcodeGenerator,
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

        $this->notifier->sendPasscode($user, $this->passcodeGenerator->generate($user));
    }
}
