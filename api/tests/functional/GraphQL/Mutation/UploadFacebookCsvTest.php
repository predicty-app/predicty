<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\UserFixtures;
use App\Entity\AdStats;
use App\Test\GraphQLTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @covers \App\GraphQL\Mutation\UploadDataFileMutation
 */
class UploadFacebookCsvTest extends GraphQLTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            UserFixtures::class,
        ]);
    }

    public function test_upload_facebook_csv_file(): void
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
                    'variables' => ['file' => null, 'type' => 'FACEBOOK_CSV'],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/facebook.csv', 'facebook-csv.txt')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadDataFileSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());

        $adStats = $this->getRepository(AdStats::class)->findAll();
        $this->assertCount(2, $adStats);

        $this->assertSame('2023-02-04', $adStats[0]->getDate()->format('Y-m-d'));
        $this->assertSame('2023-02-03', $adStats[1]->getDate()->format('Y-m-d'));

        $this->assertSame(140.86, $adStats[1]->getAmountSpent()->getAmount()->toFloat());
        $this->assertSame(28.17, $adStats[1]->getCostPerResult()->getAmount()->toFloat());
    }

    public function test_upload_allows_sending_campaign_name(): void
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
                    'variables' => [
                        'file' => null,
                        'type' => 'FACEBOOK_CSV',
                        'campaignName' => 'Test campaign',
                    ],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/facebook.csv', 'facebook-csv.txt')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadDataFileSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());
    }
}
