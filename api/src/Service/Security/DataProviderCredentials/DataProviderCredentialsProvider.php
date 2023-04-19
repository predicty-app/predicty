<?php

declare(strict_types=1);

namespace App\Service\Security\DataProviderCredentials;

use App\Entity\DataProvider;
use App\Entity\DataProviderCredentials;
use App\Repository\DataProviderCredentialsRepository;
use RuntimeException;

class DataProviderCredentialsProvider
{
    public function __construct(private DataProviderCredentialsRepository $dataProviderCredentialsRepository)
    {
    }

    public function getCredentials(int $userId, DataProvider $dataProvider): DataProviderCredentials
    {
        $credentials = $this->dataProviderCredentialsRepository->find($userId, $dataProvider);

        if ($credentials === null) {
            throw new RuntimeException('Credentials for given user and data provider were not found.');
        }

        return $credentials;
    }
}
