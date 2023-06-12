<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ConnectGoogleAnalytics;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ConnectGoogleAnalyticsAccountMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'connectGoogleAnalyticsAccount',
            'type' => $type->string(),
            'args' => [
                'ga4id' => [
                    'type' => $type->nonNullString(),
                    'description' => 'GA4 Google tag ID (https://support.google.com/analytics/answer/9539598?hl=en)',
                ],
                'oauthRefreshToken' => $type->nonNullString(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register Google Analytics account. Returns "OK" on success',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new ConnectGoogleAnalytics(
                $this->currentUser->getId(),
                $this->currentUser->getAccountId(),
                $args['oauthRefreshToken'],
                $args['ga4id'],
            )
        );

        return 'OK';
    }
}
