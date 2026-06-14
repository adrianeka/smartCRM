<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_report_definitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('dataset')->default('sales_performance');
            $table->json('columns');
            $table->json('filters')->nullable();
            $table->json('branding')->nullable();
            $table->string('visibility')->default('private');
            $table->timestamps();
        });

        Schema::create('analytics_report_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('report_definition_id')
                ->constrained('analytics_report_definitions')
                ->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('frequency');
            $table->string('format')->default('xlsx');
            $table->json('recipients');
            $table->timestamp('next_run_at');
            $table->timestamp('last_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'next_run_at']);
        });

        Schema::create('analytics_report_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('report_definition_id')
                ->constrained('analytics_report_definitions')
                ->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('analytics_report_schedules')
                ->nullOnDelete();
            $table->string('format');
            $table->string('status')->default('pending');
            $table->string('file_disk')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_report_runs');
        Schema::dropIfExists('analytics_report_schedules');
        Schema::dropIfExists('analytics_report_definitions');
    }
};
