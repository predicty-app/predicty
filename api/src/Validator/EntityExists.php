<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EntityExists extends Constraint
{
    /**
     * @var class-string
     */
    public string $entity;
    public string $message = 'Entity "%entity%" with property "%property%": "%value%" does not exist.';
    public string $property = 'id';

    /**
     * @param class-string $entity
     */
    public function __construct(string $entity, string $property = null, string $message = null, array $groups = null, mixed $payload = null)
    {
        $this->message = $message ?? $this->message;
        $this->property = $property ?? $this->property;
        $this->entity = $entity;

        parent::__construct([], $groups, $payload);
    }
}
