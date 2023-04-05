<?php

declare(strict_types=1);

namespace App\Extension\Messenger;

use App\GraphQL\Exception\ClientSafeException;
use DomainException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

/**
 * Unpacks exception thrown during message handling into GraphQL compatible type.
 */
class GraphQLMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (ValidationFailedException $exception) {
            $message = (string) $exception->getViolations()->get(0)->getMessage();

            throw new ClientSafeException($message, 0, $exception);
        } catch (HandlerFailedException $exception) {
            $originalException = $exception;
            while ($exception instanceof HandlerFailedException) {
                if ($exception->getPrevious() !== null) {
                    $exception = $exception->getPrevious();
                }
            }

            if ($this->isClientSafe($exception)) {
                $message = $exception->getMessage();
                if ($exception instanceof ValidationFailedException) {
                    $message = (string) $exception->getViolations()->get(0)->getMessage();
                }

                throw new ClientSafeException($message, 0, $exception);
            }

            throw $originalException;
        }
    }

    private function isClientSafe(Throwable $throwable): bool
    {
        return
            $throwable instanceof DomainException ||
            $throwable instanceof ClientSafeException ||
            $throwable instanceof AuthenticationException ||
            $throwable instanceof ValidationFailedException
        ;
    }
}
