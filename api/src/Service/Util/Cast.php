<?php

declare(strict_types=1);

namespace App\Service\Util;

use RuntimeException;

/**
 * Experimental helper class, that intends to narrow down the type.
 */
class Cast
{
    /**
     * @template T of object
     *
     * @param class-string<T> $expectedType
     *
     * @return T
     */
    public static function to(object $value, string $expectedType): object
    {
        assert($value instanceof $expectedType, new RuntimeException(sprintf(
            'Cannot cast "%s" to "%s"',
            get_debug_type($value),
            $expectedType
        )));

        return $value;
    }
}
