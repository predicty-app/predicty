<?php

declare(strict_types=1);

namespace App\Service\Dashboard;

use App\Entity\Campaign;

interface Dashboard
{
    /**
     * @return array<Campaign>
     */
    public function getCampaigns(int $limit): array;

    public function getName(): string;
}
