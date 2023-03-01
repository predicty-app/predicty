<?php

declare(strict_types=1);

namespace App\Tests\Unit\GraphQL\Mapper;

use App\Entity\User;
use App\GraphQL\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @covers \App\GraphQL\Mapper\UserMapper
 */
class UserMapperTest extends TestCase
{
    public function test_to_array(): void
    {
        $user = new User('john.doe@example.com');
        $user->setUuid(Uuid::fromString('01866f00-30a1-712b-9e61-968a07c2c004'));

        $expected = [
            'uid' => '01866f00-30a1-712b-9e61-968a07c2c004',
            'email' => 'john.doe@example.com',
            'is_email_verified' => false,
        ];

        $this->assertSame($expected, (new UserMapper())->map($user));
    }
}
