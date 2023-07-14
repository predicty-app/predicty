<?php

declare(strict_types=1);

namespace App\Message\Event;

use App\Message\Event;
use Symfony\Component\Uid\Ulid;

class InvitationToAccountSent implements Event
{
    public Ulid $accountInvitationId;
    public Ulid $invitingUserId;
    public Ulid $accountId;
    public string $email;

    public function __construct(Ulid $accountInvitationId, Ulid $invitingUserId, Ulid $accountId, string $email)
    {
        $this->accountInvitationId = $accountInvitationId;
        $this->invitingUserId = $invitingUserId;
        $this->accountId = $accountId;
        $this->email = $email;
    }
}
