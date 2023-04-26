<?php

declare(strict_types=1);

namespace App\Entity;

use Spatie\Color\Color as SpatieColor;
use Spatie\Color\Factory;

class Color
{
    private function __construct(private SpatieColor $color)
    {
    }

    public function toHexString(): string
    {
        return (string) $this->color->toHex();
    }

    public function toRGBString(): string
    {
        return (string) $this->color->toRgb();
    }

    public static function fromString(string $color): self
    {
        return new self(Factory::fromString($color));
    }
}
