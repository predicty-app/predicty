<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use App\Repository\DataProviderRepository;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class DataProvidersQuery extends FieldDefinition
{
    public function __construct(
        private TypeRegistry $type,
        private CurrentUserService $currentUserService,
        private DataProviderRepository $dataProviderRepository
    ) {
        parent::__construct([
            'name' => 'dataProviders',
            'type' => $type->listOf($type->dataProvider()),
            'resolve' => fn () => $this->resolve(),
            'description' => 'Get all supported data providers',
        ]);
    }

    public function resolve(): array
    {
        if ($this->currentUserService->hasLoggedInUser()) {
            $providers = $this->dataProviderRepository->findAllForUser($this->currentUserService->getId());
        } else {
            $providers = $this->dataProviderRepository->findAllForEverybody();
        }

        return $providers;
    }
}
