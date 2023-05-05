<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Entity\Ad;
use App\Entity\Campaign;
use App\Test\GraphQLTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @covers \App\GraphQL\Mutation\UploadDataFileMutation
 */
class UploadGoogleAdsCsvTest extends GraphQLTestCase
{
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

        $campaign = $this->getRepository(Campaign::class)->findOneBy(['name' => 'Search-1']);
        $this->assertNotNull($campaign);

        $ads = $this->getRepository(Ad::class)->findBy(['campaignId' => $campaign->getId()]);
        $this->assertCount(1, $ads);
        $this->assertSame('621611194472', $ads[0]->getExternalId());
        $this->assertSame('Ad no. 621611194472', $ads[0]->getName());
    }
}
