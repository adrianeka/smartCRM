<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use BezhanSalleh\FilamentShield\Support\Utils;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin role (gets all permissions via Gate::before in AuthServiceProvider)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Create other default roles
        $sales = Role::firstOrCreate(['name' => 'Sales', 'guard_name' => 'web']);
        $marketing = Role::firstOrCreate(['name' => 'Marketing', 'guard_name' => 'web']);
        $support = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'Manager/Analyst', 'guard_name' => 'web']);

        // Assign specific permissions to each role
        
        // 1. Sales Permissions (Customer CRUD + Sales Widgets)
        $salesPermissions = Permission::whereIn('name', [
            'ViewAny:Customer',
            'View:Customer',
            'Create:Customer',
            'Update:Customer',
            'Delete:Customer',
            'View:SalesPipelineWidget',
            'View:SalesSummaryWidget',
            'View:TopDealsWidget',
            'View:Dashboard',
            'View:WelcomeWidget',
        ])->get();
        $sales->syncPermissions($salesPermissions);

        // 2. Marketing Permissions (Customer View/Create/Update + Marketing Widgets)
        $marketingPermissions = Permission::whereIn('name', [
            'ViewAny:Customer',
            'View:Customer',
            'Create:Customer',
            'Update:Customer',
            'View:CampaignPerformanceChart',
            'View:MarketingStatsWidget',
            'View:RecentCampaignsWidget',
            'View:Dashboard',
            'View:WelcomeWidget',
        ])->get();
        $marketing->syncPermissions($marketingPermissions);

        // 3. Support Permissions (Customer View-only + Support/Ticket Widgets)
        $supportPermissions = Permission::whereIn('name', [
            'ViewAny:Customer',
            'View:Customer',
            'View:SupportStatsWidget',
            'View:TicketsByPriorityChart',
            'View:UrgentTicketsWidget',
            'View:Dashboard',
            'View:WelcomeWidget',
        ])->get();
        $support->syncPermissions($supportPermissions);

        // 4. Manager/Analyst Permissions (Read-only view access to all resources and widgets for business monitoring)
        $managerPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', 'View:%')
                  ->orWhere('name', 'like', 'ViewAny:%');
        })->get();
        $manager->syncPermissions($managerPermissions);
    }
}
