<?php

namespace Sultan\ApiClients\Auth;

interface AuthStrategyInterface
{
    public function apply(array $options): array;
}
