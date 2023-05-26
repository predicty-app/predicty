<?php

declare(strict_types=1);

namespace App\Controller\OAuth;

use App\Service\Google\GoogleOAuth;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleOAuthController extends AbstractController
{
    public function __construct(private CacheInterface $cache, private GoogleOAuth $googleOAuth)
    {
    }

    #[Route('/internal/google/oauth', name: 'app_google_oauth')]
    public function __invoke(Request $request): Response
    {
        $redirectUrl = (string) strtok($request->getUri(), '?');
        $givenRequestId = (string) $request->query->get('state');

        if ($givenRequestId === '' || !$this->cache->has($givenRequestId)) {
            return new Response('Bad request', 400);
        }

        $this->googleOAuth->setRedirectUrl($redirectUrl);
        $authCode = $request->query->get('code');

        if ($authCode === null) {
            return new Response('Bad request', 400);
        }

        $refreshToken = $this->googleOAuth->fetchRefreshToken($authCode);
        $this->cache->set($givenRequestId, $refreshToken);

        return new Response('OK');
    }
}
