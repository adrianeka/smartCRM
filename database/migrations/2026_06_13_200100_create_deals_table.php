<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('stage')->default('Lead');
            $table->string('status')->default('Open');
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedTinyInteger('probability')->default(0);
            $table->date('expected_close_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('lost_at')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'status']);
            $table->index(['status', 'closed_at']);
            $table->index(['stage', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
