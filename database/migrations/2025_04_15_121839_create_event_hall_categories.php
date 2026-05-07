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
        Schema::create('event_hall_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_hall_id');
            $table->unsignedBigInteger('event_category_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_hall_id')->references('id')->on('prd_event_halls');
            $table->foreign('event_category_id')->references('id')->on('prd_event_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_hall_categories');
    }
};
