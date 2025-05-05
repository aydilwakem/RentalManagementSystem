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
        Schema::table('st_settings', function (Blueprint $table) {
            $table->decimal('deposit_percentage', 5, 2)->default(50); // Default to 50% if no value is set
        });
    }

    public function down()
    {
        Schema::table('st_settings', function (Blueprint $table) {
            $table->dropColumn('deposit_percentage');
        });
    }
};
