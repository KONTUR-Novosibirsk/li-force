<?php

namespace Modules\Integrations\App\Services;

use Sultan\ApiClients\ApiClient;
use Sultan\ApiClients\Auth\MultiHeaderAuth;
use Sultan\ApiClients\Http\GuzzleClient;

final readonly class OzonSellerApiClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(
            http: new GuzzleClient(
                url: config('ozon.api.seller.url'),
                timeout: config('ozon.api.seller.request_timeout')
            ),
            auth: new MultiHeaderAuth([
                'Client-Id' => config('ozon.api.seller.client_id'),
                'Api-Key' => config('ozon.api.seller.key'),
            ])
        );
    }
}
