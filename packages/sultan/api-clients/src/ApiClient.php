<?php

namespace Sultan\ApiClients;

use Sultan\ApiClients\Auth\AuthStrategyInterface;
use Sultan\ApiClients\Http\HttpClientInterface;
use Throwable;

readonly class ApiClient
{
    public function __construct(
        private HttpClientInterface $http,
        private ?AuthStrategyInterface $auth = null
    ) {
    }

    public function request(string $method, string $url, array $options = []): Response
    {
        try {
            if ($this->auth) {
                $options = $this->auth->apply($options);
            }

            return $this->http->request($method, $url, $options);
        } catch (Throwable $e) {
            return new Response(400, [], $e->getMessage());
        }
    }

    public function post(string $uri, array $body = []): Response
    {
        return $this->request('POST', $uri, ['json' => $body]);
    }

    public function get(string $uri, array $query = []): Response
    {
        return $this->request('GET', $uri, ['query' => $query]);
    }
}
