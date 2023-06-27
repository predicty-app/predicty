<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\Repository\AdCollectionRepository;
use App\Repository\AdStatsRepository;
use App\Repository\CampaignRepository;
use App\Repository\ConversationRepository;
use App\Repository\DailyRevenueRepository;
use App\Service\Clock\Clock;
use App\Service\Security\CurrentUser;
use DateInterval;
use GraphQL\Type\Definition\ObjectType;

class DashboardType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        CampaignRepository $campaignRepository,
        AdCollectionRepository $adCollectionRepository,
        DailyRevenueRepository $dailyRevenueRepository,
        ConversationRepository $conversationRepository,
        AdStatsRepository $adStatsRepository,
        CurrentUser $currentUser
    ) {
        parent::__construct([
            'name' => 'Dashboard',
            'fields' => [
                'name' => [
                    'type' => $type->string(),
                    'resolve' => fn () => 'Default Dashboard',
                ],
                'insights' => [
                    'args' => [
                        'from' => [
                            'type' => $type->date(),
                            'defaultValue' => Clock::now()->sub(new DateInterval('P30D'))->format('Y-m-d'),
                            'description' => 'Date from which to start loading data for the dashboard. Defaults to 30 days before today.',
                        ],
                        'to' => [
                            'type' => $type->date(),
                            'defaultValue' => Clock::now(),
                            'description' => 'Date until which to load data for the dashboard.',
                        ],
                    ],
                    'type' => $type->listOf($type->dailyInsights()),
                    'resolve' => fn (mixed $data, array $args) => $adStatsRepository->getDailyAggregatedStats($currentUser->getAccountId(), $args['from'], $args['to']),
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
