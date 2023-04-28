<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\UserFixture;
use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Entity\DataProvider;
use App\Test\GraphQLTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @covers \App\GraphQL\Mutation\UploadDataFileMutation
 */
class UploadSimplifiedCsvTest extends GraphQLTestCase
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
                mutation($file: Upload, $campaignName: String, $type: FileImportType!) {
                  uploadDataFile(file: $file, type: $type, campaignName: $campaignName)
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
                        'type' => 'OTHER_SIMPLIFIED_CSV',
                        'campaignName' => 'Test campaign',
                    ],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/simplified.csv', 'facebook-csv.txt')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadSimplifiedCsvSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());

        $campaign = $this->getRepository(Campaign::class)->findOneBy([]);
        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertSame('Test campaign', $campaign->getName());
        $this->assertSame(DataProvider::OTHER, $campaign->getDataProvider());
        $this->assertSame('e7593cd7c1d265bd1cbf7d7ae83759b8', $campaign->getExternalId());

        $adSet = $this->getRepository(AdSet::class)->findAll();
        $this->assertCount(1, $adSet);
        $this->assertSame('Test campaign ad set', $adSet[0]->getName());
        $this->assertSame('dad5755afb5b17f677f6e15566b80442', $adSet[0]->getExternalId());

        $ads = $this->getRepository(Ad::class)->findAll();
        $this->assertCount(2, $ads);
        $this->assertSame('Test Ad 1', $ads[0]->getName());
        $this->assertSame('Test Ad 2', $ads[1]->getName());
    }
}
