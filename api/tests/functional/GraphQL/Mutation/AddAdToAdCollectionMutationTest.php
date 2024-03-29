<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\Account1\AdCollectionFixture;
use App\DataFixtures\Account1\AdFixture;
use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Test\GraphQLTestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\GraphQL\Mutation\AddAdToCollectionMutation
 * @covers \App\MessageHandler\Command\AddAdToCollectionHandler
 */
class AddAdToAdCollectionMutationTest extends GraphQLTestCase
{
    private Ulid $adCollectionId;

    /**
     * @var Ulid[]
     */
    private array $adsIds;
    private string $mutation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AdCollectionFixture::class,
        ]);

        $this->adCollectionId = $this->getReference(AdCollectionFixture::EMPTY_COLLECTION, AdCollection::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixture::AD_1, Ad::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixture::AD_2, Ad::class)->getId();
        $this->adsIds[] = $this->getReference(AdFixture::AD_3, Ad::class)->getId();

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
            'var1' => '00000000000000000000000000',
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
            'var2' => $this->adsIds + [5 => '00000000000000000000000000'],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/AddToAdCollectionFailure2.json');
    }
}
