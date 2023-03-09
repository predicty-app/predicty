<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\RegisterDataProvider;
use App\Repository\DataProviderCredentialsRepository;
use App\Service\User\CurrentUserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterDataProviderHandler
{
    public function __construct(
        private CurrentUserService $currentUserService,
        private DataProviderCredentialsRepository $repository
    ) {
    }

    public function __invoke(RegisterDataProvider $command): void
    {
        $userId = $this->currentUserService->getUser()->getId();
        $credentials = $this->repository->findOrCreate($userId, $command->type);
        $credentials->setCredentials(['token' => $command->oauthRefreshToken]);
        $this->repository->save($credentials);
    }
}
