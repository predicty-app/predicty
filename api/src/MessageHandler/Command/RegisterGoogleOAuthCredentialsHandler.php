<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\ConnectedAccount;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\RegisterGoogleOAuthCredentials;
use App\Message\Event\AccountConnected;
use App\Repository\ConnectedAccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterGoogleOAuthCredentialsHandler
{
    use EmitEventTrait;

    public function __construct(
        private ConnectedAccountRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(RegisterGoogleOAuthCredentials $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $connectedAccount = $this->repository->find($command->userId, $command->dataProvider);

        if ($connectedAccount === null) {
            $connectedAccount = new ConnectedAccount($command->accountId, $user->getId(), $command->dataProvider);
        }

        $connectedAccount->setCredentials(['token' => $command->oauthRefreshToken]);
        $this->repository->save($connectedAccount);

        $this->emit(new AccountConnected($command->accountId, $user->getId(), $command->dataProvider));
    }
}
