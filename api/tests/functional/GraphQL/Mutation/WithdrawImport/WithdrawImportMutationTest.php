<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\WithdrawImport;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\FileImport;
use App\Entity\ImportStatus;
use App\Message\Event\ImportWithdrew;
use App\Test\GraphQLTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\WithdrawImportMutation
 * @covers \App\Message\CommandHandler\WithdrawImportHandler
 */
class WithdrawImportMutationTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_withdraw_succesfully(): void
    {
        $this->loadFixtures([WithdrawImportFixture::class]);
        $import = $this->getReference(WithdrawImportFixture::IMPORT1, FileImport::class);
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation($importId: ID!) {
                  withdrawImport(importId: $importId)
                }
            EOF;

        $this->executeMutation($mutation, ['importId' => (string) $import->getId()]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/WithdrawSuccess.json');

        $this->assertCount(0, $this->getRepository(Campaign::class)->findAll());
        $this->assertCount(0, $this->getRepository(AdSet::class)->findAll());
        $this->assertCount(0, $this->getRepository(Ad::class)->findAll());
        $this->assertCount(0, $this->getRepository(AdStats::class)->findAll());

        $this->assertSame(ImportStatus::WITHDRAWN, $import->getStatus());
    }

    public function test_withdraw_emits_event(): void
    {
        $this->loadFixtures([WithdrawImportFixture::class]);
        $import = $this->getReference(WithdrawImportFixture::IMPORT1, FileImport::class);
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation($importId: ID!) {
                  withdrawImport(importId: $importId)
                }
            EOF;

        $this->executeMutation($mutation, ['importId' => (string) $import->getId()]);
        $this->assertResponseIsSuccessful();

        $this->bus('event.bus')->dispatched()->assertContains(ImportWithdrew::class);
    }
}
