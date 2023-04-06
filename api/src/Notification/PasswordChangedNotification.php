<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class PasswordChangedNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('Predicty Account Password Changed');
        $this->content('Your account password was changed successfully.');
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }
}
