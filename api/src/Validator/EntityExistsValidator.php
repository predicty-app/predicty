<?php

declare(strict_types=1);

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new LogicException(\sprintf('You can only pass %s constraint to this validator.', EntityExists::class));
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (empty($constraint->entity)) {
            throw new LogicException(\sprintf('Must set "entity" on "%s" validator', EntityExists::class));
        }

        $entity = $this->entityManager->getRepository($constraint->entity)->findOneBy([
            $constraint->property => $value,
        ]);

        if (null === $entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%entity%', (new ReflectionClass($constraint->entity))->getShortName())
                ->setParameter('%property%', $constraint->property)
                ->setParameter('%value%', (string) $value)
                ->addViolation();
        }
    }
}
