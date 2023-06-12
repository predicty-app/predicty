<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Validator as AssertCustom;
use App\Validator\EntityExists;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class AddAdToCollection
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    /**
     * @var array<Ulid>
     */
    #[Assert\All([new EntityExists(Ad::class)])]
    public readonly array $adsIds;

    #[EntityExists(AdCollection::class)]
    public Ulid $adCollectionId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public function __construct(Ulid $userId, Ulid $accountId, Ulid $adCollectionId, array $adsIds)
    {
        $this->adsIds = $adsIds;
        $this->adCollectionId = $adCollectionId;
        $this->userId = $userId;
        $this->accountId = $accountId;
    }
}
