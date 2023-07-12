<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Message\SyncMessage;
use App\Validator as AssertCustom;
use Symfony\Component\Validator\Constraints as Assert;

class Register implements SyncMessage
{
    #[Assert\Email(message: 'Invalid email')]
    #[Assert\NotBlank(message: 'You must provide an email')]
    public readonly string $email;

    #[AssertCustom\TermsOfServiceVersion]
    public readonly int $acceptedTermsOfServiceVersion;

    public readonly bool $hasAgreedToNewsletter;

    public function __construct(string $email, int $acceptedTermsOfServiceVersion, bool $hasAgreedToNewsletter)
    {
        $this->email = $email;
        $this->acceptedTermsOfServiceVersion = $acceptedTermsOfServiceVersion;
        $this->hasAgreedToNewsletter = $hasAgreedToNewsletter;
    }
}
