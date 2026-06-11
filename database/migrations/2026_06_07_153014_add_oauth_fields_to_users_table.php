<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'provider_name')) {
                $table->string('provider_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable();
            }
            if (! Schema::hasColumn('users', 'avatar_url')) {
                $table->string('avatar_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
