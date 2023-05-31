<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\DataProvider;
use App\Message\Command\RegisterGoogleOAuthCredentials;
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
            ->addArgument('userId', InputArgument::REQUIRED, 'User id that you would like to be the owner of the connection.')
            ->addArgument('accountId', InputArgument::REQUIRED, 'Account id that you would like assign the connection to.')
            ->addArgument('service', InputArgument::REQUIRED, 'Service name: GOOGLE_ADS, GOOGLE_ANALYTICS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = $input->getArgument('userId');
        if ($userId === '') {
            $userId = $io->ask('User id', validator: fn (string $value) => Ulid::isValid($value));
            $userId = Ulid::fromString($userId);
        }

        $accountId = $input->getArgument('accountId');
        if ($accountId === '') {
            $accountId = $io->ask('Account id', validator: fn (string $value) => Ulid::isValid($value));
            $accountId = Ulid::fromString($accountId);
        }

        $service = $input->getArgument('service');
        if ($service === null) {
            $service = $io->choice('Service', ['GOOGLE_ADS', 'GOOGLE_ANALYTICS']);
        }

        $user = $this->userRepository->getById($userId);
        $io->writeln(sprintf('Selected user account: <info>%s</info>', $user->getEmail()));

        $account = $this->accountRepository->getById($accountId);
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

        $this->bus->dispatch(new RegisterGoogleOAuthCredentials($user->getId(), $account->getId(), $dataProvider, $refreshToken));
        $io->success('Refresh token was saved in the database. You can now connect using credentials stored with this user\'s account.');

        return Command::SUCCESS;
    }
}
