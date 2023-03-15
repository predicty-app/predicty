<?php

declare(strict_types=1);

namespace App\Extension\Messenger;

use Symfony\Component\Messenger\HandleTrait as SymfonyHandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Adds message bus as a required dependency and allows handling messages.
 */
trait HandleTrait
{
    use SymfonyHandleTrait;
    private MessageBusInterface $messageBus;

    #[Required]
    public function setMessageBus(MessageBusInterface $messageBus): void
    {
        $this->messageBus = $messageBus;
    }
}
