<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteRoomsRelatedTables extends Migration
{
    public function up()
    {
        // Drop the tables
        Schema::dropIfExists('prd_room_rates');
        Schema::dropIfExists('prd_room_categories');
        Schema::dropIfExists('prd_rooms');
    }

    public function down()
    {
        // Rollback logic: Recreate tables (Optional)

        // Recreate prd_room_categories table
        Schema::create('prd_room_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Recreate prd_room_rates table
        Schema::create('prd_room_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('extra_person_charge', 10, 2)->nullable();
            $table->decimal('extended_stay_charge_per_hr', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->enum('rate_type', ['Weekend', 'Weekdays', 'Holiday', 'Peak']);
            $table->timestamps();
        });

        // Recreate prd_rooms table
        Schema::create('prd_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('room_category_id');
            $table->integer('ideal_guest');
            $table->integer('max_adults');
            $table->integer('max_kids');
            $table->integer('turnover_duration');
            $table->enum('room_status', ['Available', 'Booked', 'Out of Service']);
            $table->decimal('base_rate', 10, 2);
            $table->string('image')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('room_category_id')->references('id')->on('prd_room_categories')->onDelete('cascade');
        });
    }
}
