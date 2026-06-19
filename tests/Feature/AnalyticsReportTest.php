<?php

namespace Tests\Feature;

use App\Filament\Resources\AnalyticsReports\Pages\CreateAnalyticsReport;
use App\Filament\Resources\AnalyticsReportSchedules\Pages\CreateAnalyticsReportSchedule;
use App\Jobs\GenerateScheduledAnalyticsReport;
use App\Mail\ScheduledAnalyticsReportMail;
use App\Models\AnalyticsReportDefinition;
use App\Models\AnalyticsReportRun;
use App\Models\AnalyticsReportSchedule;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Product;
use App\Models\User;
use App\Services\Analytics\ReportBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AnalyticsReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_builder_preserves_custom_column_order(): void
    {
        [$manager, $sales, $product] = $this->analyticsUsersAndProduct();
        $this->createWonDeal($sales, $product, 12500000);
        $report = $this->createReport($manager, ['revenue', 'sales_person']);

        $data = app(ReportBuilderService::class)->build($report, $manager);

        $this->assertSame(['Revenue (IDR)', 'Sales Person'], $data['headings']);
        $this->assertSame([12500000.0, $sales->name], $data['rows'][0]);
    }

    public function test_manager_can_create_custom_report_through_filament_builder(): void
    {
        [$manager] = $this->analyticsUsersAndProduct();
        $this->actingAs($manager);

        Livewire::test(CreateAnalyticsReport::class)
            ->fillForm([
                'name' => 'Filament Builder Report',
                'visibility' => 'private',
                'columns' => [
                    ['column' => 'revenue'],
                    ['column' => 'sales_person'],
                ],
                'filters' => [
                    'from' => now()->startOfYear()->toDateString(),
                    'until' => now()->endOfYear()->toDateString(),
                ],
                'branding' => [
                    'title' => 'SmartCRM Analytics',
                    'subtitle' => 'Builder Test',
                    'primary_color' => '#f59e0b',
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $report = AnalyticsReportDefinition::where('name', 'Filament Builder Report')->firstOrFail();
        $this->assertSame(['revenue', 'sales_person'], $report->columns);
        $this->assertSame($manager->id, $report->owner_id);
    }

    public function test_manager_can_download_branded_csv_pdf_and_excel_reports(): void
    {
        [$manager, $sales, $product] = $this->analyticsUsersAndProduct();
        $this->createWonDeal($sales, $product, 12500000);
        $report = $this->createReport($manager);

        $this->actingAs($manager)->withSession(['mfa_verified' => true]);

        $this->get(route('analytics.reports.export', [$report, 'csv']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertSee('SmartCRM Analytics')
            ->assertSee('"Sales Person","Revenue (IDR)"', escape: false);

        $pdfResponse = $this->get(route('analytics.reports.export', [$report, 'pdf']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $pdfResponse->getContent());

        $this->get(route('analytics.reports.export', [$report, 'xlsx']))
            ->assertOk()
            ->assertDownload();
    }

    public function test_sales_cannot_export_another_users_private_report(): void
    {
        [$manager, $sales] = $this->analyticsUsersAndProduct();
        $report = $this->createReport($manager);

        $this->actingAs($sales)
            ->withSession(['mfa_verified' => true])
            ->get(route('analytics.reports.export', [$report, 'csv']))
            ->assertForbidden();
    }

    public function test_scheduled_report_generates_file_records_run_and_sends_email(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$manager, $sales, $product] = $this->analyticsUsersAndProduct();
        $this->createWonDeal($sales, $product, 12500000);
        $report = $this->createReport($manager);
        $schedule = AnalyticsReportSchedule::create([
            'report_definition_id' => $report->id,
            'created_by' => $manager->id,
            'frequency' => 'weekly',
            'format' => 'csv',
            'recipients' => ['manager@example.test'],
            'next_run_at' => now()->subMinute(),
            'is_active' => true,
        ]);

        GenerateScheduledAnalyticsReport::dispatchSync($schedule->id);

        $run = AnalyticsReportRun::firstOrFail();
        $this->assertSame('completed', $run->status);
        Storage::disk('local')->assertExists($run->file_path);
        Mail::assertSent(ScheduledAnalyticsReportMail::class, fn ($mail): bool => $mail->hasTo('manager@example.test'));
        $this->assertNotNull($schedule->fresh()->last_run_at);
        $this->assertTrue($schedule->fresh()->next_run_at->isFuture());
    }

    public function test_manager_can_create_schedule_through_filament(): void
    {
        [$manager] = $this->analyticsUsersAndProduct();
        $report = $this->createReport($manager);
        $this->actingAs($manager);

        Livewire::test(CreateAnalyticsReportSchedule::class)
            ->fillForm([
                'report_definition_id' => $report->id,
                'frequency' => 'monthly',
                'format' => 'pdf',
                'recipients' => [
                    ['email' => 'manager@example.test'],
                ],
                'next_run_at' => now()->addMonth(),
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $schedule = AnalyticsReportSchedule::firstOrFail();
        $this->assertSame(['manager@example.test'], $schedule->recipients);
        $this->assertSame($manager->id, $schedule->created_by);
    }

    public function test_scheduler_claims_due_report_before_dispatching(): void
    {
        Queue::fake();
        [$manager] = $this->analyticsUsersAndProduct();
        $report = $this->createReport($manager);
        $schedule = AnalyticsReportSchedule::create([
            'report_definition_id' => $report->id,
            'created_by' => $manager->id,
            'frequency' => 'weekly',
            'format' => 'csv',
            'recipients' => ['manager@example.test'],
            'next_run_at' => now()->subMinute(),
            'is_active' => true,
        ]);

        $this->artisan('analytics:send-scheduled-reports')->assertSuccessful();
        $this->artisan('analytics:send-scheduled-reports')->assertSuccessful();

        Queue::assertPushed(GenerateScheduledAnalyticsReport::class, 1);
        $this->assertTrue($schedule->fresh()->next_run_at->isFuture());
    }

    private function analyticsUsersAndProduct(): array
    {
        $permission = Permission::create(['name' => 'View:Analytics']);
        $managerRole = Role::create(['name' => 'Manager/Analyst']);
        $salesRole = Role::create(['name' => 'Sales']);
        $managerRole->givePermissionTo($permission);
        $salesRole->givePermissionTo($permission);

        $manager = User::factory()->create(['name' => 'Manager']);
        $sales = User::factory()->create(['name' => 'Sales One']);
        $manager->assignRole($managerRole);
        $sales->assignRole($salesRole);

        $product = Product::create(['name' => 'CRM Pro', 'sku' => 'CRM-PRO']);

        return [$manager, $sales, $product];
    }

    private function createWonDeal(User $owner, Product $product, float $amount): Deal
    {
        $customer = Customer::create([
            'customer_code' => 'CUST-REPORT-001',
            'full_name' => 'Report Customer',
            'email' => 'report.customer@example.test',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'status' => 'Active',
            'assigned_user_id' => $owner->id,
        ]);

        return Deal::create([
            'customer_id' => $customer->id,
            'owner_id' => $owner->id,
            'product_id' => $product->id,
            'name' => 'Report Deal',
            'stage' => 'Won',
            'status' => 'Won',
            'amount' => $amount,
            'probability' => 100,
            'closed_at' => now(),
        ]);
    }

    private function createReport(User $owner, array $columns = ['sales_person', 'revenue']): AnalyticsReportDefinition
    {
        return AnalyticsReportDefinition::create([
            'owner_id' => $owner->id,
            'name' => 'Executive Report',
            'dataset' => 'sales_performance',
            'columns' => $columns,
            'filters' => [],
            'branding' => [
                'title' => 'SmartCRM Analytics',
                'subtitle' => 'Executive Report',
                'primary_color' => '#f59e0b',
            ],
            'visibility' => 'private',
        ]);
    }
}
