<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\UserRepository;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserExistsValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UserExists) {
            throw new LogicException(\sprintf('You can only pass %s constraint to this validator.', UserExists::class));
        }

        if (null === $value || '' === $value) {
            return;
        }

        $this->userRepository->findById($value) ?? $this->context->buildViolation($constraint->message)->addViolation();
    }
}
