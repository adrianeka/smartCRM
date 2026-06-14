<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnalyticsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $salesUsers = User::role('Sales')->get();
        $customers = Customer::query()->get();

        if ($salesUsers->isEmpty() || $customers->isEmpty()) {
            return;
        }

        $products = collect([
            ['name' => 'CRM Starter', 'sku' => 'CRM-STARTER', 'category' => 'Subscription'],
            ['name' => 'CRM Professional', 'sku' => 'CRM-PRO', 'category' => 'Subscription'],
            ['name' => 'CRM Enterprise', 'sku' => 'CRM-ENTERPRISE', 'category' => 'Subscription'],
        ])->map(fn (array $product): Product => Product::updateOrCreate(
            ['sku' => $product['sku']],
            $product + ['is_active' => true],
        ));

        $stages = ['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Won'];

        foreach ($customers->take(36)->values() as $index => $customer) {
            $status = match ($index % 5) {
                0, 1 => 'Won',
                2 => 'Lost',
                default => 'Open',
            };
            $createdAt = now()->subMonths($index % 8)->subDays($index % 20);
            $closedAt = in_array($status, ['Won', 'Lost'], true)
                ? $createdAt->copy()->addDays(10 + ($index % 15))
                : null;

            Deal::updateOrCreate(
                ['name' => 'Demo Deal '.$customer->customer_code],
                [
                    'customer_id' => $customer->id,
                    'owner_id' => $salesUsers[$index % $salesUsers->count()]->id,
                    'product_id' => $products[$index % $products->count()]->id,
                    'stage' => $status === 'Won' ? 'Won' : $stages[$index % count($stages)],
                    'status' => $status,
                    'amount' => 7500000 + (($index % 7) * 2500000),
                    'probability' => $status === 'Won' ? 100 : (($index % 4) + 1) * 20,
                    'expected_close_at' => $createdAt->copy()->addMonth(),
                    'closed_at' => $status === 'Won' ? $closedAt : null,
                    'lost_at' => $status === 'Lost' ? $closedAt : null,
                    'created_at' => $createdAt,
                    'updated_at' => $closedAt ?? $createdAt,
                ],
            );
        }
    }
}
