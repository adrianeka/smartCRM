<?php

namespace Tests\Feature;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_be_created_without_saving_activity_log_repeater_rows(): void
    {
        $role = Role::create(['name' => 'super_admin']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user);

        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'customer_code' => 'CUST-TEST-001',
                'full_name' => 'Test Customer',
                'email' => 'customer@example.test',
                'phone' => '081234567890',
                'status' => 'Lead',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Customer::class, [
            'customer_code' => 'CUST-TEST-001',
            'email' => 'customer@example.test',
        ]);
    }
}
