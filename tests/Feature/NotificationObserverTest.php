<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_creation_sends_notification_to_sales()
    {
        $salesRole = Role::create(['name' => 'Sales']);
        $salesUser = User::factory()->create();
        $salesUser->assignRole($salesRole);

        $customer = Customer::create([
            'customer_code' => 'CUST-TEST-001',
            'full_name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '08123456789',
            'company_name' => 'Test Company',
            'status' => 'Lead',
        ]);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $salesUser->id,
            'title' => 'New Lead Assigned',
            'source_module' => 'sales',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $salesUser->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_customer_status_update_sends_notification_to_manager()
    {
        $managerRole = Role::create(['name' => 'Manager/Analyst']);
        $managerUser = User::factory()->create();
        $managerUser->assignRole($managerRole);

        $customer = Customer::create([
            'customer_code' => 'CUST-TEST-002',
            'full_name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'phone' => '08123456789',
            'company_name' => 'Acme Corp',
            'status' => 'Lead',
        ]);

        $customer->update(['status' => 'Customer']);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $managerUser->id,
            'title' => 'Deal Closed Successfully!',
            'source_module' => 'sales',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $managerUser->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_failed_webhook_sends_notification_to_admin()
    {
        $adminRole = Role::create(['name' => 'super_admin']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        WebhookLog::create([
            'event_type' => 'customer.created',
            'source_module' => 'integration',
            'target_url' => 'https://api.test/webhook',
            'status_code' => 500,
            'payload' => json_encode(['data' => 'test']),
            'status' => 'failed',
        ]);

        $this->assertDatabaseHas('app_notifications', [
            'user_id' => $adminUser->id,
            'title' => 'Webhook Delivery Failed',
            'source_module' => 'integration',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $adminUser->id,
            'notifiable_type' => User::class,
        ]);
    }
}
