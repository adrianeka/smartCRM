<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles & Permissions
        $this->call(RoleSeeder::class);

        // 2. Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@smartcrm.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // 3. Create test users for each role
        $testUsers = [
            ['name' => 'Sales User', 'email' => 'sales@smartcrm.com', 'role' => 'Sales'],
            ['name' => 'Marketing User', 'email' => 'marketing@smartcrm.com', 'role' => 'Marketing'],
            ['name' => 'Support User', 'email' => 'support@smartcrm.com', 'role' => 'Support'],
            ['name' => 'Manager User', 'email' => 'manager@smartcrm.com', 'role' => 'Manager/Analyst'],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);
        }

        // 4. Seed Role-specific app notifications
        $this->call(TagSeeder::class);
        $this->call(CustomerDemoSeeder::class);
        $this->call(AnalyticsDemoSeeder::class);
        $this->call(AnalyticsReportDemoSeeder::class);
        $this->call(NotificationSeeder::class);
    }
}
