<?php

declare(strict_types=1);

namespace App\Service\Security\ConnectedAccountCredentials;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use App\Repository\ConnectedAccountRepository;
use RuntimeException;

class ConnectedAccountCredentialsProvider
{
    public function __construct(private ConnectedAccountRepository $connectedAccountRepository)
    {
    }

    public function getCredentials(int $userId, DataProvider $dataProvider): ConnectedAccount
    {
        $credentials = $this->connectedAccountRepository->find($userId, $dataProvider);

        if ($credentials === null) {
            throw new RuntimeException('Credentials for given user and data provider were not found.');
        }

        return $credentials;
    }
}
