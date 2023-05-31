<?php

declare(strict_types=1);

namespace App\Tests\Unit\MessageHandler\Command;

use App\Message\Command\WithdrawImport;
use App\Message\Event\ImportWithdrew;
use App\MessageHandler\Command\WithdrawImportHandler;
use App\Repository\ImportRepository;
use App\Repository\UserRepository;
use App\Service\DataImport\ImportWithdrawalService;
use App\Service\Security\Authorization\AuthorizationChecker;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\MessageHandler\Command\WithdrawImportHandler
 */
class WithdrawImportHandlerTest extends TestCase
{
    public function test_invoke(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $importId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $userRepository = $this->createMock(UserRepository::class);
        $importRepository = $this->createMock(ImportRepository::class);
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->method('dispatch')->willReturn(new Envelope(new stdClass()));

        $service = $this->createMock(ImportWithdrawalService::class);
        $service->expects($this->once())->method('withdraw')->with($importId);

        $handler = new WithdrawImportHandler($service, $importRepository, $userRepository);
        $handler->setAuthorizationChecker($authorizationChecker);
        $handler->setEventBus($eventBus);

        $handler->__invoke(new WithdrawImport($userId, $importId));
    }

    public function test_invoke_emits_event(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $importId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $userRepository = $this->createMock(UserRepository::class);
        $importRepository = $this->createMock(ImportRepository::class);
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())->method('dispatch')
            ->with($this->isInstanceOf(ImportWithdrew::class))
            ->willReturn(new Envelope(new stdClass()));

        $service = $this->createMock(ImportWithdrawalService::class);
        $service->expects($this->once())->method('withdraw')->with($importId);

        $handler = new WithdrawImportHandler($service, $importRepository, $userRepository);
        $handler->setAuthorizationChecker($authorizationChecker);
        $handler->setEventBus($eventBus);

        $handler->__invoke(new WithdrawImport($userId, $importId));
    }
}
