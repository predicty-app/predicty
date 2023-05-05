<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\EmailUnique;
use App\Validator\EmailUniqueValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validator\EmailUniqueValidator
 * @covers \App\Validator\EmailUnique
 *
 * @extends ConstraintValidatorTestCase<EmailUniqueValidator>
 */
class EmailUniqueValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject&UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);

        parent::setUp();
    }

    public function test_empty_email_is_skipped(): void
    {
        $this->repository->expects($this->never())->method('findByEmail');

        $constraint = new EmailUnique();
        $this->validator->validate('', $constraint);
        $this->assertNoViolation();
    }

    public function test_null_email_is_skipped(): void
    {
        $this->repository->expects($this->never())->method('findByEmail');

        $constraint = new EmailUnique();
        $this->validator->validate(null, $constraint);
        $this->assertNoViolation();
    }

    public function test_email_unique(): void
    {
        $this->repository->expects($this->once())->method('findByEmail')->willReturn(null);

        $constraint = new EmailUnique();
        $this->validator->validate('john.doe@example.com', $constraint);
        $this->assertNoViolation();
    }

    public function test_email_not_unique(): void
    {
        $this->repository->expects($this->once())->method('findByEmail')->willReturn($this->createMock(User::class));

        $constraint = new EmailUnique();
        $this->validator->validate('john.doe@example.com', $constraint);
        $this
            ->buildViolation('The email "%email%" cannot be used anymore. Use different email.')
            ->setParameter('%email%', 'john.doe@example.com')
            ->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new EmailUniqueValidator($this->repository);
    }
}
