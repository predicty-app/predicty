<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\AccountRepository;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AccountExistsValidator extends ConstraintValidator
{
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AccountExists) {
            throw new LogicException(\sprintf('You can only pass %s constraint to this validator.', AccountExists::class));
        }

        if (null === $value || '' === $value) {
            return;
        }

        $this->accountRepository->findById($value) ?? $this->context->buildViolation($constraint->message)->addViolation();
    }
}
