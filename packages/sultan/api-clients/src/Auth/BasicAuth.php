<?php

namespace Sultan\ApiClients\Auth;

final readonly class BasicAuth implements AuthStrategyInterface
{
    public function __construct(
        private string $username,
        private string $password
    ) {
    }

    public function apply(array $options): array
    {
        $options['auth'] = [$this->username, $this->password];

        return $options;
    }
}
