<?php

declare(strict_types=1);

namespace App\Service\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @see https://github.com/symfony/symfony/pull/48642
 */
class Clock
{
    private static ?ClockInterface $clock = null;

    public function __construct(ClockInterface $clock)
    {
        self::$clock = $clock;
    }

    public static function now(): DateTimeImmutable
    {
        return self::get()->now();
    }

    public static function set(ClockInterface $clock): void
    {
        self::$clock = $clock;
    }

    public static function get(): ClockInterface
    {
        if (self::$clock === null) {
            self::$clock = new NativeClock();
        }

        return self::$clock;
    }
}
