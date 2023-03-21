<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\DataProvider;
use App\Entity\DataProviderType;
use App\Message\Command\CreateCustomDataProvider;
use App\Repository\DataProviderRepository;
use App\Service\User\CurrentUserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateCustomDataProviderHandler
{
    public function __construct(
        private CurrentUserService $currentUserService,
        private DataProviderRepository $dataProviderRepository,
    ) {
    }

    public function __invoke(CreateCustomDataProvider $message): DataProvider
    {
        $dataProvider = new DataProvider(DataProviderType::OTHER, $message->name, $message->userId);
        $this->dataProviderRepository->save($dataProvider);

        return $dataProvider;
    }
}
