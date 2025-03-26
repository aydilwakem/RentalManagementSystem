<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lt_houses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Property name
            $table->unsignedBigInteger('house_category_id')->nullable(); // Foreign key
            $table->text('description')->nullable();
            $table->decimal('monthly_rent', 10, 2); // Rent amount
            $table->enum('availability', ['available', 'unavailable'])->default('available');
            // Address Fields
            $table->string('house_number', 20);
            $table->string('street', 150);
            $table->string('barangay', 100);
            $table->string('city_municipality', 100);
            $table->string('province', 100);
            $table->string('region', 50);
            $table->string('postal_code', 10);
            $table->string('country', 50);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lt_houses');
    }
};
