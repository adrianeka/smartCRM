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
        $guest = Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']);

        // Assign basic permissions to each role
        // Sales: can view and create users
        $salesPermissions = Permission::whereIn('name', [
            'view_shield::role',
        ])->get();
        $sales->syncPermissions($salesPermissions);

        // Marketing: can view users
        $marketingPermissions = Permission::whereIn('name', [
            'view_shield::role',
        ])->get();
        $marketing->syncPermissions($marketingPermissions);

        // Support: can view users
        $supportPermissions = Permission::whereIn('name', [
            'view_shield::role',
        ])->get();
        $support->syncPermissions($supportPermissions);

        // Manager/Analyst: can view and manage users, view activity logs
        $managerPermissions = Permission::whereIn('name', [
            'view_shield::role',
            'view_any_shield::role',
        ])->get();
        $manager->syncPermissions($managerPermissions);
    }
}
