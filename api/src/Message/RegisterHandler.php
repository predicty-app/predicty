<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegisterHandler
{
    public function __invoke(Register $message): void
    {
    }
}
