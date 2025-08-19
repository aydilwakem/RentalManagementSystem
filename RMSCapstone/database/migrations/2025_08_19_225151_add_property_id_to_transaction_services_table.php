<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_services', function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->nullable()->after('service_id');

            // If you have a properties table and want to enforce referential integrity:
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_services', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });
    }
};
