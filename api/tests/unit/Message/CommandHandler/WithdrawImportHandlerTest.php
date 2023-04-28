<?php

declare(strict_types=1);

namespace App\Tests\Unit\Message\CommandHandler;

use App\Message\Command\WithdrawImport;
use App\Message\CommandHandler\WithdrawImportHandler;
use App\Message\Event\ImportWithdrew;
use App\Repository\ImportRepository;
use App\Repository\UserRepository;
use App\Service\DataImport\ImportWithdrawalService;
use App\Service\Security\Authorization\AuthorizationChecker;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @covers \App\Message\CommandHandler\WithdrawImportHandler
 */
class WithdrawImportHandlerTest extends TestCase
{
    public function test_invoke(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $importRepository = $this->createMock(ImportRepository::class);
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturn(new Envelope(new stdClass()));

        $service = $this->createMock(ImportWithdrawalService::class);
        $service->expects($this->once())->method('withdraw')->with(2);
        $handler = new WithdrawImportHandler($service, $importRepository, $userRepository);
        $handler->setAuthorizationChecker($authorizationChecker);
        $handler->setEventBus($eventBus);

        $handler->__invoke(new WithdrawImport(1, 2));
    }

    public function test_invoke_emits_event(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $importRepository = $this->createMock(ImportRepository::class);
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())->method('dispatch')
            ->with($this->isInstanceOf(ImportWithdrew::class))
            ->willReturn(new Envelope(new stdClass()));

        $service = $this->createMock(ImportWithdrawalService::class);
        $service->expects($this->once())->method('withdraw')->with(2);

        $handler = new WithdrawImportHandler($service, $importRepository, $userRepository);
        $handler->setAuthorizationChecker($authorizationChecker);
        $handler->setEventBus($eventBus);

        $handler->__invoke(new WithdrawImport(1, 2));
    }
}
