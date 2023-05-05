<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\UserResetPassword;
use App\Notification\PasswordResetNotification;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class UserResetPasswordHandler
{
    public function __construct(private UserRepository $userRepository, private NotifierInterface $notifier)
    {
    }

    public function __invoke(UserResetPassword $event): void
    {
        $user = $this->userRepository->getById($event->userId);
        $this->notifier->send(new PasswordResetNotification(), $user);
    }
}
