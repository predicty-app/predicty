<?php

namespace App\Message;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LogoutHandler
{
    public function __construct(private Security $security)
    {
    }

    public function __invoke(Logout $message): void
    {
        $this->security->logout(false);
    }
}