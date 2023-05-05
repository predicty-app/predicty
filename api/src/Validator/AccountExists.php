<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AccountExists extends Constraint
{
    public string $message = 'Account not found.';

    public function __construct(?string $message = null, array $groups = null, mixed $payload = null)
    {
        $this->message = $message ?? $this->message;
        parent::__construct([], $groups, $payload);
    }
}
