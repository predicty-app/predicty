<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\UserExists;
use App\Validator\UserExistsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validator\UserExistsValidator
 * @covers \App\Validator\UserExists
 *
 * @extends ConstraintValidatorTestCase<UserExistsValidator>
 */
class UserExistsValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject&UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);

        parent::setUp();
    }

    public function test_user_exists(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $this->repository->expects($this->once())->method('findById')->with($userId)->willReturn($this->createMock(User::class));

        $constraint = new UserExists();
        $this->validator->validate($userId, $constraint);
        $this->assertNoViolation();
    }

    public function test_user_does_not_exist(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $this->repository->expects($this->once())->method('findById')->with($userId)->willReturn(null);

        $constraint = new UserExists();
        $this->validator->validate($userId, $constraint);
        $this->buildViolation('User not found.')->assertRaised();
    }

    public function test_user_does_not_exist_with_custom_message(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $this->repository->expects($this->once())->method('findById')->with($userId)->willReturn(null);

        $constraint = new UserExists('Custom message');
        $this->validator->validate($userId, $constraint);
        $this->buildViolation('Custom message')->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new UserExistsValidator($this->repository);
    }
}
