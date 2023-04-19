<?php

declare(strict_types=1);

namespace App\Extension\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait DispatchCommandTrait
{
    /**
     * @internal do not use it directly, use dispatch method instead
     */
    private ?MessageBusInterface $commandBus = null;

    #[Required]
    public function setCommandBus(MessageBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param array<StampInterface> $stamps
     */
    public function dispatch(object $event, array $stamps = []): void
    {
        if ($this->commandBus === null) {
            throw new RuntimeException('Message bus is not set.');
        }

        $stamps = array_merge($stamps, [new DispatchAfterCurrentBusStamp()]);
        $this->commandBus->dispatch($event, $stamps);
    }
}
