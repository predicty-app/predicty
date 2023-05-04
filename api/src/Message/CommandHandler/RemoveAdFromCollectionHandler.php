<?php

declare(strict_types=1);

namespace App\Message\CommandHandler;

use App\Entity\AdCollection;
use App\Entity\Permission;
use App\Message\Command\RemoveAdFromCollection;
use App\Repository\AdCollectionRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveAdFromCollectionHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private AdCollectionRepository $adCollectionRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(RemoveAdFromCollection $command): AdCollection
    {
        $adCollection = $this->adCollectionRepository->getById($command->adCollectionId);
        $user = $this->userRepository->getById($command->userId);

        $this->denyAccessUnlessGranted($user, Permission::REMOVE_AD_FROM_AD_COLLECTION, $adCollection);

        $adCollection->removeAdsIds($command->adsIds);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
