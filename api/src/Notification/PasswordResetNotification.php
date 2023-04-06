<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class PasswordResetNotification extends Notification
{
    public function __construct()
    {
        parent::__construct('Predicty Account Password Reset');
        $this->content('Your account password was reset successfully.');
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }
}
