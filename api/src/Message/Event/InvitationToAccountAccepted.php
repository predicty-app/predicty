<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class InvitationToAccountAccepted implements Event
{
    public Ulid $accountInvitationId;
    public Ulid $userId;
    public Ulid $accountId;
    public string $email;

    public function __construct(Ulid $accountInvitationId, Ulid $userId, Ulid $accountId, string $email)
    {
        $this->accountInvitationId = $accountInvitationId;
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->email = $email;
    }
}
