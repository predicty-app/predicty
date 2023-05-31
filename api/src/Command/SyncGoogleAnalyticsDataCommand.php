<?php

declare(strict_types=1);

namespace App\Command;

use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\SyncGoogleAnalytics;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:sync:google-analytics',
    description: 'Synchronize revenue data from Google Analytics',
)]
class SyncGoogleAnalyticsDataCommand extends Command
{
    use DispatchCommandTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::OPTIONAL, 'User id that you would like to connect to Google Analytics')
            ->addArgument('accountId', InputArgument::OPTIONAL, 'User account id that you would like to use')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = $input->getArgument('userId');
        if ($userId === '') {
            $userId = $io->ask('User id', validator: fn (string $value) => Ulid::isValid($value));
        }

        $accountId = $input->getArgument('accountId');
        if ($accountId === '') {
            $accountId = $io->ask('Account id', validator: fn (string $value) => Ulid::isValid($value));
        }

        $userId = Ulid::fromString($userId);
        $accountId = Ulid::fromString($accountId);

        $user = $this->userRepository->getById($userId);
        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));

        $account = $this->accountRepository->getById($accountId);
        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));

        $this->dispatch(new SyncGoogleAnalytics($user->getId(), $account->getId()));

        return Command::SUCCESS;
    }
}
