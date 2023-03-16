<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Ad;
use App\Entity\AdCollection;
use App\Validator\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class AddToAdCollection
{
    #[Assert\All([new EntityExists(Ad::class)])]
    public readonly array $adsIds;

    #[EntityExists(AdCollection::class)]
    public int $adCollectionId;

    public function __construct(int $adCollectionId, array $adsIds)
    {
        $this->adsIds = $adsIds;
        $this->adCollectionId = $adCollectionId;
    }
}
