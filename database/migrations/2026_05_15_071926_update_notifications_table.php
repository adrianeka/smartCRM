<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('source_module')->nullable();
            $table->string('priority')->default('normal');
            $table->timestamp('read_at')->nullable();
            $table->string('action_url')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {

            $table->dropColumn([
                'type',
                'source_module',
                'priority',
                'read_at',
                'action_url',
            ]);

        });
    }
};
