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
        Schema::create('prd_room_rates', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('room_id');
            $table->string('name', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('extra_person_charge', 10, 2)->default(0);
            $table->decimal('extended_stay_charge_per_hr', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('rate_type', ['Weekend', 'Weekdays', 'Holiday', 'Peak']);
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('prd_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_rates');
    }
};
