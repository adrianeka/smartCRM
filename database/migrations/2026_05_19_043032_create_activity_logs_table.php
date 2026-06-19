<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan log ke pelanggan terkait
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('activity_type'); // Jenis aktivitas (misal: 'Create', 'Update', 'Merge')
            $table->text('description');     // Detail aktivitas
            $table->unsignedBigInteger('causer_id')->nullable(); // ID User/Admin yang melakukan aksi
            $table->timestamps(); // Menggunakan created_at bawaan sebagai penanda waktu (timeline)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
