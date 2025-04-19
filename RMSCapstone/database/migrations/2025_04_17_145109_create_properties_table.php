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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Room, House, Event_Hall 
            $table->unsignedBigInteger('property_type_id');

            // Category for Rooms or Houses and Event Halls (if applicable)
            $table->unsignedBigInteger('property_category_id');

            // Fields (some can be nullable)
            $table->string('name_number');
            $table->string('ideal_guest')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('max_adults')->nullable();
            $table->integer('max_kids')->nullable();
            $table->integer('turnover_duration')->nullable();
            $table->enum('property_status', ['available', 'booked', 'out_of_service'])->default('available');
            $table->string('house_number')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Philippines');
            $table->decimal('amount', 10, 2);
            $table->decimal('extra_charge_per_hour', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            // Foreign key constraints
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
            $table->foreign('property_category_id')->references('id')->on('property_categories')->onDelete('cascade');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
