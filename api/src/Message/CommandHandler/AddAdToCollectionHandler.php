<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\AdCollection;
use App\Message\Command\AddAdToCollection;
use App\Repository\AdCollectionRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddAdToCollectionHandler
{
    public function __construct(
        private AdCollectionRepository $adCollectionRepository,
    ) {
    }

    public function __invoke(AddAdToCollection $message): AdCollection
    {
        $adCollection = $this->adCollectionRepository->findById($message->adCollectionId);
        assert($adCollection !== null);

        $adCollection->addAdsIds($message->adsIds);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
