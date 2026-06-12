<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            // Menghubungkan tabel ini ke tabel customers (Foreign Key)
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('field_key');   // Nama field di sistem (misal: 'tanggal_lahir')
            $table->string('field_value'); // Isi datanya (misal: '2004-05-19')
=======

            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('field_key');
            $table->string('field_value');
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
