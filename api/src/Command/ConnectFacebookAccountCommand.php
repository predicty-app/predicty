<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\DataProvider;
use App\Message\Command\ConnectFacebookAds;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Facebook\FacebookOAuth;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:connect:facebook',
    description: 'Connect user account to facebook account using OAuth 2.0',
)]
class ConnectFacebookAccountCommand extends Command
{
    public function __construct(
        private FacebookOAuth $auth,
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
        private CacheInterface $cache,
        private MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::OPTIONAL, 'User id that you would like to be the owner of the connection.')
            ->addArgument('accountId', InputArgument::OPTIONAL, 'Account id that you would like assign the connection to.')
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
        $dataProvider = DataProvider::FACEBOOK_ADS;
        $scope = $dataProvider->getOAuthScope();
        $url = $this->auth->getAuthenticationUrl($scope);
        $requestId = $this->auth->getRequestId();
        $this->cache->set($requestId, '');

        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));
        $io->writeln(sprintf('Data provider: <info>%s</info>', $dataProvider->getName()));
        $io->writeln('Requested scopes:');
        foreach ($scope as $item) {
            $io->writeln(sprintf(' - <info>%s</info>', $item));
        }
        $io->writeln('');
        $io->writeln('Go to the following url in your browser:');
        $io->writeln($url);

        while (($accessToken = $this->cache->get($requestId)) === '') {
            sleep(1);
        }

        do {
            $adAccountId = (string) $io->ask('Please provide Facebook Ad account id:');
            $adAccountId = trim($adAccountId);
        } while ($adAccountId === '');

        $this->bus->dispatch(new ConnectFacebookAds($user->getId(), $account->getId(), $accessToken, $adAccountId));
        $io->success('Account connected successfully.');

        return Command::SUCCESS;
    }
}
