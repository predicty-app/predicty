<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\Repository\AdCollectionRepository;
use App\Repository\CampaignRepository;
use App\Repository\ConversationRepository;
use App\Repository\DailyRevenueRepository;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class DashboardType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        CampaignRepository $campaignRepository,
        AdCollectionRepository $adCollectionRepository,
        DailyRevenueRepository $dailyRevenueRepository,
        ConversationRepository $conversationRepository,
        CurrentUser $currentUser
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
                    'resolve' => fn () => $dailyRevenueRepository->findAllByAccountId($currentUser->getAccountId()),
                ],
                'campaigns' => [
                    'type' => $type->listOf($type->campaign()),
                    'resolve' => fn () => $campaignRepository->findAllByAccountId($currentUser->getAccountId()),
                ],
                'collections' => [
                    'type' => $type->listOf($type->adCollection()),
                    'resolve' => fn () => $adCollectionRepository->findAllByAccountId($currentUser->getAccountId()),
                ],
                'conversations' => [
                    'type' => $type->listOf($type->conversation()),
                    'resolve' => fn () => $conversationRepository->findAllByAccountId($currentUser->getAccountId()),
                ],
            ],
        ]);
    }
}
