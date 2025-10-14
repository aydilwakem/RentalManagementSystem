<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('day_tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('inclusions')->nullable(); // What's included in the tour
            $table->text('exclusions')->nullable(); // What's not included
            $table->text('terms_conditions')->nullable();
            $table->integer('duration_hours')->default(8); // Default 8 hours
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('16:00:00');
            $table->integer('max_guests')->default(50);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('images')->nullable(); // Array of image paths
            $table->string('main_image')->nullable(); // Main display image
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('day_tours');
    }
};