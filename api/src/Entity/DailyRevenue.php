<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\ImportableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['date'])]
class DailyRevenue implements Importable, AccountOwnable, UserOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column]
    private int $revenue;

    #[ORM\Column]
    private int $averageOrderValue;

    #[ORM\Column(length: 3)]
    private string $currency;

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        DateTimeImmutable $date,
        Money $revenue,
        Money $averageOrderValue
    ) {
        assert(
            $revenue->getCurrency() === $averageOrderValue->getCurrency(),
            'Revenue and average order value must be the same currency'
        );

        $this->id = $id;
        $this->userId = $userId;
        $this->date = $date;
        $this->revenue = $revenue->getMinorAmount()->toInt();
        $this->averageOrderValue = $averageOrderValue->getMinorAmount()->toInt();
        $this->currency = $averageOrderValue->getCurrency()->getCurrencyCode();
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->accountId = $accountId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getRevenue(): Money
    {
        return Money::ofMinor($this->revenue, $this->currency);
    }

    public function getAverageOrderValue(): Money
    {
        return Money::ofMinor($this->averageOrderValue, $this->currency);
    }
}
