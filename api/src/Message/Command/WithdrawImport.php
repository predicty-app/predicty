<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Import;
use App\Validator as AssertCustom;

class WithdrawImport
{
    #[AssertCustom\UserExists]
    public int $userId;

    #[AssertCustom\EntityExists(Import::class)]
    public int $importId;

    public function __construct(int $userId, int $importId)
    {
        $this->importId = $importId;
        $this->userId = $userId;
    }
}
