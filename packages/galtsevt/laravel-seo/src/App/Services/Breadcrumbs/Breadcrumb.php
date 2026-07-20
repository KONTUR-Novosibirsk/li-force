<?php

namespace Galtsevt\LaravelSeo\App\Services\Breadcrumbs;

class Breadcrumb
{
    private ?string $url = null;

    private string $name;

    public function __construct(string $name, ?string $url = null)
    {
        $this->url = $url;
        $this->name = $name;
    }

    public function getUrl(): ?string
    {
        return $this->url == 0 ? null : $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
