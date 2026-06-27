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


            $table->string('customer_code')->unique();


            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('company_name')->nullable();


            $table->string('status')->default('Lead');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
