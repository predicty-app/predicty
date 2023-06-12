<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\DataProvider;
use App\Entity\GoogleAdsConnectedAccount;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\ConnectGoogleAds;
use App\Message\Event\AccountConnected;
use App\Repository\ConnectedAccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class ConnectGoogleAdsHandler
{
    use EmitEventTrait;

    public function __construct(
        private ConnectedAccountRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(ConnectGoogleAds $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $connectedAccount = $this->repository->findByAccountId($command->accountId, GoogleAdsConnectedAccount::class);
        $connectedAccount ??= new GoogleAdsConnectedAccount(new Ulid(), $command->accountId, $user->getId());

        $connectedAccount->update($command->refreshToken, $command->customerId);
        $this->repository->save($connectedAccount);

        $this->emit(new AccountConnected($command->accountId, $connectedAccount->getId(), DataProvider::GOOGLE_ADS));
    }
}
