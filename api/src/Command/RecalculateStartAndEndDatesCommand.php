<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\AccountRepository;
use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:recalculate-start-and-end-dates',
    description: 'Recalculates start and end dates for all campaign, ads, ad collections and ad sets.',
)]
class RecalculateStartAndEndDatesCommand extends Command
{
    public function __construct(
        private AccountRepository $accountRepository,
        private StartAndEndDateRecalculationService $startAndEndDateRecalculationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('accountId', InputArgument::REQUIRED, 'The account that should be recalculated.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $accountId = $input->getArgument('accountId');
        $accountId = Ulid::fromString($accountId);
        $account = $this->accountRepository->findById($accountId);

        if ($account === null) {
            $io->writeln('Account with given id was not found.');

            return Command::FAILURE;
        }

        $io->writeln('Recalculation started ...');
        $this->startAndEndDateRecalculationService->recalculate($account->getId());
        $io->writeln('Recalculation complete');

        return Command::SUCCESS;
    }
}
