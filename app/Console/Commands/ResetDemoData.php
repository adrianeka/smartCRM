<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\CustomerAttachment;
use App\Models\CustomerCustomField;
use App\Models\Tag;
use Database\Seeders\CustomerDemoSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDemoData extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Reset akun, tag, dan data customer demo SmartCRM79 untuk kebutuhan presentasi.';

    public function handle(): int
    {
        DB::transaction(function (): void {
            CustomerAttachment::query()->delete();
            CustomerCustomField::query()->delete();
            DB::table('customer_tag')->delete();

            Customer::query()
                ->where('customer_code', 'like', 'CUST-INDRA-%')
                ->orWhere('customer_code', 'like', 'CUST-CSV-%')
                ->orWhereIn('customer_code', ['CUST-DEMO-001', 'CUST-DEMO-002'])
                ->delete();

            Tag::query()
                ->whereIn('name', ['VIP', 'Prioritas Tinggi', 'B2B', 'Retail', 'Prospek Hangat'])
                ->delete();
        });

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(CustomerDemoSeeder::class);

        $this->components->info('Data demo SmartCRM79 sudah di-reset.');
        $this->line('Akun demo memakai password: password');
        $this->line('Admin: admin@smartcrm.com');
        $this->line('Sales: sales@smartcrm.com');
        $this->line('Marketing: marketing@smartcrm.com');
        $this->line('Support: support@smartcrm.com');
        $this->line('Manager: manager@smartcrm.com');

        return self::SUCCESS;
    }
}
