<?php

declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\User;
use App\Validator as AssertCustom;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class StartConversation
{
    #[AssertCustom\EntityExists(entity: User::class, message: 'User does not exist')]
    public int $userId;

    public DateTimeImmutable $date;

    #[Assert\CssColor(formats: [Assert\CssColor::HEX_LONG, Assert\CssColor::RGB])]
    public string $color;

    public string $comment;

    public function __construct(int $userId, DateTimeImmutable $date, string $comment = '', string $color = '#ffffff')
    {
        $this->userId = $userId;
        $this->color = $color;
        $this->date = $date;
        $this->comment = $comment;
    }
}
