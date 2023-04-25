<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\Repository\AdCollectionRepository;
use App\Repository\CampaignRepository;
use App\Repository\ConversationRepository;
use App\Repository\DailyRevenueRepository;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\ObjectType;

class DashboardType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        CampaignRepository $campaignRepository,
        AdCollectionRepository $adCollectionRepository,
        DailyRevenueRepository $dailyRevenueRepository,
        ConversationRepository $conversationRepository,
        private CurrentUserService $currentUserService
    ) {
        parent::__construct([
            'name' => 'Dashboard',
            'fields' => [
                'name' => [
                    'type' => $type->string(),
                    'resolve' => fn () => 'Default Dashboard',
                ],
                'dailyRevenue' => [
                    'type' => $type->listOf($type->dailyRevenue()),
                    'resolve' => fn () => $dailyRevenueRepository->findAllByUserId($this->currentUserService->getId()),
                ],
                'campaigns' => [
                    'type' => $type->listOf($type->campaign()),
                    'resolve' => fn () => $campaignRepository->findAllByUserId($this->currentUserService->getId()),
                ],
                'collections' => [
                    'type' => $type->listOf($type->adCollection()),
                    'resolve' => fn () => $adCollectionRepository->findAllByUserId($this->currentUserService->getId()),
                ],
                'conversations' => [
                    'type' => $type->listOf($type->conversation()),
                    'resolve' => fn () => $conversationRepository->findAllByUserId($this->currentUserService->getId()),
                ],
            ],
        ]);
    }
}
