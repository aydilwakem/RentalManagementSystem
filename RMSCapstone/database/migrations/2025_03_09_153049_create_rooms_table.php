<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prd_rooms', function (Blueprint $table) {
            $table->id('id'); // Primary Key
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('room_category_id')->nullable();
            $table->integer('ideal_guest')->nullable();
            $table->integer('max_adults')->nullable();
            $table->integer('max_kids')->nullable();
            $table->integer('turnover_duration')->nullable();
            $table->enum('room_status', ['Available', 'Booked', 'Out of Service'])->default('Available');
            $table->string('image')->nullable();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('room_category_id')->references('id')->on('prd_room_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prd_rooms');
    }
};
