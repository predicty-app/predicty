<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Message\Command\RegisterGoogleOAuthCredentials;
use App\Repository\DataProviderCredentialsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterGoogleOAuthCredentialsHandler
{
    public function __construct(private DataProviderCredentialsRepository $repository)
    {
    }

    public function __invoke(RegisterGoogleOAuthCredentials $command): void
    {
        $credentials = $this->repository->findOrCreate($command->userId, $command->dataProvider);
        $credentials->setCredentials(['token' => $command->oauthRefreshToken]);
        $this->repository->save($credentials);
    }
}
