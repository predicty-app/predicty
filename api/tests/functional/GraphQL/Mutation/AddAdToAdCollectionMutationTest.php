<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\AdCollectionFixtures;
use App\DataFixtures\AdFixtures;
use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\AddAdToCollectionMutation
 * @covers \App\Message\CommandHandler\AddAdToCollectionHandler
 */
class AddAdToAdCollectionMutationTest extends GraphQLTestCase
{
    private int $adCollectionId;
    private array $adsIds;
    private string $mutation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AdCollectionFixtures::class,
        ]);

        $this->adCollectionId = $this->getReference(AdCollectionFixtures::EMPTY_COLLECTION, AdCollection::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixtures::AD_1, Ad::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixtures::AD_2, Ad::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixtures::AD_3, Ad::class)->getId();

        $this->mutation = <<<'EOF'
            mutation($var1: ID!, $var2: [ID]!) {
                addToAdCollection(adCollectionId: $var1, adsIds: $var2) {
                id
                ads {
                  id
                  name
                }
              }
            }
            EOF;
    }

    public function test_add_to_ad_collection(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => $this->adCollectionId,
            'var2' => $this->adsIds,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/AddToAdCollection.json');
    }

    public function test_add_to_ad_collection_fails_with_invalid_ad_collection_id(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => 999999,
            'var2' => $this->adsIds,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/AddToAdCollectionFailure1.json');
    }

    public function test_add_to_ad_collection_fails_with_invalid_ad_id(): void
    {
        $this->authenticate();
        $this->executeMutation($this->mutation, [
            'var1' => $this->adCollectionId,
            'var2' => $this->adsIds + [5 => 99999],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/AddToAdCollectionFailure2.json');
    }
}
