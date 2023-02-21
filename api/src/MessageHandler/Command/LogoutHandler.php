<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Message\Command\Logout;
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
        if ($this->security->getUser() !== null) {
            $this->security->logout(false);
        }
    }
}
