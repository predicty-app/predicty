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

    public function sendPassword(User $user, string $password): void
    {
        $text = <<<'EOT'
            Thank you for creating an account.
            <br>We generated a new password for your account: %s
            <br>
            <strong>Make sure to change your password</strong>
            EOT;

        $notification = (new Notification('Predicty Account Registration'))
            ->content(sprintf($text, $password));

        $this->notifier->send($notification, $user);
    }
}
