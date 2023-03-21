<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @covers \App\GraphQL\Mutation\UploadDataFileMutation
 */
class UploadDataFileMutationTest extends GraphQLTestCase
{
    public function test_upload_facebook_csv_file(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation($file: Upload, $type: FileImportType!, $dataProvider: DataProviderId!) {
                  uploadDataFile(file: $file, type: $type, dataProvider: $dataProvider)
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
                    'variables' => ['file' => null, 'type' => 'FACEBOOK_CSV', 'dataProvider' => 'FACEBOOK_ADS'],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/uploaded-file.txt', 'uploaded-file.txt')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadDataFileSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());
    }

    public function test_upload_allows_sending_campaign_name(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation($file: Upload, $type: FileImportType!, $dataProvider: DataProviderId!) {
                  uploadDataFile(file: $file, type: $type, dataProvider: $dataProvider)
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
                        'dataProvider' => 'FACEBOOK_ADS',
                        'campaignName' => 'Test campaign',
                    ],
                    'operationName' => null,
                ]),
                'map' => json_encode([0 => ['variables.file']]),
            ],
            files: [new UploadedFile(__DIR__.'/data/uploaded-file.txt', 'uploaded-file.txt')],
            server: ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/UploadDataFileSuccess.json');
        $this->assertCount(1, $filesystem->listContents('uploads')->toArray());
    }
}
