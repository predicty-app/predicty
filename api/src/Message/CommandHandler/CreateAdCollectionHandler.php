<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\AdCollection;
use App\Message\Command\CreateAdCollection;
use App\Repository\AdCollectionRepository;
use App\Service\User\CurrentUserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateAdCollectionHandler
{
    public function __construct(
        private CurrentUserService $currentUserService,
        private AdCollectionRepository $adCollectionRepository,
    ) {
    }

    public function __invoke(CreateAdCollection $message): AdCollection
    {
        $adCollection = new AdCollection($this->currentUserService->getId(), $message->name);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
