<?php

declare(strict_types=1);

namespace App\Service\FileUpload;

use League\Flysystem\FilesystemOperator;
use Psr\Http\Message\UploadedFileInterface;

class FileUploadService
{
    public function __construct(private FilesystemOperator $storage)
    {
    }

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
