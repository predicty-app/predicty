<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\RegisterDataProvider;
use App\Repository\DataProviderCredentialsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterDataProviderHandler
{
    public function __construct(private DataProviderCredentialsRepository $repository)
    {
    }

    public function __invoke(RegisterDataProvider $command): void
    {
        $credentials = $this->repository->findOrCreate($command->userId, $command->type);
        $credentials->setCredentials(['token' => $command->oauthRefreshToken]);
        $this->repository->save($credentials);
    }
}
