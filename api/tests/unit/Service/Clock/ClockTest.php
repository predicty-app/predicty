<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Clock;

use App\Service\Clock\Clock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Clock\NativeClock;

/**
 * @covers \App\Service\Clock\Clock
 */
class ClockTest extends TestCase
{
    protected function tearDown(): void
    {
        Clock::reset();
    }

    public function test_get(): void
    {
        $clock = Clock::get();
        $this->assertInstanceOf(NativeClock::class, $clock);
    }

    public function test_now(): void
    {
        $clock = new Clock();
        $this->assertInstanceOf(DateTimeImmutable::class, $clock->now());
    }

    public function test_set(): void
    {
        $clock = new Clock();
        $clock->set(new NativeClock());
        $this->assertInstanceOf(NativeClock::class, $clock->get());

        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2021-01-01 00:00:00');
        $this->assertInstanceOf(DateTimeImmutable::class, $now);
        $clock = new Clock(new MockClock($now));
        $this->assertEquals($now, $clock->now());
    }

    public function test_reset(): void
    {
        $clock = new Clock();
        $clock->set(new MockClock());
        $this->assertInstanceOf(MockClock::class, $clock->get());
        $clock->reset();
        $this->assertInstanceOf(NativeClock::class, $clock->get());
    }
}
