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
        Schema::create('room_category_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_category_id')->constrained('prd_room_categories')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('prd_amenities')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_category_amenity');
    }
};
