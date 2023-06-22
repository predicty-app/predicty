<?php

declare(strict_types=1);

namespace App\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use DateTimeImmutable;

class DailyInsights
{
    private DateTimeImmutable $date;
    private int $results;
    private int $clicks;
    private int $impressions;
    private int $leads;
    private Money $amountSpent;
    private Money $revenue;
    private Money $averageOrderValue;

    public function __construct(DateTimeImmutable $date, int $results, int $clicks, int $impressions, int $leads, Money $averageOrderValue, Money $amountSpent, Money $revenue)
    {
        $this->date = $date;
        $this->results = $results;
        $this->amountSpent = $amountSpent;
        $this->revenue = $revenue;
        $this->averageOrderValue = $averageOrderValue;
        $this->clicks = $clicks;
        $this->impressions = $impressions;
        $this->leads = $leads;
    }

    public static function fromScalar(
        string $date,
        int $results,
        int $clicks,
        int $impressions,
        int $leads,
        int $averageOrderValue,
        int $amountSpent,
        int $revenue,
        string $currency
    ): self {
        return new self(
            new DateTimeImmutable($date),
            $results,
            $clicks,
            $impressions,
            $leads,
            Money::ofMinor($averageOrderValue, $currency),
            Money::ofMinor($amountSpent, $currency),
            Money::ofMinor($revenue, $currency)
        );
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getResults(): int
    {
        return $this->results;
    }

    public function getAverageOrderValue(): Money
    {
        return $this->averageOrderValue;
    }

    public function getAmountSpent(): Money
    {
        return $this->amountSpent;
    }

    public function getRevenue(): Money
    {
        return $this->revenue;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getLeads(): int
    {
        return $this->leads;
    }

    public function getCostPerResult(): Money
    {
        if ($this->amountSpent->isZero() || $this->results === 0) {
            return Money::zero($this->amountSpent->getCurrency());
        }

        return $this->amountSpent->dividedBy($this->results, RoundingMode::HALF_UP);
    }

    public function getCostPerClick(): Money
    {
        if ($this->amountSpent->isZero() || $this->clicks === 0) {
            return Money::zero($this->amountSpent->getCurrency());
        }

        return $this->amountSpent->dividedBy($this->clicks, RoundingMode::HALF_UP);
    }

    public function getCostPerMil(): Money
    {
        if ($this->amountSpent->isZero() || $this->impressions === 0) {
            return Money::zero($this->amountSpent->getCurrency());
        }

        return $this->amountSpent->dividedBy($this->impressions, RoundingMode::HALF_UP)->multipliedBy(1000, RoundingMode::HALF_UP);
    }
}
