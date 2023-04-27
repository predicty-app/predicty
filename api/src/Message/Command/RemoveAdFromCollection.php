<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Entity\User;
use App\Validator as AssertCustom;
use App\Validator\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class RemoveAdFromCollection
{
    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    #[AssertCustom\EntityExists(AdCollection::class)]
    public int $adCollectionId;

    #[Assert\All([new EntityExists(Ad::class)])]
    public readonly array $adsIds;

    public function __construct(int $userId, int $adCollectionId, array $adsIds)
    {
        $this->adsIds = $adsIds;
        $this->adCollectionId = $adCollectionId;
        $this->userId = $userId;
    }
}
