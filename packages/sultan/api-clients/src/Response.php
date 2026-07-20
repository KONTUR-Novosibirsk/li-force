<?php

namespace Sultan\ApiClients;

readonly class Response
{
    public function __construct(
        public int $status,
        public array $headers,
        public string $body
    ) {
    }
}
