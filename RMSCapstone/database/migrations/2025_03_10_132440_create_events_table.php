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
        Schema::create('prd_events', function (Blueprint $table) {
            $table->id(); //Primary Key
            $table->string('name', 255);
            $table->unsignedBigInteger('event_category_id'); // Foreign Key reference
            $table->unsignedBigInteger('event_hall_id'); // Foreign Key reference
            $table->string('company_name', 255);
            $table->string('contact_person', 255);
            $table->string('email', 255);
            $table->date('event_date_start');
            $table->date('event_date_end');
            $table->time('event_time');
            $table->integer('capacity');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['confirmed', 'on-going', 'cancelled'])->default('confirmed');
            $table->text('requests')->nullable();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('event_category_id')->references('id')->on('prd_event_categories');
            $table->foreign('event_hall_id')->references('id')->on('prd_event_halls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
