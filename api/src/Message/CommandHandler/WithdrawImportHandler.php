<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\Permission;
use App\Extension\Messenger\EmitEventTrait;
use App\Message\Command\WithdrawImport;
use App\Message\Event\ImportWithdrew;
use App\Repository\ImportRepository;
use App\Repository\UserRepository;
use App\Service\DataImport\ImportWithdrawalService;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class WithdrawImportHandler
{
    use AuthorizationCheckerTrait;
    use EmitEventTrait;

    public function __construct(
        private ImportWithdrawalService $importWithdrawalService,
        private ImportRepository $importRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(WithdrawImport $command): void
    {
        $user = $this->userRepository->getById($command->userId);
        $import = $this->importRepository->getById($command->importId);

        $this->denyAccessUnlessGranted($user, Permission::WITHDRAW_IMPORT, $import);

        $this->importWithdrawalService->withdraw($command->importId);
        $this->emit(new ImportWithdrew($command->userId, $command->importId));
    }
}
