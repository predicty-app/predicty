<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Clock\Clock;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['date'])]
class DailyRevenue
{
    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $date;

    #[ORM\Column]
    private int $revenue;

    #[ORM\Column]
    private int $averageOrderValue;

    #[ORM\Column(length: 3)]
    private string $currency;

    public function __construct(DateTimeImmutable $date, Money $revenue, Money $averageOrderValue)
    {
        $this->date = $date;
        $this->revenue = $revenue->getMinorAmount()->toInt();
        $this->averageOrderValue = $averageOrderValue->getMinorAmount()->toInt();
        $this->currency = $averageOrderValue->getCurrency()->getCurrencyCode();
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
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