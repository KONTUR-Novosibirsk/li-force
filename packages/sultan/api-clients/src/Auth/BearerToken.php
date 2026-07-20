<?php

namespace Sultan\ApiClients\Auth;

final readonly class BearerToken implements AuthStrategyInterface
{
    public function __construct(
        private string $token
    ) {
    }

    public function apply(array $options): array
    {
        $options['headers']['Authorization'] = 'Bearer '.$this->token;

        return $options;
    }
}
