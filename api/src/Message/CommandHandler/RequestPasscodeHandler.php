<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\RequestPasscode;
use App\Message\Event\UserRequestedPasscode;
use App\Notification\PasscodeNotification;
use App\Repository\UserRepository;
use App\Service\Security\PasscodeGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class RequestPasscodeHandler
{
    use EmitEventTrait;

    public function __construct(
        private UserRepository $userRepository,
        private PasscodeGenerator $passcodeGenerator,
        private NotifierInterface $notifier
    ) {
    }

    public function __invoke(RequestPasscode $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $passcode = $this->passcodeGenerator->generate($user);
        $this->notifier->send(new PasscodeNotification($passcode), $user);

        $this->emit(new UserRequestedPasscode($user->getId()));
    }
}