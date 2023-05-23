<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\DataProvider;
use App\Entity\GoogleAnalyticsConnectedAccount;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ConnectGoogleAnalytics;
use App\Message\Event\AccountConnected;
use App\Repository\ConnectedAccountRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class ConnectGoogleAnalyticsHandler
{
    use EmitEventTrait;

    public function __construct(private ConnectedAccountRepository $repository)
    {
    }

    public function __invoke(ConnectGoogleAnalytics $command): void
    {
        $connectedAccount = $this->repository->findByAccountId($command->accountId, GoogleAnalyticsConnectedAccount::class);
        $connectedAccount ??= new GoogleAnalyticsConnectedAccount(new Ulid(), $command->accountId, $command->userId);

        $connectedAccount->update($command->oauthRefreshToken, $command->ga4Id);
        $this->repository->save($connectedAccount);

        $this->emit(new AccountConnected($command->accountId, $connectedAccount->getId(), DataProvider::GOOGLE_ANALYTICS));
    }
}
