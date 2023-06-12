<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

/**
 * Represents an entity that can be imported and therefore contains the import id.
 */
trait ImportableTrait
{
    #[ORM\Column(type: UlidType::NAME, unique: false, nullable: true)]
    private ?Ulid $importId = null;

    public function setImportId(Ulid $importId): void
    {
        $this->importId = $importId;
    }

    public function getImportId(): ?Ulid
    {
        return $this->importId;
    }
}
