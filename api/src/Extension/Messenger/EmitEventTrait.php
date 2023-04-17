<?php

declare(strict_types=1);

namespace App\Extension\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Contracts\Service\Attribute\Required;

trait EmitEventTrait
{
    private ?MessageBusInterface $eventBus = null;

    #[Required]
    public function setEventBus(MessageBusInterface $eventBus): void
    {
        $this->eventBus = $eventBus;
    }

    protected function emit(object $event): void
    {
        if ($this->eventBus === null) {
            throw new RuntimeException('Event bus is not set.');
        }

        $this->eventBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
    }
}
