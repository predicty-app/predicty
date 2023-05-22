<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\CreateAccount;
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
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(Register $message): void
    {
        $user = $this->userRepository->findByUsername($message->email);
        $callbacks[] = fn (User $user) => $this->dispatch(new RequestPasscode($user->getId()));

        if ($user === null) {
            $user = $this->userFactory->create($message->email);
            $callbacks[] = fn (User $user) => $this->dispatch(new CreateAccount($user->getId()));
            $callbacks[] = fn (User $user) => $this->emit(new UserRegistered($user->getId(), $user->getAcceptedTermsOfServiceVersion(), $user->hasAgreedToNewsletter()));
        }

        if ($message->hasAgreedToNewsletter) {
            $user->setAgreedToNewsletter();
        }

        $user->setAgreedToTerms($message->acceptedTermsOfServiceVersion);
        $this->userRepository->save($user);

        foreach ($callbacks as $callback) {
            $callback($user);
        }
    }
}
