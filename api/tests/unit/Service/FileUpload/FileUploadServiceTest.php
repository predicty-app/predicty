<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\FileUpload;

use App\Service\FileUpload\FileUploadService;
use League\Flysystem\FilesystemWriter;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @covers \App\Service\FileUpload\FileUploadService
 */
class FileUploadServiceTest extends TestCase
{
    public function test_receive(): void
    {
        $file = $this->createMock(UploadedFileInterface::class);
        $file->method('getClientFilename')->willReturn('test.txt');
        $file->method('getStream')->willReturn(Stream::create('testing'));

        $filesystem = $this->createMock(FilesystemWriter::class);
        $filesystem->expects($this->once())->method('writeStream')->with(
            $this->matchesRegularExpression('#^uploads\/[a-z0-9]{40}\.txt$#'),
            $this->isType(IsType::TYPE_RESOURCE),
        );

        $service = new FileUploadService($filesystem);
        $service->receive($file);
    }
}
