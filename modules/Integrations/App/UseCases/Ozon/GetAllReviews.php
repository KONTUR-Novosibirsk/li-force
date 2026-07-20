<?php

namespace Modules\Integrations\App\UseCases\Ozon;

use Illuminate\Support\Facades\Log;
use Modules\Integrations\App\Services\OzonSellerApiClient;

final readonly class GetAllReviews
{
    const MAX_LIMIT = 70; // 20 - 100

    const SORT = 'DESC';

    public function __construct(private OzonSellerApiClient $client)
    {
    }

    public function handle(): array
    {
        $all = [];
        $lastId = null;

        do {
            $page = $this->getReviewsPage($lastId);

            if (empty($page['reviews'])) {
                break;
            }

            $all = array_merge($all, $page['reviews']);
            $lastId = $page['last_id'] ?? null;

            usleep(333_000);

        } while (! empty($page['has_next']));

        return $all;
    }

    private function getReviewsPage(?string $lastId = null): array
    {
        $payload = [
            'limit' => self::MAX_LIMIT,
            'sort_dir' => self::SORT,
            'status' => 'ALL',
        ];

        if ($lastId) {
            $payload['last_id'] = $lastId;
        }

        $response = $this->client->post('/v1/review/list', $payload);

        if ($response->status !== 200) {
            Log::error('[OZON] review.list failed', ['message' => $response->body]);
        }

        return json_decode($response->body, true) ?: [];
    }
}
