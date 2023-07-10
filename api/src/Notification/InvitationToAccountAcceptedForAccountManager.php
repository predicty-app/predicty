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
 * This notification is sent to the account manager when a new member accepts an invitation to join the account.
 */
class InvitationToAccountAcceptedForAccountManager extends Notification implements EmailNotificationInterface
{
    public function __construct(string $newMemberEmail, string $accountName)
    {
        parent::__construct('Predicty Account - New member');
        $this->content(sprintf(
            'This message is a confirmation that the person you invited ("%s") '
            .'has accepted the invitation to the "%s" account.'
            .'This person has now member access to your account.',
            $newMemberEmail,
            $accountName
        ));
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
