<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\DataProviderType;
use App\Entity\FileImport;
use App\Entity\Import;
use Psr\Clock\ClockInterface;

class ImportFactory
{
    public function __construct(private ClockInterface $clock)
    {
    }

    public function createFacebookFileImport(int $userId, string $filename): Import
    {
        return new FileImport(
            userId: $userId,
            filename: $filename,
            type: DataProviderType::FACEBOOK_ADS,
            createdAt: $this->clock->now()
        );
    }
}
