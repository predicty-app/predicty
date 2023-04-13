<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\DataRecalculation\StartAndEndDateRecalculationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recalculate-start-and-end-dates',
    description: 'Connect user account to google ads',
)]
class RecalculateStartAndEndDatesCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private StartAndEndDateRecalculationService $startAndEndDateRecalculationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Recalculates start and end dates for all campaign, ads, ad collections and ad sets.')
            ->addArgument('userId', InputArgument::REQUIRED, 'The user for who the dates should be recalculated.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = (int) $input->getArgument('userId');
        $user = $this->userRepository->findById((int) $userId);

        if ($user === null) {
            $io->writeln('User with given id was not found.');

            return Command::FAILURE;
        }

        $io->writeln('Selected user account: '.$user->getEmail());

        $this->startAndEndDateRecalculationService->recalculate($userId);

        $io->writeln('Recalculation complete');

        return Command::SUCCESS;
    }
}
