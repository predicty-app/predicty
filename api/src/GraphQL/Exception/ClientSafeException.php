<?php

declare(strict_types=1);

namespace App\GraphQL\Exception;

use GraphQL\Error\ClientAware;
use RuntimeException;

class ClientSafeException extends RuntimeException implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }
}
