<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Validator as AssertCustom;
use DateTimeImmutable;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

class StartConversation
{
    #[AssertCustom\UserExists]
    public Ulid $userId;

    #[AssertCustom\AccountExists]
    public Ulid $accountId;

    public DateTimeImmutable $date;

    #[Assert\CssColor(formats: [Assert\CssColor::HEX_LONG, Assert\CssColor::RGB])]
    public string $color;

    public string $comment;

    public function __construct(Ulid $userId, Ulid $accountId, DateTimeImmutable $date, string $comment = '', string $color = '#ffffff')
    {
        $this->userId = $userId;
        $this->color = $color;
        $this->date = $date;
        $this->comment = $comment;
        $this->accountId = $accountId;
    }
}
