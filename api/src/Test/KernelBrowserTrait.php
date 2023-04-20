<?php

declare(strict_types=1);

namespace App\Test;

use Coduo\PHPMatcher\Backtrace;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @see https://github.com/symfony/symfony/issues/40817
 * @see https://github.com/symfony/symfony/blob/6.1/src/Symfony/Bundle/FrameworkBundle/Test/BrowserKitAssertionsTrait.php#L159
 */
trait KernelBrowserTrait
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

    protected static function getClient(): KernelBrowser
    {
        if (self::$client === null) {
            static::createClient();
        }
        assert(self::$client !== null);

        return self::$client;
    }
}
