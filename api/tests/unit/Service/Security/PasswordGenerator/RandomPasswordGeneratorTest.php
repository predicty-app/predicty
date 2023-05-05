<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\PasswordGenerator;

use App\Service\Security\PasswordGenerator\RandomPasswordGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\Security\PasswordGenerator\RandomPasswordGenerator
 */
class RandomPasswordGeneratorTest extends TestCase
{
    public function test_generate(): void
    {
        $generator = new RandomPasswordGenerator();
        $password = $generator->generate();
        $this->assertIsString($password);
        $this->assertSame(32, strlen($password));
    }
}
