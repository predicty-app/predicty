<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

use Symfony\Component\Uid\Ulid;

/**
 * @internal
 */
final class FileImportContext
{
    /**
     * @param array<string> $headers
     */
    public function __construct(
        private readonly Ulid $userId,
        private readonly Ulid $accountId,
        private readonly FileImportMetadata $metadata = new FileImportMetadata(),
        private readonly array $headers = []
    ) {
    }

    public function getUserId(): Ulid
    {
        return $this->userId;
    }

public function getAccountId(): Ulid
{
    return $this->accountId;
}

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMetadata(): FileImportMetadata
    {
        return $this->metadata;
    }
}
