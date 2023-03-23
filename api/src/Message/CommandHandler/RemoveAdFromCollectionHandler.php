<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\AdCollection;
use App\Message\Command\RemoveAdFromCollection;
use App\Repository\AdCollectionRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveAdFromCollectionHandler
{
    public function __construct(
        private AdCollectionRepository $adCollectionRepository,
    ) {
    }

    public function __invoke(RemoveAdFromCollection $command): AdCollection
    {
        $adCollection = $this->adCollectionRepository->findById($command->adCollectionId);
        assert($adCollection !== null);

        $adCollection->removeAdsIds($command->adsIds);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
