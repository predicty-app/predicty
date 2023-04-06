<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class PasswordResetTokenIssuedNotification extends Notification implements EmailNotificationInterface
{
    public function __construct(private string $passwordResetLink)
    {
        parent::__construct('Predicty Account Password Reset Requested');
        $this->content('Use link below to reset your password');
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        $email = NotificationEmail::asPublicEmail()
            ->to($recipient->getEmail())
            ->subject($this->getSubject())
            ->content($this->getContent())
            ->action('Reset password', $this->passwordResetLink);

        return new EmailMessage($email);
    }
}
