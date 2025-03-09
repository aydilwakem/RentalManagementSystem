<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prd_rooms', function (Blueprint $table) {
            $table->id('id'); // Primary Key
            $table->string('name', 100);
            $table->unsignedBigInteger('room_category_id'); // Foreign Key reference
            $table->integer('ideal_guest');
            $table->integer('max_adults');
            $table->integer('max_kids');
            $table->integer('turnover_duration');
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
