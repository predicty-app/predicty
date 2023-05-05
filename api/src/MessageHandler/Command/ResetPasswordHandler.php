<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Extension\Messenger\EmitEventTrait;
use App\GraphQL\Exception\ClientSafeException;
use App\Message\Command\ResetPassword;
use App\Message\Event\UserResetPassword;
use App\Repository\UserRepository;
use App\Service\Security\PasswordReset\PasswordResetTokenValidator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class ResetPasswordHandler
{
    use EmitEventTrait;

    public function __construct(
        private PasswordResetTokenValidator $resetPasswordTokenValidator,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(ResetPassword $message): void
    {
        $userId = $this->resetPasswordTokenValidator->validateAndGetUserId($message->token);

        if ($userId === null) {
            throw new ClientSafeException('Invalid password reset token. Please request a new one.');
        }

        $user = $this->userRepository->getById($userId);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $message->password));
        $this->userRepository->save($user);
        $this->emit(new UserResetPassword($user->getId()));
    }
}
