<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AdCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Entity\AdCollection
 */
class AdCollectionTest extends TestCase
{
    public function test_create_new_entity(): void
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VECKZXA5X21VBCMWMFM0T5');

        $adsIds = [
            Ulid::fromString('01H1VEDRTAAY5N06BFYRCMFCFC'),
            Ulid::fromString('01H1VEE64AV1CC0QN3221110T3'),
            Ulid::fromString('01H1VEEBTG92098SB6CE3Q92QW'),
        ];

        $entity = new AdCollection($id, $userId, $accountId, 'Test', $adsIds);
        $this->assertSame('Test', $entity->getName());
        $this->assertSame($id, $entity->getId());
        $this->assertEquals($userId, $entity->getUserId());
        $this->assertEquals($accountId, $entity->getAccountId());
        $this->assertEquals($adsIds, $entity->getAdsIds());
    }

    public function test_add_ads_ids(): void
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VECKZXA5X21VBCMWMFM0T5');

        $adsIds = [
            Ulid::fromString('01H1VEDRTAAY5N06BFYRCMFCFC'),
            Ulid::fromString('01H1VEE64AV1CC0QN3221110T3'),
            Ulid::fromString('01H1VEEBTG92098SB6CE3Q92QW'),
        ];

        $entity = new AdCollection($id, $userId, $accountId, 'Test');

        $entity->addAdsIds($adsIds);
        $this->assertEquals($adsIds, $entity->getAdsIds());
    }

    public function test_remove_ads_ids(): void
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VECKZXA5X21VBCMWMFM0T5');

        $adsIds = [
            Ulid::fromString('01H1VEDRTAAY5N06BFYRCMFCFC'),
            Ulid::fromString('01H1VEE64AV1CC0QN3221110T3'),
            Ulid::fromString('01H1VEEBTG92098SB6CE3Q92QW'),
        ];

        $entity = new AdCollection($id, $userId, $accountId, 'Test', $adsIds);
        $entity->removeAdsIds([
            Ulid::fromString('01H1VEDRTAAY5N06BFYRCMFCFC'),
            Ulid::fromString('01H1VEE64AV1CC0QN3221110T3'),
        ]);

        $this->assertEquals([Ulid::fromString('01H1VEEBTG92098SB6CE3Q92QW')], $entity->getAdsIds());
    }
}
