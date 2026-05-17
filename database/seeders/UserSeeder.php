<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager Analyst',
                'email' => 'manager@smartcrm.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'Sales Representative',
                'email' => 'sales@smartcrm.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'sales',
            ],
            [
                'name' => 'Marketing Specialist',
                'email' => 'marketing@smartcrm.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'marketing',
            ],
            [
                'name' => 'Customer Support',
                'email' => 'support@smartcrm.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'support',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
