<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\AdCollection;
use App\Entity\Permission;
use App\Message\Command\CreateAdCollection;
use App\Repository\AccountRepository;
use App\Repository\AdCollectionRepository;
use App\Repository\UserRepository;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class CreateAdCollectionHandler
{
    use AuthorizationCheckerTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AccountRepository $accountRepository,
        private AdCollectionRepository $adCollectionRepository,
    ) {
    }

    public function __invoke(CreateAdCollection $message): AdCollection
    {
        $user = $this->userRepository->getById($message->userId);
        $account = $this->accountRepository->getById($message->accountId);

        $this->denyAccessUnlessGranted($user, Permission::CREATE_AD_COLLECTION, $account);

        $adCollection = new AdCollection(new Ulid(), $user->getId(), $account->getId(), $message->name);
        $this->adCollectionRepository->save($adCollection);

        return $adCollection;
    }
}
