<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class InvitationToAccountAccepted implements Event
{
    public Ulid $accountInvitationId;
    public Ulid $invitedUserId;
    public Ulid $accountId;
    public Ulid $invitingUserId;

    public function __construct(Ulid $accountInvitationId, Ulid $invitingUserId, Ulid $invitedUserId, Ulid $accountId)
    {
        $this->accountInvitationId = $accountInvitationId;
        $this->invitedUserId = $invitedUserId;
        $this->invitingUserId = $invitingUserId;
        $this->accountId = $accountId;
    }
}
