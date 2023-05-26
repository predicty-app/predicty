<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use ArrayObject;
use FacebookAds\Http\Adapter\AbstractAdapter as AbstractFacebookHttpAdapter;
use FacebookAds\Http\Adapter\AdapterInterface as FacebookHttpAdapterInterface;
use FacebookAds\Http\Client as FacebookClient;
use FacebookAds\Http\Headers;
use FacebookAds\Http\RequestInterface as FacebookRequestInterface;
use FacebookAds\Http\Response;
use FacebookAds\Http\ResponseInterface as FacebookResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyFacebookBridgeAdapter extends AbstractFacebookHttpAdapter implements FacebookHttpAdapterInterface
{
    /**
     * @var ArrayObject<string, mixed>
     */
    protected ArrayObject $opts;

    public function __construct(FacebookClient $client, private ?HttpClientInterface $httpClient = null)
    {
        parent::__construct($client);
    }

    /**
     * @return ArrayObject<string, mixed>
     */
    public function getOpts(): ArrayObject
    {
        return $this->opts;
    }

    /**
     * @param ArrayObject<string, mixed> $opts
     */
    public function setOpts(ArrayObject $opts): void
    {
        $this->opts = $opts;
    }

    public function sendRequest(FacebookRequestInterface $request): FacebookResponseInterface
    {
        assert($this->httpClient !== null, 'An instance of HttpClientInterface is required');

        $body = $request->getBodyParams()->export();
        $response = $this->httpClient->request($request->getMethod(), $request->getUrl(), [
            'body' => $body,
            'headers' => $request->getHeaders()->getArrayCopy(),
        ]);

        $facebookResponse = new Response();
        $headers = new Headers($response->getHeaders());
        $facebookResponse->setBody($response->getContent());
        $facebookResponse->setHeaders($headers);

        return $facebookResponse;
    }
}
