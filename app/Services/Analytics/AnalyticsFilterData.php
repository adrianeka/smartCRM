<?php

namespace App\Services\Analytics;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

final readonly class AnalyticsFilterData
{
    public function __construct(
        public ?CarbonImmutable $from = null,
        public ?CarbonImmutable $until = null,
        public ?int $ownerId = null,
        public ?int $productId = null,
        public ?string $region = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validate([
            'from' => ['nullable', 'date'],
            'until' => ['nullable', 'date', 'after_or_equal:from'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'region' => ['nullable', 'string', 'max:255'],
        ]));
    }

    public static function fromArray(array $filters): self
    {
        return new self(
            from: filled($filters['from'] ?? null)
                ? CarbonImmutable::parse($filters['from'])->startOfDay()
                : null,
            until: filled($filters['until'] ?? null)
                ? CarbonImmutable::parse($filters['until'])->endOfDay()
                : null,
            ownerId: filled($filters['owner_id'] ?? null) ? (int) $filters['owner_id'] : null,
            productId: filled($filters['product_id'] ?? null) ? (int) $filters['product_id'] : null,
            region: filled($filters['region'] ?? null) ? (string) $filters['region'] : null,
        );
    }
}
