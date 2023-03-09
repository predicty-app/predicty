<?php

declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DefaultFieldResolver
{
    private PropertyAccessorInterface $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();
    }

    public function __invoke(mixed $objectLikeValue, array $args, mixed $contextValue, ResolveInfo $info): mixed
    {
        $property = null;
        if ($this->isObjectOrArrayLike($objectLikeValue)) {
            $property = $this->accessor->getValue($objectLikeValue, $info->fieldName);
        }

        return $property instanceof \Closure
            ? $property($objectLikeValue, $args, $contextValue, $info)
            : $property;
    }

    private function isObjectOrArrayLike(mixed $objectLikeValue): bool
    {
        return is_array($objectLikeValue) || is_object($objectLikeValue);
    }
}
