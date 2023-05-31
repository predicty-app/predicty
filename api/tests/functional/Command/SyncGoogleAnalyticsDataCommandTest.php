<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Command\SyncGoogleAnalyticsDataCommand;
use App\Entity\Account;
use App\Entity\User;
use App\Message\Command\SyncGoogleAnalytics;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Command\SyncGoogleAnalyticsDataCommand
 */
class SyncGoogleAnalyticsDataCommandTest extends KernelTestCase
{
    public function test_execute(): void
    {
        $userId = Ulid::fromString('01H1VDSB1RDVRF5WWTVHM1EFMH');
        $accountId = Ulid::fromString('01H1VDSQJGBR23CPCPBTY8EYMB');

        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $commandBus = $this->createMock(MessageBusInterface::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with(new SyncGoogleAnalytics($userId, $accountId))
            ->willReturn(Envelope::wrap(new stdClass()));

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($userId);

        $account = $this->createMock(Account::class);
        $account->method('getId')->willReturn($accountId);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('getById')
            ->willReturn($user);

        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('getById')
            ->willReturn($account);

        $kernel->getContainer()->set(UserRepository::class, $userRepository);
        $kernel->getContainer()->set(AccountRepository::class, $accountRepository);
        $kernel->getContainer()->get(SyncGoogleAnalyticsDataCommand::class)->setCommandBus($commandBus);

        $command = $application->find('app:sync:google-analytics');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => '01H1VDSB1RDVRF5WWTVHM1EFMH',
            'accountId' => '01H1VDSQJGBR23CPCPBTY8EYMB',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
