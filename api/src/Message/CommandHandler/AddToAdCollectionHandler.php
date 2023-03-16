<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\AdCollection;
use App\Message\Command\AddToAdCollection;
use App\Repository\AdCollectionRepository;
use App\Service\User\CurrentUserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddToAdCollectionHandler
{
    public function __construct(
        private CurrentUserService $currentUserService,
        private AdCollectionRepository $adCollectionRepository,
    ) {
    }

    public function __invoke(AddToAdCollection $message): AdCollection
    {
        $adCollection = $this->adCollectionRepository->findById($message->adCollectionId);
        assert($adCollection !== null);

        $adCollection->addAdsIds($message->adsIds);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
