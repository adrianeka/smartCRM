<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('customers', 'phone')) {
            if (DB::getDriverName() === 'sqlite') {
                return;
            }

            DB::statement('ALTER TABLE customers MODIFY phone VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'phone')) {
            if (DB::getDriverName() === 'sqlite') {
                return;
            }

            DB::statement("UPDATE customers SET phone = '' WHERE phone IS NULL");
            DB::statement('ALTER TABLE customers MODIFY phone VARCHAR(255) NOT NULL');
        }
    }
};
