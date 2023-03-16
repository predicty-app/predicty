<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ApiImport extends Import
{
    #[ORM\Column]
    private int $dataProviderCredentialsId;

    public function __construct(
        int $userId,
        int $dataProviderCredentialsId,
        DataProvider $type,
        DateTimeImmutable $createdAt
    ) {
        parent::__construct($userId, $type, $createdAt);
        $this->dataProviderCredentialsId = $dataProviderCredentialsId;
    }

    public function getDataProviderCredentialsId(): int
    {
        return $this->dataProviderCredentialsId;
    }
}
