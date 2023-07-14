<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class AccountInvitationIssuedNotification extends Notification implements EmailNotificationInterface
{
    public function __construct(string $accountName, private string $invitationLink)
    {
        parent::__construct('Predicty Account Invitation');
        $this->content(sprintf('Use link below to accept an invitation from %s', $accountName));
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
            ->action('Accept invitation', $this->invitationLink);

        return new EmailMessage($email);
    }
}
