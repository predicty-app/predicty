<?php

declare(strict_types=1);

namespace App\Messenger;

use App\GraphQL\Exception\ClientSafeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class GraphQLMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (ValidationFailedException $exception) {
            $message = (string) $exception->getViolations()->get(0)->getMessage();
            throw new ClientSafeException($message, 0, $exception);
        }
    }
}
