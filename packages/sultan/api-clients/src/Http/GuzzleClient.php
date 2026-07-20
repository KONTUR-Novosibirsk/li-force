<?php

namespace Sultan\ApiClients\Http;

use GuzzleHttp\Client;
use Sultan\ApiClients\Response;

final readonly class GuzzleClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(
        private string $url,
        private int $timeout = 10
    ) {
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function request(string $method, string $url, array $options = []): Response
    {
        $res = $this->client->request($method, $url, $options);

        return new Response($res->getStatusCode(), $res->getHeaders(), (string) $res->getBody());
    }
}
