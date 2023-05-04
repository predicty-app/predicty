<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\AdCollectionFixture;
use App\DataFixtures\AdFixture;
use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\RemoveAdFromCollectionMutation
 * @covers \App\MessageHandler\Command\RemoveAdFromCollectionHandler
 */
class RemoveAdFromCollectionMutationTest extends GraphQLTestCase
{
    private int $adCollectionId;
    private array $adsIds;
    private string $mutation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AdCollectionFixture::class,
        ]);

        $this->adCollectionId = $this->getReference(AdCollectionFixture::AD_COLLECTION_1, AdCollection::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixture::AD_1, Ad::class)->getId();

        $this->mutation = <<<'EOF'
            mutation($var1: ID!, $var2: [ID]!) {
                removeAdFromCollection(adCollectionId: $var1, adsIds: $var2) {
                id
                ads {
                  id
                  name
                }
              }
            }
            EOF;
    }

    public function test_remove_ad_from_collection(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => $this->adCollectionId,
            'var2' => $this->adsIds,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RemoveAdFromCollection.json');
    }

    public function test_remove_ad_from_collection_fails_with_invalid_ad_collection_id(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => 999999,
            'var2' => $this->adsIds,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RemoveAdFromCollectionFailure1.json');
    }

    public function test_remove_ad_from_collection_fails_with_invalid_ad_id(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => $this->adCollectionId,
            'var2' => $this->adsIds + [5 => 99999],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RemoveAdFromCollectionFailure2.json');
    }
}
