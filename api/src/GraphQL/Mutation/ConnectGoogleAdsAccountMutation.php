<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ConnectGoogleAds;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ConnectGoogleAdsAccountMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'connectGoogleAdsAccount',
            'type' => $type->string(),
            'args' => [
                'customerId' => [
                    'type' => $type->nonNullString(),
                    'description' => 'Google Ads customer ID (https://support.google.com/google-ads/answer/1704344?hl=en)',
                ],
                'oauthRefreshToken' => $type->nonNullString(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register Google Ads account. Returns "OK" on success',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new ConnectGoogleAds(
                $this->currentUser->getId(),
                $this->currentUser->getAccountId(),
                $args['oauthRefreshToken'],
                $args['customerId'],
            )
        );

        return 'OK';
    }
}
