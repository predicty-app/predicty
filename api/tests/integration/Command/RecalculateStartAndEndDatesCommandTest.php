<?php

declare(strict_types=1);

namespace App\Tests\Integration\Command;

use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Command\RecalculateStartAndEndDatesCommand
 */
class RecalculateStartAndEndDatesCommandTest extends KernelTestCase
{
    public function test_command(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        static::getContainer()->set(StartAndEndDateRecalculationService::class, $this->createMock(StartAndEndDateRecalculationService::class));

        $command = $application->find('app:recalculate-start-and-end-dates');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 1,
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Recalculation complete', $output);
    }

    public function test_command_requires_to_provide_valid_user_id(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        static::getContainer()->set(StartAndEndDateRecalculationService::class, $this->createMock(StartAndEndDateRecalculationService::class));

        $command = $application->find('app:recalculate-start-and-end-dates');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 'not-existing-id',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User with given id was not found.', $output);
        $this->assertSame(1, $commandTester->getStatusCode());
    }
}
