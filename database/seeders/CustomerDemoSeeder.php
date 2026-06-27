<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerDemoSeeder extends Seeder
{
    public function run(): void
    {
        $salesUser = User::where('email', 'sales@smartcrm.com')->first();
        $adminUser = User::where('email', 'admin@smartcrm.com')->first();

        $tags = Tag::query()->pluck('id', 'name');
        $companies = ['Nusantara Digital', 'Mitra Retail Jaya', 'Borneo Logistics', 'Sinar EduTech', 'Astra Prima'];
        $statuses = ['Lead', 'Active', 'Customer', 'Inactive'];

        for ($i = 1; $i <= 50; $i++) {
            $customer = Customer::updateOrCreate(
                ['customer_code' => sprintf('CUST-INDRA-%03d', $i)],
                [
                    'full_name' => "Customer Demo {$i}",
                    'email' => sprintf('customer.demo.%03d@smartcrm.test', $i),
                    'phone' => '08123' . str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                    'company_name' => $companies[($i - 1) % count($companies)],
                    'status' => $statuses[($i - 1) % count($statuses)],
                    'assigned_user_id' => $i % 2 === 0 ? $salesUser?->id : $adminUser?->id,
                    'custom_fields' => [
                        'Instagram' => '@customer_demo_' . $i,
                        'Kategori' => $i % 3 === 0 ? 'VIP' : 'Regular',
                        'Data Source' => 'Indra CSV',
                    ],
                ]
            );

            $customer->customFields()->delete();
            $customer->customFields()->createMany([
                ['field_key' => 'Instagram', 'field_value' => '@customer_demo_' . $i],
                ['field_key' => 'Kategori', 'field_value' => $i % 3 === 0 ? 'VIP' : 'Regular'],
                ['field_key' => 'Data Source', 'field_value' => 'Indra CSV'],
            ]);

            $customer->tags()->syncWithoutDetaching(array_filter([
                $i % 3 === 0 ? $tags->get('VIP') : $tags->get('Retail'),
                $i % 5 === 0 ? $tags->get('Priority Tinggi') : null,
                $i % 4 === 0 ? $tags->get('B2B') : null,
            ]));
        }

        $primary = Customer::updateOrCreate(
            ['customer_code' => 'CUST-DEMO-001'],
            [
                'full_name' => 'Budi Santoso',
                'email' => 'budi.primary@mail.test',
                'phone' => '08123456789',
                'company_name' => 'Nusantara Digital',
                'status' => 'Lead',
                'assigned_user_id' => $salesUser?->id,
                'custom_fields' => ['Kategori' => 'VIP'],
            ]
        );

        $duplicate = Customer::updateOrCreate(
            ['customer_code' => 'CUST-DEMO-002'],
            [
                'full_name' => 'Budi Santoso',
                'email' => 'budi.secondary@mail.test',
                'phone' => '08123456789',
                'company_name' => 'Nusantara Digital',
                'status' => 'Active',
                'assigned_user_id' => $salesUser?->id,
                'custom_fields' => ['Instagram' => '@budi_santoso'],
            ]
        );

        $primary->tags()->syncWithoutDetaching([$tags->get('VIP'), $tags->get('Priority Tinggi')]);
        $duplicate->tags()->syncWithoutDetaching([$tags->get('VIP')]);
    }
}
