<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\DataProvider;
use App\Message\Command\RegisterGoogleOAuthCredentials;
use App\Repository\UserRepository;
use App\Service\Google\GoogleOAuth;
use InvalidArgumentException;
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
        private CacheInterface $cache,
        private MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::OPTIONAL, 'User account Id that you would like to connect.')
            ->addArgument('service', InputArgument::OPTIONAL, 'Service name: GOOGLE_ADS, GOOGLE_ANALYTICS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userId = (int) $input->getArgument('userId');
        if ($userId === 0) {
            $userId = (int) $io->ask('User id');
        }

        $service = $input->getArgument('service');
        if ($service === null) {
            $service = $io->choice('Service', ['GOOGLE_ADS', 'GOOGLE_ANALYTICS']);
        }

        $user = $this->userRepository->findById($userId) ?? throw new InvalidArgumentException('User with given id was not found.');
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

        $this->bus->dispatch(new RegisterGoogleOAuthCredentials($user->getId(), $dataProvider, $refreshToken));
        $io->success('Refresh token was saved in the database. You can now connect using credentials stored with this user\'s account.');

        return Command::SUCCESS;
    }
}
