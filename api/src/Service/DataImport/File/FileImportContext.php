<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

/**
 * @internal
 */
final class FileImportContext
{
    /**
     * @param array<string> $headers
     */
    public function __construct(
        private readonly int $userId,
        private readonly int $accountId,
        private readonly FileImportMetadata $metadata,
        private readonly array $headers = []
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

public function getAccountId(): int
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
