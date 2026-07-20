<?php

namespace Modules\Integrations\App\UseCases\Wildberries;

use Illuminate\Support\Facades\Log;
use Modules\Integrations\App\Services\WildberriesFeedbacksApiClient;
use RuntimeException;

final readonly class GetReviewsByProduct
{
    const MAX_LIMIT = 70;

    const SORT = 'date';

    public function __construct(
        private WildberriesFeedbacksApiClient $client
    ) {
    }

    public function handle(int $productId): array
    {
        $all = [];
        $offset = 0;

        do {
            $page = $this->getReviewsPage($productId, $offset);

            usleep(333_000);

            if (empty($page['data']['feedbacks'])) {
                break;
            }

            $all = array_merge($all, $page['data']['feedbacks']);
            $offset += self::MAX_LIMIT;

        } while (count($page['data']['feedbacks']) === self::MAX_LIMIT);

        return $all;
    }

    private function getReviewsPage(int $productId, int $offset): array
    {
        $payload = [
            'nmId' => $productId,
            'take' => self::MAX_LIMIT,
            'skip' => $offset,
            'order' => self::SORT,
            'isAnswered' => true,
        ];

        $response = $this->client->get('/api/v1/feedbacks', $payload);

        if ($response->status !== 200) {
            Log::error('[WB] review.list failed', [
                'productId' => $productId,
                'offset' => $offset,
                'status' => $response->status,
                'message' => $response->body,
            ]);
            throw new RuntimeException('WB reviews request failed');
        }

        $data = json_decode($response->body, true);

        if (! is_array($data)) {
            throw new RuntimeException('WB reviews invalid JSON');
        }

        return $data;
    }
}
