<?php

namespace App\Services\Analytics;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsQuery
{
    /**
     * @return Builder<Deal>
     */
    public function deals(User $user, AnalyticsFilterData $filters): Builder
    {
        return Deal::query()
            ->with(['customer:id,full_name,province,city,status', 'owner:id,name', 'product:id,name'])
            ->when(
                $user->hasRole('Sales'),
                fn (Builder $query): Builder => $query->where('owner_id', $user->id),
            )
            ->when(
                $filters->ownerId,
                fn (Builder $query, int $ownerId): Builder => $query->where('owner_id', $ownerId),
            )
            ->when(
                $filters->productId,
                fn (Builder $query, int $productId): Builder => $query->where('product_id', $productId),
            )
            ->when(
                $filters->region,
                fn (Builder $query, string $region): Builder => $query->whereHas(
                    'customer',
                    fn (Builder $customerQuery): Builder => $customerQuery
                        ->where('province', $region)
                        ->orWhere('city', $region),
                ),
            )
            ->when(
                $filters->from,
                fn (Builder $query): Builder => $query->where(
                    fn (Builder $dateQuery): Builder => $dateQuery
                        ->where('closed_at', '>=', $filters->from)
                        ->orWhere(
                            fn (Builder $fallbackQuery): Builder => $fallbackQuery
                                ->whereNull('closed_at')
                                ->where('created_at', '>=', $filters->from),
                        ),
                ),
            )
            ->when(
                $filters->until,
                fn (Builder $query): Builder => $query->where(
                    fn (Builder $dateQuery): Builder => $dateQuery
                        ->where('closed_at', '<=', $filters->until)
                        ->orWhere(
                            fn (Builder $fallbackQuery): Builder => $fallbackQuery
                                ->whereNull('closed_at')
                                ->where('created_at', '<=', $filters->until),
                        ),
                ),
            );
    }
}
