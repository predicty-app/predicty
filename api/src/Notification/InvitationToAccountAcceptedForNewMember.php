<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

/**
 * This notification is sent to the new member when they accept an invitation to join the account.
 */
class InvitationToAccountAcceptedForNewMember extends Notification implements EmailNotificationInterface
{
    public function __construct(string $accountName)
    {
        parent::__construct();
        $this->subject(sprintf('Predicty Account - Welcome to %s', $accountName));
        $this->content(sprintf('This email is just a confirmation that you have a member access to the %s account on Predicty. Have a nice day!', $accountName));
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
            ->content($this->getContent());

        return new EmailMessage($email);
    }
}
