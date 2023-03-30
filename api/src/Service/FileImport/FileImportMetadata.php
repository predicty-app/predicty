<?php

declare(strict_types=1);

namespace App\Service\FileImport;

/**
 * Additional metadata supplied to the import handler. Object is immutable.
 */
final class FileImportMetadata
{
    /**
     * @param array<string> $metadata
     */
    public function __construct(private readonly array $metadata = [])
    {
    }

    public function get(string $key, string $default = null): string|null
    {
        return $this->metadata[$key] ?? $default;
    }

    public function set(string $key, string $value): self
    {
        $metadata = $this->metadata;
        $metadata[$key] = $value;

        return new self($metadata);
    }
}
