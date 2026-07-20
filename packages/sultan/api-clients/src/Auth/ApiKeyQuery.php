<?php

namespace Sultan\ApiClients\Auth;

final readonly class ApiKeyQuery implements AuthStrategyInterface
{
    public function __construct(
        private string $apiKey,
        private string $queryParam = 'api_key'
    ) {
    }

    public function apply(array $options): array
    {
        $options['query'][$this->queryParam] = $this->apiKey;

        return $options;
    }
}
