<?php

declare(strict_types=1);

namespace App\Entity;

enum Role: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case IS_AUTHENTICATED = 'IS_AUTHENTICATED';
    case IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';
}
