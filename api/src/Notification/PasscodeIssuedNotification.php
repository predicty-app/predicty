<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class PasscodeIssuedNotification extends Notification
{
    public function __construct(string $passcode)
    {
        parent::__construct('Predicty Account Login');
        $this->content(sprintf('Your passcode is: %s', implode(' ', str_split($passcode, 3))));
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }
}
