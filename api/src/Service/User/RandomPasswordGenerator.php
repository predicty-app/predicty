<?php

declare(strict_types=1);

namespace App\Service\User;

class RandomPasswordGenerator
{
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
