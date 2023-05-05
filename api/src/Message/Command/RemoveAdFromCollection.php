<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Validator as AssertCustom;
use App\Validator\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class RemoveAdFromCollection
{
    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\AccountExists]
    public int $accountId;

    #[AssertCustom\EntityExists(AdCollection::class)]
    public int $adCollectionId;

    #[Assert\All([new EntityExists(Ad::class)])]
    public readonly array $adsIds;

    public function __construct(int $userId, int $accountId, int $adCollectionId, array $adsIds)
    {
        $this->adsIds = $adsIds;
        $this->adCollectionId = $adCollectionId;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
