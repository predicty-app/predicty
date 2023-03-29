<?php

declare(strict_types=1);

namespace App\Service\FileUpload;

use League\Flysystem\FilesystemWriter;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Stores uploaded files in the configured storage.
 */
class FileUploadService
{
    public function __construct(private FilesystemWriter $storage)
    {
    }

    /**
     * @retrun string The filename of the uploaded file
     */
    public function receive(UploadedFileInterface $file): string
    {
        $clientFilename = $file->getClientFilename();
        assert(is_string($clientFilename));

        $extension = pathinfo($clientFilename, \PATHINFO_EXTENSION);
        $filename = sprintf('uploads/%s.%s', sha1((string) microtime()), $extension);
        $this->storage->writeStream($filename, $file->getStream()->detach());

        return $filename;
    }
}
