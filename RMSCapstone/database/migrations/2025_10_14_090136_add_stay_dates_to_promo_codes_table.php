<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->date('stay_start_date')->nullable()->after('end_date');
            $table->date('stay_end_date')->nullable()->after('stay_start_date');
        });
    }

    public function down()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['stay_start_date', 'stay_end_date']);
        });
    }
};