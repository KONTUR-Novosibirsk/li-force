<?php

namespace Sultan\ApiClients\Auth;

final readonly class MultiHeaderAuth implements AuthStrategyInterface
{
    public function __construct(
        private array $headers,
    ) {
    }

    public function apply(array $options): array
    {
        foreach ($this->headers as $key => $value) {
            $options['headers'][$key] = $value;
        }

        return $options;
    }
}
