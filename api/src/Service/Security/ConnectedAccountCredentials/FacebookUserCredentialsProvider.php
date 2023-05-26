<?php

declare(strict_types=1);

namespace App\Service\Security\ConnectedAccountCredentials;

use App\Repository\ConnectedAccountRepository;
use App\Service\Facebook\FacebookUserCredentials;
use App\Service\Facebook\FacebookUserCredentialsProvider as FacebookUserCredentialsProviderInterface;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class FacebookUserCredentialsProvider implements FacebookUserCredentialsProviderInterface
{
    public function __construct(private ConnectedAccountRepository $connectedAccountRepository)
    {
    }

    public function getCredentials(Ulid $connectedAccountId): FacebookUserCredentials
    {
        $credentials = $this->connectedAccountRepository->findById($connectedAccountId);

        if (!$credentials instanceof FacebookUserCredentials) {
            throw new RuntimeException('Credentials were not found.');
        }

        return $credentials;
    }
}
