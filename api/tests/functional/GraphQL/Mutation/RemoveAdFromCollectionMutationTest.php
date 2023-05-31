<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\Account1\AdCollectionFixture;
use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Test\GraphQLTestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\GraphQL\Mutation\RemoveAdFromCollectionMutation
 * @covers \App\MessageHandler\Command\RemoveAdFromCollectionHandler
 */
class RemoveAdFromCollectionMutationTest extends GraphQLTestCase
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

        $adCollection = $this->getRepository(AdCollection::class)->find(AdCollectionFixture::AD_COLLECTION_1);
        $this->assertInstanceOf(AdCollection::class, $adCollection, 'AdCollection not found');

        $ad = $this->getRepository(Ad::class)->find($adCollection->getAdsIds()[0]);
        $this->assertInstanceOf(Ad::class, $ad, 'Ad not found');

        $this->adCollectionId = $adCollection->getId();
        $this->adsIds[] = $ad->getId();

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
            'var1' => '00000000000000000000000000',
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
            'var2' => $this->adsIds + [5 => '00000000000000000000000000'],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RemoveAdFromCollectionFailure2.json');
    }
}
