<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('property_rates', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('rate_type');
            $table->unsignedTinyInteger('min_stay_nights')->nullable()->after('is_active');
            $table->unsignedTinyInteger('max_stay_nights')->nullable()->after('min_stay_nights');
            $table->text('freebies')->nullable()->after('description');
            $table->unsignedTinyInteger('priority')->default(5)->after('freebies');
        });
    }

    public function down()
    {
        Schema::table('property_rates', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'min_stay_nights',
                'max_stay_nights',
                'freebies',
                'priority',
            ]);
        });
    }
};
