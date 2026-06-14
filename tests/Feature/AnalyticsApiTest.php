<?php

namespace Tests\Feature;

use App\Filament\Widgets\Analytics\SalesPerformanceWidget;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AnalyticsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_analytics_for_all_sales_people(): void
    {
        [$manager, $firstSales, $secondSales, $product] = $this->analyticsUsersAndProduct();

        $this->createDeal($firstSales, $product, 'Won', 10000000, 'DKI Jakarta');
        $this->createDeal($secondSales, $product, 'Won', 15000000, 'Jawa Barat');
        $this->createDeal($secondSales, $product, 'Lost', 5000000, 'Jawa Barat');

        Sanctum::actingAs($manager);

        $this->getJson('/api/v1/analytics/dashboard')
            ->assertOk()
            ->assertJsonPath('data.kpis.total_revenue', 25000000)
            ->assertJsonPath('data.kpis.conversion_rate', 66.7)
            ->assertJsonCount(2, 'data.sales_performance');
    }

    public function test_sales_person_only_sees_their_own_analytics(): void
    {
        [, $firstSales, $secondSales, $product] = $this->analyticsUsersAndProduct();

        $this->createDeal($firstSales, $product, 'Won', 10000000, 'DKI Jakarta');
        $this->createDeal($secondSales, $product, 'Won', 15000000, 'Jawa Barat');

        Sanctum::actingAs($firstSales);

        $this->getJson('/api/v1/analytics/dashboard')
            ->assertOk()
            ->assertJsonPath('data.kpis.total_revenue', 10000000)
            ->assertJsonCount(1, 'data.sales_performance')
            ->assertJsonPath('data.sales_performance.0.sales_person', $firstSales->name);
    }

    public function test_analytics_can_be_filtered_by_region_and_product(): void
    {
        [$manager, $sales, , $product] = $this->analyticsUsersAndProduct();
        $otherProduct = Product::create(['name' => 'Other', 'sku' => 'OTHER']);

        $this->createDeal($sales, $product, 'Won', 10000000, 'DKI Jakarta');
        $this->createDeal($sales, $otherProduct, 'Won', 20000000, 'Jawa Barat');

        Sanctum::actingAs($manager);

        $this->getJson("/api/v1/analytics/kpis?region=DKI%20Jakarta&product_id={$product->id}")
            ->assertOk()
            ->assertJsonPath('data.total_revenue', 10000000);
    }

    public function test_analytics_can_be_filtered_by_date_range(): void
    {
        [$manager, $sales, , $product] = $this->analyticsUsersAndProduct();
        $oldDeal = $this->createDeal($sales, $product, 'Won', 20000000, 'DKI Jakarta');
        $oldDeal->forceFill([
            'created_at' => now()->subYear(),
            'closed_at' => now()->subYear(),
        ])->save();
        $this->createDeal($sales, $product, 'Won', 10000000, 'DKI Jakarta');

        Sanctum::actingAs($manager);

        $this->getJson('/api/v1/analytics/kpis?from='.now()->startOfYear()->toDateString())
            ->assertOk()
            ->assertJsonPath('data.total_revenue', 10000000);
    }

    public function test_user_without_analytics_permission_is_forbidden(): void
    {
        $supportRole = Role::create(['name' => 'Support']);
        $support = User::factory()->create();
        $support->assignRole($supportRole);

        Sanctum::actingAs($support);

        $this->getJson('/api/v1/analytics/dashboard')->assertForbidden();
    }

    public function test_manager_can_render_filament_analytics_dashboard(): void
    {
        [$manager, $sales, , $product] = $this->analyticsUsersAndProduct();
        $this->createDeal($sales, $product, 'Won', 10000000, 'DKI Jakarta');

        $this->actingAs($manager)
            ->withSession(['mfa_verified' => true])
            ->get('/admin/analytics')
            ->assertOk()
            ->assertSee('Analytics &amp; Reporting', escape: false)
            ->assertSee('Dari Tanggal')
            ->assertSee('Sales Person');
    }

    public function test_sales_performance_widget_renders_filtered_data(): void
    {
        [$manager, $sales, , $product] = $this->analyticsUsersAndProduct();
        $this->createDeal($sales, $product, 'Won', 10000000, 'DKI Jakarta');

        $this->actingAs($manager);

        Livewire::test(SalesPerformanceWidget::class, ['pageFilters' => []])
            ->assertOk()
            ->assertSee('Performa Sales')
            ->assertSee($sales->name)
            ->assertSee('Rp 10.000.000');
    }

    public function test_manager_can_export_sales_performance_csv(): void
    {
        [$manager, $sales, , $product] = $this->analyticsUsersAndProduct();
        $this->createDeal($sales, $product, 'Won', 10000000, 'DKI Jakarta');

        Sanctum::actingAs($manager);

        $this->get('/api/v1/analytics/sales-performance/export/csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertDownload();
    }

    private function analyticsUsersAndProduct(): array
    {
        $permission = Permission::create(['name' => 'View:Analytics']);
        $managerRole = Role::create(['name' => 'Manager/Analyst']);
        $salesRole = Role::create(['name' => 'Sales']);
        $managerRole->givePermissionTo($permission);
        $salesRole->givePermissionTo($permission);

        $manager = User::factory()->create(['name' => 'Manager']);
        $firstSales = User::factory()->create(['name' => 'Sales One']);
        $secondSales = User::factory()->create(['name' => 'Sales Two']);
        $manager->assignRole($managerRole);
        $firstSales->assignRole($salesRole);
        $secondSales->assignRole($salesRole);

        $product = Product::create(['name' => 'CRM Pro', 'sku' => 'CRM-PRO']);

        return [$manager, $firstSales, $secondSales, $product];
    }

    private function createDeal(
        User $owner,
        Product $product,
        string $status,
        float $amount,
        string $province,
    ): Deal {
        $customer = Customer::create([
            'customer_code' => fake()->unique()->bothify('CUST-####'),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'province' => $province,
            'status' => 'Active',
            'assigned_user_id' => $owner->id,
        ]);

        return Deal::create([
            'customer_id' => $customer->id,
            'owner_id' => $owner->id,
            'product_id' => $product->id,
            'name' => 'Deal '.$customer->customer_code,
            'stage' => $status === 'Won' ? 'Won' : 'Negotiation',
            'status' => $status,
            'amount' => $amount,
            'probability' => $status === 'Won' ? 100 : 40,
            'closed_at' => in_array($status, ['Won', 'Lost'], true) ? now() : null,
            'lost_at' => $status === 'Lost' ? now() : null,
        ]);
    }
}
