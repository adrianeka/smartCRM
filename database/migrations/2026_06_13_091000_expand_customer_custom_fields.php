<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_custom_fields', function (Blueprint $table) {
            if (! Schema::hasColumn('customer_custom_fields', 'field_type')) {
                $table->string('field_type')->default('text')->after('field_key');
            }

            if (! Schema::hasColumn('customer_custom_fields', 'field_options')) {
                $table->json('field_options')->nullable()->after('field_type');
            }

            if (! Schema::hasColumn('customer_custom_fields', 'field_date')) {
                $table->date('field_date')->nullable()->after('field_value');
            }

            if (! Schema::hasColumn('customer_custom_fields', 'field_boolean')) {
                $table->boolean('field_boolean')->nullable()->after('field_date');
            }

            if (! Schema::hasColumn('customer_custom_fields', 'file_path')) {
                $table->string('file_path')->nullable()->after('field_boolean');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customer_custom_fields', function (Blueprint $table) {
            foreach ([
                'field_type',
                'field_options',
                'field_date',
                'field_boolean',
                'file_path',
            ] as $column) {
                if (Schema::hasColumn('customer_custom_fields', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
