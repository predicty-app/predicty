<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use App\Entity\User;
use App\Message\Command\SyncGoogleAnalytics;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\Command\SyncGoogleAnalyticsDataCommand
 */
class SyncGoogleAnalyticsDataCommandTest extends KernelTestCase
{
    use InteractsWithMessenger;

    public function test_execute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $this->transport('sync')->intercept();

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(3);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('findById')
            ->with(3)
            ->willReturn($user);

        $kernel->getContainer()->set(UserRepository::class, $userRepository);

        $command = $application->find('app:sync:google-analytics');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => '3',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $this->transport('sync')->dispatched()->assertContains(SyncGoogleAnalytics::class);
    }
}
