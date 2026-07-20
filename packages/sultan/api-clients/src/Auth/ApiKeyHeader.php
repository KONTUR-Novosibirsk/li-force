<?php

namespace Sultan\ApiClients\Auth;

final readonly class ApiKeyHeader implements AuthStrategyInterface
{
    public function __construct(
        private string $apiKey,
        private string $apiHeaderKey = 'X-API-KEY'
    ) {
    }

    public function apply(array $options): array
    {
        $options['headers'][$this->apiHeaderKey] = $this->apiKey;

        return $options;
    }
}
