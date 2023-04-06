<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use App\Repository\ImportRepository;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class ImportsQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type, ImportRepository $importRepository, CurrentUserService $currentUserService)
    {
        parent::__construct([
            'name' => 'imports',
            'type' => $type->listOf($type->import()),
            'resolve' => fn () => $importRepository->findAllByUserId($currentUserService->getId()),
            'description' => 'List all imports',
        ]);
    }
}
