<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents an entity that can be imported and therefore contains the import id.
 */
trait ImportableTrait
{
    #[ORM\Column(nullable: true)]
    private ?int $importId = null;

    public function setImportId(int $importId): void
    {
        $this->importId = $importId;
    }

    public function getImportId(): ?int
    {
        return $this->importId;
    }
}
