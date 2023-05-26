<?php

declare(strict_types=1);

namespace App\Controller\OAuth;

use App\Service\Facebook\FacebookOAuth;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookOAuthController extends AbstractController
{
    public function __construct(private CacheInterface $cache, private FacebookOAuth $facebookOAuth)
    {
    }

    #[Route('/internal/facebook/oauth', name: 'facebook_oauth_callback')]
    public function __invoke(Request $request): Response
    {
        $authCode = $request->query->get('code');
        $givenRequestId = (string) $request->query->get('state');

        if ($givenRequestId === '' || !$this->cache->has($givenRequestId)) {
            return new Response('Bad request', 400);
        }

        if ($authCode === null) {
            return new Response('Bad request', 400);
        }

        $accessToken = $this->facebookOAuth->getAccessToken($authCode);
        $accessToken = $this->facebookOAuth->getLongLivedAccessToken($accessToken);
        $this->cache->set($givenRequestId, $accessToken);

        return new Response('OK');
    }
}
