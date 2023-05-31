<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Ulid;

interface Importable
{
    public function setImportId(Ulid $importId): void;

    public function getImportId(): ?Ulid;
}
