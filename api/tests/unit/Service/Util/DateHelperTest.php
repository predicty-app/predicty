<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Util;

use App\Service\Util\DateHelper;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \App\Service\Util\DateHelper
 */
class DateHelperTest extends TestCase
{
    public function test_from_string(): void
    {
        $date = DateHelper::fromString('2021-01-01');
        $this->assertEquals('2021-01-01 00:00:00', $date->format('Y-m-d H:i:s'));

        $date = DateHelper::fromString('2021-01-01 12:00:00', 'Y-m-d H:i:s');
        $this->assertEquals('2021-01-01 12:00:00', $date->format('Y-m-d H:i:s'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid date format given: "2021-01-01" (expecting Y-m-d H:i:s)');
        DateHelper::fromString('2021-01-01', 'Y-m-d H:i:s');
    }
}
