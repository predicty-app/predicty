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

/**
 * @covers \App\Command\SyncGoogleAnalyticsDataCommand
 */
class SyncGoogleAnalyticsDataCommandTest extends KernelTestCase
{
    public function test_execute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $commandBus = $this->createMock(MessageBusInterface::class);
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with(new SyncGoogleAnalytics(3, 5))
            ->willReturn(Envelope::wrap(new stdClass()));

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(3);

        $account = $this->createMock(Account::class);
        $account->method('getId')->willReturn(5);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('getById')
            ->with(3)
            ->willReturn($user);

        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('getById')
            ->with(5)
            ->willReturn($account);

        $kernel->getContainer()->set(UserRepository::class, $userRepository);
        $kernel->getContainer()->set(AccountRepository::class, $accountRepository);
        $kernel->getContainer()->get(SyncGoogleAnalyticsDataCommand::class)->setCommandBus($commandBus);

        $command = $application->find('app:sync:google-analytics');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => '3',
            'accountId' => '5',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
