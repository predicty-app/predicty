<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @internal this class is not meant to be used outside the Security component of the application
 */
class AuthorizationToken extends AbstractToken
{
    public function __construct(UserInterface $user)
    {
        parent::__construct($user->getRoles());
        $this->setUser($user);
    }
}
