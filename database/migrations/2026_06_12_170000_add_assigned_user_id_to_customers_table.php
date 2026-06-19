<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('customers', 'assigned_user_id')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table): void {
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('customers', 'assigned_user_id')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('assigned_user_id');
        });
    }
};
