<?php

namespace Sultan\ApiClients\Http;

use Sultan\ApiClients\Response;

interface HttpClientInterface
{
    public function request(string $method, string $url, array $options = []): Response;
}
