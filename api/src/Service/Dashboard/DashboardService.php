<?php

declare(strict_types=1);

namespace App\Service\Dashboard;

use App\Entity\AdCollection;
use App\Entity\Campaign;
use App\Repository\CampaignRepository;

class DashboardService implements Dashboard
{
    public function __construct(private CampaignRepository $campaignRepository)
    {
    }

    public function getName(): string
    {
        return 'default dashboard';
    }

    /**
     * @return array<Campaign>
     */
    public function getCampaigns(int $limit = 10): array
    {
        return $this->campaignRepository->findAll($limit);
    }

    /**
     * @return array<AdCollection>
     */
    public function getAdCollections(int $limit = 10): array
    {
        return $this->campaignRepository->findAll($limit);
    }
}
