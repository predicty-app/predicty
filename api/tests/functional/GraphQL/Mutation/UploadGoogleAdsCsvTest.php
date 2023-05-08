<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\UserFixture;
use App\Entity\Ad;
use App\Entity\AdStats;
use App\Test\GraphQLTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @covers \App\GraphQL\Mutation\UploadDataFileMutation
 */
class UploadGoogleAdsCsvTest extends GraphQLTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            UserFixture::class,
        ]);
    }

    public function test_upload_csv_file(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation($file: Upload, $type: FileImportType!) {
                  uploadDataFile(file: $file, type: $type)
                }
            EOF;

        $client = static::getClient();
        $filesystem = $client->getContainer()->get('default.storage');

        $client->request(
            method: 'POST',
            uri: '/graphql',
            parameters: [
                'operations' => json_encode([
                    'query' => $mutation,
                    'variables' => ['file' => null, 'type' => 'GOOGLE_ADS_CSV'],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/gads.csv', 'gads.csv')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadGoogleAdsCsvSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());

        $adStats = $this->getRepository(AdStats::class)->findAll();
        $this->assertCount(3, $adStats);

        $this->assertSame('2023-02-04', $adStats[0]->getDate()->format('Y-m-d'));
        $this->assertSame('2022-09-27', $adStats[1]->getDate()->format('Y-m-d'));
        $this->assertSame('2022-09-28', $adStats[2]->getDate()->format('Y-m-d'));

        $this->assertSame(2448.0, $adStats[0]->getAmountSpent()->getAmount()->toFloat());
        $this->assertSame(306.0, $adStats[0]->getCostPerResult()->getAmount()->toFloat());
        $this->assertSame(8, $adStats[0]->getResults());
        $this->assertSame('PLN', $adStats[0]->getCurrency()->getCurrencyCode());

        $ads = $this->getRepository(Ad::class)->findAll();
        $this->assertCount(1, $ads);
        $this->assertSame('621611194472', $ads[0]->getExternalId());
        $this->assertSame('Ad no. 621611194472', $ads[0]->getName());
    }
}
