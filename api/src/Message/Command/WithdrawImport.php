<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Import;
use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class WithdrawImport
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\EntityExists(Import::class)]
    public Ulid $importId;

    public function __construct(Ulid $userId, Ulid $importId)
    {
        $this->importId = $importId;
        $this->userId = $userId;
    }
}
