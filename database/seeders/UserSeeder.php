<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager Analyst',
                'email' => 'manager@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'Sales Representative',
                'email' => 'sales@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'sales',
            ],
            [
                'name' => 'Marketing Specialist',
                'email' => 'marketing@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'marketing',
            ],
            [
                'name' => 'Customer Support',
                'email' => 'support@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'support',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
