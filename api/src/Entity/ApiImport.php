<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['connectedAccountId'])]
class ApiImport extends Import
{
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $connectedAccountId;

    public function __construct(Ulid $id, Ulid $userId, Ulid $accountId, Ulid $connectedAccountId, DataProvider $dataProvider)
    {
        $this->connectedAccountId = $connectedAccountId;
        parent::__construct($id, $userId, $accountId, $dataProvider);
    }

    public function getConnectedAccountId(): Ulid
    {
        return $this->connectedAccountId;
    }
}
