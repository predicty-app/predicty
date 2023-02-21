<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use App\GraphQL\Mapper\UserMapper;
use App\Service\User\CurrentUserService;

class UserResolver
{
    public function __construct(private CurrentUserService $currentUserService, private UserMapper $userMapper)
    {
    }

    public function findCurrentlyLoggedInUser(): array
    {
        $user = $this->currentUserService->getCurrentUser();
        return $this->userMapper->toArray($user);
    }
}
