<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\WithdrawImport;
use App\Message\Event\ImportWithdrew;
use App\Service\DataImport\ImportWithdrawalService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class WithdrawImportHandler
{
    use EmitEventTrait;

    public function __construct(private ImportWithdrawalService $importWithdrawalService)
    {
    }

    public function __invoke(WithdrawImport $command): void
    {
        $this->importWithdrawalService->withdraw($command->importId);
        $this->emit(new ImportWithdrew($command->userId, $command->importId));
    }
}
