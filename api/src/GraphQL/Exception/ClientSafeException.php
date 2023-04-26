<?php

declare(strict_types=1);

namespace App\GraphQL\Exception;

use GraphQL\Error\ClientAware;
use RuntimeException;
use Throwable;

class ClientSafeException extends RuntimeException implements ClientAware
{
    private string $realMessage;

    public function __construct(string $clientSafeMessage = '', string $realMessage = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($clientSafeMessage, $code, $previous);
        $this->realMessage = $realMessage;
    }

    public function getRealMessage(): string
    {
        return $this->realMessage;
    }

    public function isClientSafe(): bool
    {
        return true;
    }
}
