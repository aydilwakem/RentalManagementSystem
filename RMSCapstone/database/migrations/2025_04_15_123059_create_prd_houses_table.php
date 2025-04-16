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
        Schema::create('prd_houses', function (Blueprint $table) {
            $table->id(); // Primary Key

            $table->string('name_number'); // e.g., House A1, Unit 101
            $table->unsignedBigInteger('house_category_id'); // Foreign Key

            $table->text('description')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->enum('availability', ['available', 'unavailable'])->default('available');

            $table->string('house_number')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality');
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Philippines');

            $table->string('image')->nullable(); // Image path or URL

            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at

            // Foreign Key Constraint
            $table->foreign('house_category_id')->references('id')->on('house_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prd_houses');
    }
};
