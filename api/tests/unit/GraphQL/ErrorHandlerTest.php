<?php

declare(strict_types=1);

namespace App\Tests\Unit\GraphQL;

use App\GraphQL\ErrorHandler;
use Exception;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use RuntimeException;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * @covers \App\GraphQL\ErrorHandler
 */
class ErrorHandlerTest extends TestCase
{
    public function exceptions(): array
    {
        return [
            [
                $this->createHandlerFailedException(new BadCredentialsException('Wrong username')),
                'Invalid credentials.',
                'Wrong username',
                LogLevel::INFO,
            ],
            [
                $this->createHandlerFailedException(
                    $this->createCustomUserMessageAuthenticationException('User was not logged in', 'You are not logged in')
                ),
                'You are not logged in',
                'User was not logged in',
                LogLevel::INFO,
            ],
            [
                $this->createHandlerFailedException(new RuntimeException('Whoopsie')),
                'Internal server error',
                'Whoopsie',
                LogLevel::ERROR,
            ],
            [
                $this->createHandlerFailedException($this->createHandlerFailedException(
                    $this->createCustomUserMessageAuthenticationException('User was not logged in', 'You are not logged in')
                )),
                'You are not logged in',
                'User was not logged in',
                LogLevel::INFO,
            ],
            [
                $this->createValidatorException(),
                'This is a violation',
                'Validation failed: This is a violation',
                LogLevel::INFO,
            ],
            [
                $this->createCustomUserMessageAuthenticationException('User was not logged in', 'You are not logged in'),
                'You are not logged in',
                'User was not logged in',
                LogLevel::INFO,
            ],
            [
                new AuthenticationException('Your password 123456 failed'),
                'An authentication exception occurred.',
                'Your password 123456 failed',
                LogLevel::INFO,
            ],
            [
                new Exception('Some other exception'),
                'Internal server error',
                'Some other exception',
                LogLevel::ERROR,
            ],
        ];
    }

    /**
     * @dataProvider exceptions
     */
    public function test_invoke(Throwable $exception, string $expectedMessage, string $expectedLogMessage, string $expectedLogLevel): void
    {
        $logger = new class() extends AbstractLogger {
            public array $message = ['level' => '', 'message' => '', 'context' => []];

            public function log($level, $message, array $context = []): void
            {
                $this->message = ['level' => (string) $level, 'message' => $message, 'context' => $context];
            }
        };

        $errors = [new Error(previous: $exception)];
        $formatter = fn (Error $error): array => FormattedError::createFromException($error);
        $errorHandler = new ErrorHandler($this->createFakeTranslator(), $logger);
        $result = $errorHandler($errors, $formatter);
        $expected = [['message' => $expectedMessage]];

        $this->assertSame($expected, $result);
        $this->assertSame($expectedLogMessage, $logger->message['message'], 'Logged message does not match');
        $this->assertSame($expectedLogLevel, $logger->message['level'], 'Logged level does not match');
    }

    private function createValidatorException(): ValidationFailedException
    {
        $violations = new ConstraintViolationList([
            new ConstraintViolation('This is a violation', 'This is a violation', [], null, 'property', 'invalid'),
        ]);

        return new ValidationFailedException(new stdClass(), $violations);
    }

    private function createHandlerFailedException(Throwable $exception): HandlerFailedException
    {
        return new HandlerFailedException(Envelope::wrap(new stdClass()), [$exception]);
    }

    private function createCustomUserMessageAuthenticationException(string $message, string $safeMessage = null): CustomUserMessageAuthenticationException
    {
        $exception = new CustomUserMessageAuthenticationException($message);
        $exception->setSafeMessage($safeMessage ?? $message);

        return $exception;
    }

    private function createFakeTranslator(): TranslatorInterface
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturnCallback(fn ($message) => $message);

        return $translator;
    }
}
