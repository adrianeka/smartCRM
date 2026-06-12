<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->fullText([
                'customer_code',
                'full_name',
                'email',
                'phone',
                'whatsapp',
                'company_name',
                'industry',
                'city',
                'province',
                'source',
                'notes',
            ], 'customers_full_text_index');
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->dropFullText('customers_full_text_index');
        });
    }
};
