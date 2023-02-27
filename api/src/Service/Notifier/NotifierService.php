<?php

declare(strict_types=1);

namespace App\Service\Notifier;

use App\Entity\User;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class NotifierService
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function sendPasscode(User $user, string $passcode): void
    {
        $passcode = implode(' ', str_split($passcode, 3));

        $notification = (new Notification('Predicty Account Login'))
            ->content(sprintf('Your passcode is: %s', $passcode));

        $this->notifier->send($notification, $user);
    }
}
