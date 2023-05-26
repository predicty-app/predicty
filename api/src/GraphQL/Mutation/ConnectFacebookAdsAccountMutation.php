<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ConnectFacebookAds;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ConnectFacebookAdsAccountMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'connectGoogleAdsAccount',
            'type' => $type->string(),
            'args' => [
                'adAccountId' => [
                    'type' => $type->nonNullString(),
                    'description' => 'Facebook Ad Account ID (https://www.facebook.com/business/help/1492627900875762)',
                ],
                'accessToken' => [
                    'type' => $type->nonNullString(),
                    'description' => 'Facebook User Access Token (https://developers.facebook.com/docs/facebook-login/guides/access-tokens)',
                ],
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register Facebook Ads account. Returns "OK" on success',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new ConnectFacebookAds(
                $this->currentUser->getId(),
                $this->currentUser->getAccountId(),
                $args['accessToken'],
                $args['adAccountId'],
            )
        );

        return 'OK';
    }
}
