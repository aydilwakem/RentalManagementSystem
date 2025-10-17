<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->foreignId('day_tour_id')->nullable()->constrained('day_tours');
            $table->foreignId('day_tour_rate_id')->nullable()->constrained('day_tour_rates');
            $table->enum('day_tour_rate_type', ['with_room', 'without_room'])->nullable();
        });
    }


    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropForeign(['day_tour_id']);
            $table->dropForeign(['day_tour_rate_id']);
            $table->dropColumn(['day_tour_id', 'day_tour_rate_id', 'day_tour_rate_type']);
        });
    }
};
