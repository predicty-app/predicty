<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\WithdrawImport;

use App\Entity\Import;
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
        $this->authenticate();

        $import = $this->getRepository(Import::class)->findOneBy(['status' => ImportStatus::COMPLETE]);
        assert($import instanceof Import);

        $mutation = <<<'EOF'
                mutation($importId: ID!) {
                  withdrawImport(importId: $importId)
                }
            EOF;

        $this->executeMutation($mutation, ['importId' => $import->getId()]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/WithdrawSuccess.json');

        $this->assertSame(ImportStatus::WITHDRAWN, $import->getStatus());
    }

    public function test_withdraw_emits_event(): void
    {
        $this->authenticate();
        $import = $this->getRepository(Import::class)->findOneBy(['status' => ImportStatus::COMPLETE]);
        assert($import instanceof Import);

        $mutation = <<<'EOF'
                mutation($importId: ID!) {
                  withdrawImport(importId: $importId)
                }
            EOF;

        $this->executeMutation($mutation, ['importId' => $import->getId()]);
        $this->assertResponseIsSuccessful();

        $this->bus('event.bus')->dispatched()->assertContains(ImportWithdrew::class);
    }
}
