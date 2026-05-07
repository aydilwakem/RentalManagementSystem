<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('day_tour_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_tour_id')->constrained()->onDelete('cascade');
            $table->string('rate_name'); // e.g., "Weekday Rate", "Weekend Rate", "Holiday Rate"
            $table->enum('rate_type', ['with_room', 'without_room'])->default('without_room');
            $table->enum('day_type', ['weekday', 'weekend', 'holiday'])->default('weekday');
            $table->decimal('adult_rate', 10, 2)->default(0);
            $table->decimal('kid_rate', 10, 2)->default(0);
            $table->integer('min_guests')->default(1);
            $table->integer('max_guests')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Additional notes about this rate
            $table->softDeletes();
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['day_tour_id', 'rate_type', 'day_type'], 'day_tour_rate_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('day_tour_rates');
    }
};