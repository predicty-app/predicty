<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\Facebook\CsvImporter\FacebookCsvImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:facebook:import-csv',
    description: 'Imports data from a Facebook CSV file',
)]
class ImportDataFromFacebookCsvCommand extends Command
{
    public function __construct(private FacebookCsvImporter $csvImporter, private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::REQUIRED, 'User for whom that the imported data will be assigned to')
            ->addArgument('filename', InputArgument::REQUIRED, 'Filename of the imported file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = (int) $input->getArgument('userId');
        $filename = $input->getArgument('filename');

        if (file_exists($filename) === false) {
            $io->writeln(sprintf('File does not exist: "%s"', $filename));

            return Command::FAILURE;
        }

        $user = $this->userRepository->findById((int) $userId);

        if ($user === null) {
            $io->writeln('User with given id was not found.');

            return Command::FAILURE;
        }

        $io->writeln('Selected user account: '.$user->getEmail());
        $this->csvImporter->import($userId, $filename);

        $io->success('Import complete');

        return Command::SUCCESS;
    }
}
