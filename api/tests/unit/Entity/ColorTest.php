<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Color;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Color
 */
class ColorTest extends TestCase
{
    public function test_to_hex_string(): void
    {
        $this->assertSame('#ffffff', Color::fromString('#ffffff')->toHexString());
        $this->assertSame('#ffffff', Color::fromString('rgb(255,255,255)')->toHexString());
    }

    public function test_to_rgb_string(): void
    {
        $this->assertSame('rgb(255,255,255)', Color::fromString('#ffffff')->toRGBString());
        $this->assertSame('rgb(255,255,255)', Color::fromString('rgb(255,255,255)')->toRGBString());
    }

    public function test_from_string(): void
    {
        $this->assertSame('#c812aa', Color::fromString('rgb(200, 18, 170)')->toHexString());
    }
}
