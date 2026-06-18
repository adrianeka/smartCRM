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
Schema::create('opportunities', function (Blueprint $table) {
    $table->id();

    $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('title');

    $table->enum('stage', [
        'Lead',
        'Prospect',
        'Negotiation',
        'Proposal',
        'Won',
        'Lost'
    ])->default('Lead');

    $table->decimal('deal_value', 15, 2)
        ->default(0);

    $table->integer('probability')
        ->default(0);

    $table->date('expected_close_date')
        ->nullable();

    $table->text('notes')
        ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
