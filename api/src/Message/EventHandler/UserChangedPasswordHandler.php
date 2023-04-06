<?php

declare(strict_types=1);

namespace App\Message\EventHandler;

use App\Message\Event\UserChangedPassword;
use App\Notification\PasswordChangedNotification;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class UserChangedPasswordHandler
{
    public function __construct(private UserRepository $userRepository, private NotifierInterface $notifier)
    {
    }

    public function __invoke(UserChangedPassword $event): void
    {
        $user = $this->userRepository->getById($event->userId);
        $this->notifier->send(new PasswordChangedNotification(), $user);
    }
}
