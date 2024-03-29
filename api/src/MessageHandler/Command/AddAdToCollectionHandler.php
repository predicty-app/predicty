<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\AdCollection;
use App\Entity\Permission;
use App\Message\Command\AddAdToCollection;
use App\Repository\AdCollectionRepository;
use App\Repository\UserWithAccountRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddAdToCollectionHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private AdCollectionRepository $adCollectionRepository,
        private UserWithAccountRepository $userWithAccountRepository,
    ) {
    }

    public function __invoke(AddAdToCollection $message): AdCollection
    {
        $user = $this->userWithAccountRepository->get($message->userId, $message->accountId);
        $adCollection = $this->adCollectionRepository->getById($message->adCollectionId);

        $this->denyAccessUnlessGranted($user, Permission::ADD_AD_TO_AD_COLLECTION, $adCollection);

        $adCollection->addAdsIds($message->adsIds);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
