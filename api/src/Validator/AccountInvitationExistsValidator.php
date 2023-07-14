<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\AccountInvitationRepository;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AccountInvitationExistsValidator extends ConstraintValidator
{
    public function __construct(private AccountInvitationRepository $accountInvitationRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AccountInvitationExists) {
            throw new LogicException(\sprintf('You can only pass %s constraint to this validator.', AccountInvitationExists::class));
        }

        if (null === $value || '' === $value) {
            return;
        }

        $this->accountInvitationRepository->finByIdAndSkipExpired($value) ?? $this->context->buildViolation($constraint->message)->addViolation();
    }
}
