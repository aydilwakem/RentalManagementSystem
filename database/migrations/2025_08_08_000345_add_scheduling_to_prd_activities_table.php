<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prd_activities', function (Blueprint $table) {
            $table->enum('schedule_type', ['no_schedule', 'system', 'guest'])->default('no_schedule')->after('inclusions');
            $table->json('available_times')->nullable()->after('schedule_type');
        });
    }

    public function down()
    {
        Schema::table('prd_activities', function (Blueprint $table) {
            $table->dropColumn(['schedule_type', 'available_times']);
        });
    }
};
