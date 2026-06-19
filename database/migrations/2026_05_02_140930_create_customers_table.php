<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // ID Utama (Primary Key)

            // Kode unik pelanggan untuk kebutuhan integrasi dengan kelompok lain (Sales/Marketing)
            $table->string('customer_code')->unique();

            // Atribut inti data profil pelanggan
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('company_name')->nullable(); // Boleh kosong jika pelanggan retail

            // Status data pelanggan (contoh default awal: 'Lead')
            $table->string('status')->default('Lead');

            $table->timestamps(); // Otomatis membuat kolom created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
