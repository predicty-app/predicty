<?php

declare(strict_types=1);

namespace App\Entity;

interface Importable
{
    public function setImportId(int $importId): void;

    public function getImportId(): ?int;
}
