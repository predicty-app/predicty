<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Test\AuthenticatorTrait;
use League\Flysystem\FilesystemWriter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\FileDownloadController
 */
class FileDownloadControllerTest extends WebTestCase
{
    use AuthenticatorTrait;

    public function test_download_file(): void
    {
        $client = static::createClient();
        $this->authenticate();

        /** @var FilesystemWriter $filesystem */
        $filesystem = $client->getContainer()->get(FilesystemWriter::class);
        $filesystem->write('dummy-import-1.csv', 'This,Is,A,Test');

        $client->request('GET', '/uploads/file/1');

        $this->assertResponseIsSuccessful();
        $this->assertSame('This,Is,A,Test', $client->getInternalResponse()->getContent());
        $this->assertResponseHeaderSame('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertResponseHeaderSame('Content-Disposition', 'attachment; filename="dummy-import-1.csv"');
    }

    public function test_returns404_if_user_is_not_authenticated(): void
    {
        $client = static::createClient();
        /** @var FilesystemWriter $filesystem */
        $filesystem = $client->getContainer()->get(FilesystemWriter::class);
        $filesystem->write('dummy-import-1.csv', 'This,Is,A,Test');
        $client->request('GET', '/uploads/file/1');

        $this->assertResponseStatusCodeSame(404);
    }

    public function test_returns404_if_file_was_not_found(): void
    {
        $client = static::createClient();
        $client->request('GET', '/uploads/file/12345678');
        $this->assertResponseStatusCodeSame(404);
    }
}
