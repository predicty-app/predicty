<?php

declare(strict_types=1);

namespace App\Service\FileImport;

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
        private readonly FileImportMetadata $metadata,
        private readonly array $headers = []
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
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
