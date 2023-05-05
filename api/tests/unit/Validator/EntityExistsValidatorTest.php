<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\User;
use App\Validator\EntityExists;
use App\Validator\EntityExistsValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validator\EntityExistsValidator
 * @covers \App\Validator\EntityExists
 *
 * @extends ConstraintValidatorTestCase<EntityExistsValidator>
 */
class EntityExistsValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject&EntityManagerInterface $em;
    /* @phpstan-ignore-next-line */
    private MockObject&EntityRepository $repository;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);

        parent::setUp();
    }

    public function test_entity_exists(): void
    {
        $this->em->expects($this->once())->method('getRepository')->with(User::class)->willReturn($this->repository);
        $this->repository->method('findOneBy')->willReturn($this->createMock(User::class));

        $constraint = new EntityExists(User::class, 'email');
        $this->validator->validate('john.doe@example.com', $constraint);
        $this->assertNoViolation();
    }

    public function test_entity_does_not_exist(): void
    {
        $this->em->expects($this->once())->method('getRepository')->with(User::class)->willReturn($this->repository);
        $this->repository->method('findOneBy')->willReturn(null);

        $constraint = new EntityExists(User::class, 'email');
        $this->validator->validate('john.doe@example.com', $constraint);

        $this
            ->buildViolation('Entity "%entity%" with property "%property%": "%value%" does not exist.')
            ->setParameter('%entity%', 'User')
            ->setParameter('%property%', 'email')
            ->setParameter('%value%', 'john.doe@example.com')
            ->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new EntityExistsValidator($this->em);
    }
}
