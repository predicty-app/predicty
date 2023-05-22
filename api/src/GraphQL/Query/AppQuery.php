<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use App\Service\Predicty\PredictySettings;
use GraphQL\Type\Definition\FieldDefinition;

class AppQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type, PredictySettings $predictySettings)
    {
        parent::__construct([
            'name' => 'app',
            'type' => $type->app(),
            'resolve' => fn () => $predictySettings,
            'description' => 'Get information about current app',
        ]);
    }
}
