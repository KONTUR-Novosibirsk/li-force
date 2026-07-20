<?php

namespace Modules\Integrations\App\Services;

use Sultan\ApiClients\ApiClient;
use Sultan\ApiClients\Auth\ApiKeyHeader;
use Sultan\ApiClients\Http\GuzzleClient;

final readonly class WildberriesFeedbacksApiClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(
            http: new GuzzleClient(
                url: config('wb.api.feedbacks.url'),
                timeout: config('wb.api.feedbacks.request_timeout')
            ),
            auth: new ApiKeyHeader(config('wb.api.feedbacks.key'), 'Authorization')
        );
    }
}
