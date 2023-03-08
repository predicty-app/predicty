<?php

declare(strict_types=1);

namespace App\Test;

use Coduo\PHPMatcher\Backtrace;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherConstraint;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class GraphQLTestCase extends WebTestCase
{
    private static ?KernelBrowser $client = null;
    private static ?Backtrace $backtrace = null;

    protected function tearDown(): void
    {
        self::$client = null;
        parent::tearDown();
    }

    public static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        self::$client = parent::createClient($options, $server);

        return self::$client;
    }

    public static function executeQuery(string $query, array $variables = [], string $operationName = null): void
    {
        $parameters = [
            'operationName' => $operationName,
            'query' => $query,
            'variables' => $variables,
        ];

        self::getClient()->jsonRequest(
            method: 'POST',
            uri: '/graphql',
            parameters: $parameters
        );
    }

    public static function executeMutation(string $query, array $variables = [], string $operationName = null): void
    {
        self::executeQuery($query, $variables, $operationName);
    }

    public static function assertResponseMatchesPattern(string $pattern): void
    {
    }

    public static function assertResponseMatchesJsonFile(string $filename, string $message = ''): void
    {
        $responseContent = (string) self::getClient()->getResponse()->getContent();

        $dirname = dirname($filename);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0o777, true);
        }

        if (!file_exists($filename)) {
            file_put_contents($filename, $responseContent);
            static::fail(sprintf('Response saved to: "%s", re-run this test to use it.', $filename));
        }

        $pattern = (string) file_get_contents($filename);
        static::assertMatchesPattern($pattern, $responseContent, $message);
    }

    protected static function setBacktrace(Backtrace $backtrace): void
    {
        self::$backtrace = $backtrace;
    }

    protected static function assertMatchesPattern(string $pattern, string $value, string $message = ''): void
    {
        TestCase::assertThat($value, self::matchesPattern($pattern, self::$backtrace), $message);
    }

    protected static function matchesPattern(string $pattern, ?Backtrace $backtrace = null): PHPMatcherConstraint
    {
        return new PHPMatcherConstraint($pattern, $backtrace);
    }

    private static function getClient(): KernelBrowser
    {
        if (self::$client === null) {
            static::createClient();
        }
        assert(self::$client !== null);

        return self::$client;
    }
}
