<?php

declare(strict_types=1);

namespace App\Test;

use Coduo\PHPMatcher\Backtrace;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherConstraint;

trait PHPMatcherAssertionsTrait
{
    use KernelBrowserTrait;

    public static function assertResponseMatchesJsonFile(string $filename, string $message = ''): void
    {
        $responseContent = (string) static::getClient()->getResponse()->getContent();
        $dirname = dirname($filename);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0o777, true);
        }

        if (!file_exists($filename)) {
            file_put_contents($filename, $responseContent);
            static::fail(sprintf('Response saved to: "%s", re-run this test to use it.', $filename));
        }

        $pattern = (string) file_get_contents($filename);
        static::assertResponseMatchesPattern($pattern, $message);
    }

    public static function assertResponseMatchesPattern(string $pattern, string $message = ''): void
    {
        $responseContent = (string) static::getClient()->getResponse()->getContent();
        static::assertMatchesPattern($pattern, $responseContent, $message);
    }

    protected static function assertMatchesPattern(string $pattern, string $value, string $message = ''): void
    {
        static::assertThat($value, self::matchesPattern($pattern), $message);
    }

    protected static function matchesPattern(string $pattern, ?Backtrace $backtrace = null): PHPMatcherConstraint
    {
        return new PHPMatcherConstraint($pattern, $backtrace);
    }
}
