<?php

declare(strict_types=1);

namespace App\Service\DataImport\File;

class FileImportContext
{
    /**
     * @param array<string> $metadata
     */
    public function __construct(private array $metadata = [])
    {
    }

    public function getMetadataKey(string $key, string $default = null): string|null
    {
        return $this->metadata[$key] ?? $default;
    }

    public function setMetadataKey(string $key, string $value): void
    {
        $this->metadata[$key] = $value;
    }
}
