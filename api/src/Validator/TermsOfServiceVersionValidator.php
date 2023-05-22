<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\Predicty\PredictySettings;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TermsOfServiceVersionValidator extends ConstraintValidator
{
    public function __construct(private PredictySettings $predictySettings)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TermsOfServiceVersion) {
            throw new LogicException(\sprintf('You can only pass %s constraint to this validator.', TermsOfServiceVersion::class));
        }

        if ($this->predictySettings->getCurrentTermsOfServiceVersion() !== $value) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
