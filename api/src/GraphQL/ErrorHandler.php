<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Exception\ClientSafeException;
use GraphQL\Error\Error;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * Wraps symfony exceptions into GraphQL-understandable errors.
 */
class ErrorHandler
{
    public function __construct(
        private TranslatorInterface $translator,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param array<Error> $errors
     */
    public function __invoke(array $errors, callable $formatter): array
    {
        $checkedErrors = [];
        foreach ($errors as $error) {
            $error = $this->normalize($error);
            $previous = $error->getPrevious();
            $logMessage = $previous?->getMessage() ?? $error->getMessage();

            if ($error->isClientSafe()) {
                if ($previous instanceof ClientSafeException) {
                    $logMessage = $previous->getRealMessage();
                }

                $this->logger->info($logMessage, ['exception' => $error]);
            } else {
                $this->logger->error($logMessage, ['exception' => $error]);
            }

            $checkedErrors[] = $error;
        }

        return array_map($formatter, $checkedErrors);
    }

    private function normalize(Error $error): Error
    {
        $previous = $error->getPrevious();

        if ($previous === null) {
            return $error;
        }

        if ($previous instanceof HandlerFailedException) {
            $previous = $this->unpackHandlerFailedException($previous);
        }

        if ($previous instanceof AuthenticationException) {
            $previous = $this->wrapAuthenticationException($previous);
        }

        if ($previous instanceof AccessDeniedException) {
            $previous = $this->wrapAccessDeniedException($previous);
        }

        if ($previous instanceof ValidationFailedException) {
            $previous = $this->wrapValidationFailedException($previous);
        }

        return new Error(
            message: $previous->getMessage(),
            nodes: $error->getNodes(),
            source: $error->getSource(),
            positions: $error->getPositions(),
            path: $error->getPath(),
            previous: $previous
        );
    }

    private function wrapAccessDeniedException(AccessDeniedException $exception): ClientSafeException
    {
        return $this->createClientSafeException('You are not allowed to do that', $exception);
    }

    private function wrapAuthenticationException(AuthenticationException $exception): ClientSafeException
    {
        $clientSafeMessage = $this->translator->trans($exception->getMessageKey(), $exception->getMessageData());

        return $this->createClientSafeException($clientSafeMessage, $exception->getMessage(), $exception);
    }

    private function wrapValidationFailedException(ValidationFailedException $exception): ClientSafeException
    {
        $violation = $exception->getViolations()->get(0);
        $message = $this->translator->trans($violation->getMessageTemplate(), $violation->getParameters());

        return $this->createClientSafeException($message, sprintf('Validation failed: %s', $message), $exception);
    }

    private function unpackHandlerFailedException(HandlerFailedException $exception): Throwable
    {
        $previous = $exception->getPrevious() ?? $exception;
        if ($previous instanceof HandlerFailedException) {
            return $this->unpackHandlerFailedException($previous);
        }

        return $previous;
    }

    private function createClientSafeException(string $clientSafeMessage, string $realMessage, Throwable $exception): ClientSafeException
    {
        return new ClientSafeException(
            clientSafeMessage: $clientSafeMessage,
            realMessage: $realMessage,
            previous: $exception
        );
    }
}
