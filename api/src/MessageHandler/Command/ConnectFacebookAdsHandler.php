<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\FacebookAdsConnectedAccount;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ConnectFacebookAds;
use App\Message\Event\AccountConnected;
use App\Repository\ConnectedAccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class ConnectFacebookAdsHandler
{
    use EmitEventTrait;

    public function __construct(
        private ConnectedAccountRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(ConnectFacebookAds $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $connectedAccount = $this->repository->findByAccountId($command->accountId, FacebookAdsConnectedAccount::class);
        $connectedAccount ??= new FacebookAdsConnectedAccount(new Ulid(), $command->accountId, $user->getId());

        $connectedAccount->update($command->accessToken, $command->adAccountId);
        $this->repository->save($connectedAccount);

        $this->emit(new AccountConnected($command->accountId, $connectedAccount->getId(), $connectedAccount->getDataProvider()));
    }
}
