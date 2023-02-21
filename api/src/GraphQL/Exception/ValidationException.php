<?php

declare(strict_types=1);

namespace App\GraphQL\Exception;

use GraphQL\Error\ClientAware;

class ValidationException extends \RuntimeException implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }
}
