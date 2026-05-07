<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['property_id']);

            // Then drop the column
            $table->dropColumn('property_id');
        });
    }

    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            // Restore the column and foreign key if needed
            $table->unsignedBigInteger('property_id')->nullable();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
        });
    }
};
