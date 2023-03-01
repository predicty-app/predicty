<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\Command\RegisterDataProvider;
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

#[AsCommand(
    name: 'app:connect:google-ads',
    description: 'Connect user account to google ads',
)]
class ConnectGoogleAdsCommand extends Command
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
            ->setDescription('This command will generate a url which should be open in the browser. '.
                'It allows you to grant access and obtain an oauth refresh token for specified account.')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id')
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

        $redirectUrl = $this->router->generate('app_google_oauth', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $url = $this->auth->getAuthenticationUrl($redirectUrl);
        $requestId = $this->auth->getRequestId();
        $this->cache->set($requestId, '');

        $io->writeln('Go to the following url in your browser:');
        $io->writeln($url);

        while (($refreshToken = $this->cache->get($requestId)) === '') {
            sleep(1);
        }

        $this->bus->dispatch(new RegisterDataProvider($userId, $refreshToken));

        $io->success('Refresh token was saved in the database. '.
            'You can now connect to the Google Ads using this user\'s account');

        return Command::SUCCESS;
    }
}
