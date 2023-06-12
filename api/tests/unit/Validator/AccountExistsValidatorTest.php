<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Validator\AccountExists;
use App\Validator\AccountExistsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validator\AccountExistsValidator
 * @covers \App\Validator\AccountExists
 *
 * @extends ConstraintValidatorTestCase<AccountExistsValidator>
 */
class AccountExistsValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject&AccountRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AccountRepository::class);

        parent::setUp();
    }

    public function test_account_exists(): void
    {
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $this->repository->expects($this->once())->method('findById')->with($accountId)->willReturn($this->createMock(Account::class));

        $constraint = new AccountExists();
        $this->validator->validate($accountId, $constraint);
        $this->assertNoViolation();
    }

    public function test_account_does_not_exist(): void
    {
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $this->repository->expects($this->once())->method('findById')->with($accountId)->willReturn(null);

        $constraint = new AccountExists();
        $this->validator->validate($accountId, $constraint);
        $this->buildViolation('Account not found.')->assertRaised();
    }

    public function test_account_does_not_exist_with_custom_message(): void
    {
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $this->repository->expects($this->once())->method('findById')->with($accountId)->willReturn(null);

        $constraint = new AccountExists('Custom message');
        $this->validator->validate($accountId, $constraint);
        $this->buildViolation('Custom message')->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new AccountExistsValidator($this->repository);
    }
}
