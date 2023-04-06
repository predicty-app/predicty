<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class RegistrationNotification extends Notification
{
    public function __construct(string $password)
    {
        parent::__construct('Predicty Account Registration');
        $this->content(sprintf($this->text(), $password));
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }

    private function text(): string
    {
        return <<<'EOT'
            Thank you for creating an account.
            We generated a new password for you:

            %s

            Make sure to change it after login.
            EOT;
    }
}
