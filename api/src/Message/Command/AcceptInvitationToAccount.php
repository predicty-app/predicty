<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use Symfony\Component\Uid\Ulid;

class AcceptInvitationToAccount
{
    #[AssertCustom\AccountInvitationExists]
    public Ulid $invitationId;

    #[AssertCustom\TermsOfServiceVersion]
    public readonly int $acceptedTermsOfServiceVersion;

    public readonly bool $hasAgreedToNewsletter;

    public function __construct(Ulid $invitationId, int $acceptedTermsOfServiceVersion, bool $hasAgreedToNewsletter)
    {
        $this->invitationId = $invitationId;
        $this->acceptedTermsOfServiceVersion = $acceptedTermsOfServiceVersion;
        $this->hasAgreedToNewsletter = $hasAgreedToNewsletter;
    }
}
