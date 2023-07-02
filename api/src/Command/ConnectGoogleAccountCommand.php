<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\DataProvider;
use App\Message\Command\ConnectGoogleAds;
use App\Message\Command\ConnectGoogleAnalytics;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use App\Service\Google\GoogleOAuth;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:connect:google',
    description: 'Connect user account to google account using OAuth 2.0',
)]
class ConnectGoogleAccountCommand extends Command
{
    public function __construct(
        private RouterInterface $router,
        private GoogleOAuth $auth,
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
            ->addArgument('service', InputArgument::OPTIONAL, 'Service name: GOOGLE_ADS, GOOGLE_ANALYTICS')
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

        $service = $input->getArgument('service');
        while ($service === null) {
            $service = $io->choice('Service', ['GOOGLE_ADS', 'GOOGLE_ANALYTICS']);
        }

        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));

        $dataProvider = DataProvider::from($service);
        $io->writeln(sprintf('Data provider: <info>%s</info>', $dataProvider->getName()));

        $scope = $dataProvider->getOAuthScope();
        $io->writeln('Requested scopes:');
        foreach ($scope as $item) {
            $io->writeln(sprintf(' - <info>%s</info>', $item));
        }
        $io->writeln('');

        $redirectUrl = $this->router->generate('app_google_oauth', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $url = $this->auth->getAuthenticationUrl($redirectUrl, $scope);
        $requestId = $this->auth->getRequestId();
        $this->cache->set($requestId, '');

        $io->writeln('Go to the following url in your browser:');
        $io->writeln($url);

        while (($refreshToken = $this->cache->get($requestId)) === '') {
            sleep(1);
        }

        if ($dataProvider === DataProvider::GOOGLE_ADS) {
            do {
                $clientAccountId = (string) $io->ask('Please provide the client account id:');
                $clientAccountId = trim(str_ireplace('-', '', $clientAccountId));
            } while ($clientAccountId === '');

            $this->bus->dispatch(new ConnectGoogleAds($user->getId(), $account->getId(), $refreshToken, $clientAccountId));
        }

        if ($dataProvider === DataProvider::GOOGLE_ANALYTICS) {
            do {
                $ga4id = (string) $io->ask('Please provide GA4 id:');
                $ga4id = trim(str_ireplace('-', '', $ga4id));
            } while ($ga4id === '');

            $this->bus->dispatch(new ConnectGoogleAnalytics($user->getId(), $account->getId(), $refreshToken, $ga4id));
        }

        $io->success('Account connected successfully.');

        return Command::SUCCESS;
    }
}
