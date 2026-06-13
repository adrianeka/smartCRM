<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'identity_number')) {
                $table->string('identity_number')->nullable()->after('company_name');
            }

            if (! Schema::hasColumn('customers', 'tax_number')) {
                $table->string('tax_number')->nullable()->after('identity_number');
            }

            if (! Schema::hasColumn('customers', 'gender')) {
                $table->string('gender')->nullable()->after('tax_number');
            }

            if (! Schema::hasColumn('customers', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('gender');
            }

            if (! Schema::hasColumn('customers', 'address')) {
                $table->text('address')->nullable()->after('birth_date');
            }

            if (! Schema::hasColumn('customers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (! Schema::hasColumn('customers', 'province')) {
                $table->string('province')->nullable()->after('city');
            }

            if (! Schema::hasColumn('customers', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('province');
            }

            if (! Schema::hasColumn('customers', 'country')) {
                $table->string('country')->nullable()->default('Indonesia')->after('postal_code');
            }

            if (! Schema::hasColumn('customers', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('customers', 'website')) {
                $table->string('website')->nullable()->after('email');
            }

            if (! Schema::hasColumn('customers', 'job_title')) {
                $table->string('job_title')->nullable()->after('full_name');
            }

            if (! Schema::hasColumn('customers', 'industry')) {
                $table->string('industry')->nullable()->after('company_name');
            }

            if (! Schema::hasColumn('customers', 'customer_type')) {
                $table->string('customer_type')->nullable()->after('status');
            }

            if (! Schema::hasColumn('customers', 'source')) {
                $table->string('source')->nullable()->after('customer_type');
            }

            if (! Schema::hasColumn('customers', 'lead_score')) {
                $table->unsignedSmallInteger('lead_score')->nullable()->after('source');
            }

            if (! Schema::hasColumn('customers', 'preferred_contact_method')) {
                $table->string('preferred_contact_method')->nullable()->after('lead_score');
            }

            if (! Schema::hasColumn('customers', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable()->after('preferred_contact_method');
            }

            if (! Schema::hasColumn('customers', 'next_follow_up_at')) {
                $table->timestamp('next_follow_up_at')->nullable()->after('last_contacted_at');
            }

            if (! Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable()->after('next_follow_up_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            foreach ([
                'identity_number',
                'tax_number',
                'gender',
                'birth_date',
                'address',
                'city',
                'province',
                'postal_code',
                'country',
                'whatsapp',
                'website',
                'job_title',
                'industry',
                'customer_type',
                'source',
                'lead_score',
                'preferred_contact_method',
                'last_contacted_at',
                'next_follow_up_at',
                'notes',
            ] as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
