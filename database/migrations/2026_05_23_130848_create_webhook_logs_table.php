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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();

            $table->string('event_type');

            $table->string('source_module');

            $table->string('target_url')->nullable();

            $table->integer('status_code')->nullable();

            $table->longText('payload')->nullable();

            $table->text('response')->nullable();

            $table->enum('status', [
                'pending',
                'success',
                'failed'
            ])->default('pending');

            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
