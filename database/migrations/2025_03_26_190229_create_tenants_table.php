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
            $table->unsignedBigInteger('house_id')->nullable(); // Foreign key
            // Contact Information
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            // Additional Information
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable(); // Male, Female, Other
            $table->string('occupation')->nullable();
            $table->text('notes')->nullable(); // Additional remarks
            $table->softDeletes();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('house_id')->references('id')->on('lt_houses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lt_tenants');
    }
};
