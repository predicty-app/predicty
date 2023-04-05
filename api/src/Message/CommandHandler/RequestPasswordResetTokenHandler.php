<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\RequestPasswordResetToken;
use App\Message\Event\UserRequestedPasswordReset;
use App\Notification\UserRequestedPasswordResetNotification;
use App\Repository\UserRepository;
use App\Service\Security\PasswordReset\ResetPasswordTokenGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
class RequestPasswordResetTokenHandler
{
    use EmitEventTrait;

    public function __construct(
        private ResetPasswordTokenGenerator $resetPasswordTokenGenerator,
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private NotifierInterface $notifier
    ) {
    }

    public function __invoke(RequestPasswordResetToken $message): void
    {
        $user = $this->userRepository->findByUsername($message->username);

        if ($user !== null) {
            $token = $this->resetPasswordTokenGenerator->createToken($user);
            $url = $this->urlGenerator->generate('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $this->notifier->send(new UserRequestedPasswordResetNotification($url), $user);
            $this->emit(new UserRequestedPasswordReset($user->getId()));
        }
    }
}
