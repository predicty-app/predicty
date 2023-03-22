<?php

declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAdCollection
{
    #[Assert\NotBlank(message: 'You must provide a collection name')]
    public readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
