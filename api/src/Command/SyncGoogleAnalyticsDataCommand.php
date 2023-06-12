<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\GoogleAnalyticsConnectedAccount;
use App\Extension\Messenger\DispatchCommandTrait;
use App\Message\Command\SyncGoogleAnalytics;
use App\Repository\AccountRepository;
use App\Repository\ConnectedAccountRepository;
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
        private ConnectedAccountRepository $connectedAccountRepository,
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
        while ($userId === null) {
            $userId = $io->ask('User id', validator: fn (string $value) => Ulid::fromString($value));
        }

        $accountId = $input->getArgument('accountId');
        while ($accountId === null) {
            $accountId = $io->ask('Account id', validator: fn (string $value) => Ulid::fromString($value));
        }

        $userId = $userId instanceof Ulid ? $userId : Ulid::fromString($userId);
        $accountId = $accountId instanceof Ulid ? $accountId : Ulid::fromString($accountId);

        $user = $this->userRepository->getById($userId);
        $account = $this->accountRepository->getById($accountId);

        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));
        $connectedAccount = $this->connectedAccountRepository->findByAccountId($account->getId(), GoogleAnalyticsConnectedAccount::class);

        if ($connectedAccount === null) {
            $io->error('No connected Google Analytics account found for this user and account id');

            return Command::FAILURE;
        }

        $this->dispatch(new SyncGoogleAnalytics($user->getId(), $account->getId(), $connectedAccount->getId()));

        return Command::SUCCESS;
    }
}
