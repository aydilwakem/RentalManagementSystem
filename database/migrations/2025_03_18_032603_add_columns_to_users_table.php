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
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->after('middle_name');
            $table->string('suffix', 10)->nullable()->after('last_name');
            $table->string('contact_number', 20)->nullable()->after('suffix');
            $table->string('house_number', 20)->nullable()->after('contact_number');
            $table->string('street', 150)->nullable()->after('house_number');
            $table->string('barangay', 100)->nullable()->after('street');
            $table->string('city_municipality', 100)->nullable()->after('barangay');
            $table->string('province', 100)->nullable()->after('city_municipality');
            $table->string('region', 50)->nullable()->after('province');
            $table->string('postal_code', 10)->nullable()->after('region');
            $table->string('country', 50)->nullable()->after('postal_code');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'contact_number',
                'house_number',
                'street',
                'barangay',
                'city_municipality',
                'province',
                'region',
                'postal_code',
                'country',
                'status'
            ]);
        });
    }
};
