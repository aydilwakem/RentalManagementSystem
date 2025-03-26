<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lt_tenants', function (Blueprint $table) {
            $table->id(); // Primary Key

            // Full Name
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable(); // e.g., Jr., Sr., III

            // Contact Information
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            // Address Information
            $table->string('house_number')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality');
            $table->string('province');
            $table->string('region')->nullable();
            $table->string('postal_code');
            $table->string('country')->default('Philippines');

            // Additional Information
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable(); // Male, Female, Other
            $table->string('occupation')->nullable();
            $table->text('notes')->nullable(); // Additional remarks

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lt_tenants');
    }
};
