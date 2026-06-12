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
                'role' => 'super_admin',
            ],
            [
                'name' => 'Manager Analyst',
                'email' => 'manager@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'Manager/Analyst',
            ],
            [
                'name' => 'Sales Representative',
                'email' => 'sales@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'Sales',
            ],
            [
                'name' => 'Marketing Specialist',
                'email' => 'marketing@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'Marketing',
            ],
            [
                'name' => 'Customer Support',
                'email' => 'support@smartcrm.com',
                'password' => Hash::make('password'),
                'role' => 'Support',
            ],
        ];

        foreach ($users as $user) {
            $role = $user['role'];
            unset($user['role']);

            User::updateOrCreate(['email' => $user['email']], $user)
                ->syncRoles([$role]);
        }
    }
}
