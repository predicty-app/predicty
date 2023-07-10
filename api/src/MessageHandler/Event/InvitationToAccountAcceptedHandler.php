<?php

declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\InvitationToAccountAccepted;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class InvitationToAccountAcceptedHandler
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function __invoke(InvitationToAccountAccepted $event): void
    {
    }
}
