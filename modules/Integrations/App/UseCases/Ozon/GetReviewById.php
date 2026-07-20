<?php

namespace Modules\Integrations\App\UseCases\Ozon;

use Illuminate\Support\Facades\Log;
use Modules\Integrations\App\Services\OzonSellerApiClient;

final readonly class GetReviewById
{
    public function __construct(private OzonSellerApiClient $client)
    {
    }

    public function handle(string $reviewId): ?array
    {
        $attempts = 0;
        $maxAttempts = 4;

        do {
            $attempts++;

            $response = $this->client->post('/v1/review/info', ['review_id' => $reviewId]);

            if ($response->status === 200) {
                return json_decode($response->body, true) ?: [];
            }

            if ($response->status === 404) {
                return null;
            }

            usleep(333_000);

        } while ($attempts < $maxAttempts);

        Log::error('[OZON] review.info failed after retries', [
            'review_id' => $reviewId,
            'attempts' => $attempts,
            'last_status' => $response->status ?? null,
            'message' => $response->body ?? null,
        ]);

        return null;
    }
}
