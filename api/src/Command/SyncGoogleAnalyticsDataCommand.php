<?php

declare(strict_types=1);

namespace App\Command;

use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\SyncGoogleAnalytics;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync:google-analytics',
    description: 'Synchronize revenue data from Google Analytics',
)]
class SyncGoogleAnalyticsDataCommand extends Command
{
    use DispatchCommandTrait;

    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::OPTIONAL, 'User account id that you would like to connect to Google Analytics')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = (int) $input->getArgument('userId');
        if ($userId === 0) {
            $userId = (int) $io->ask('User id');
        }

        $user = $this->userRepository->findById($userId) ?? throw new InvalidArgumentException('User with given id was not found.');
        $this->dispatch(new SyncGoogleAnalytics($user->getId()));

        return Command::SUCCESS;
    }
}
