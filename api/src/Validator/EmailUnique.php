<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EmailUnique extends Constraint
{
    public string $message = 'The email "{{ string }}" cannot be used anymore. Use different email.';

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
