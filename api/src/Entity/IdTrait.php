<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): int
    {
        if ($this->id === null) {
            throw new RuntimeException(sprintf('Entity was not saved yet, therefore it does not have its id: %s', __CLASS__));
        }

        return $this->id;
    }
}
