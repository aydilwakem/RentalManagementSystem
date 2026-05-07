<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_guest_details', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_property_id')->nullable()->after('transaction_id');

            $table->foreign('transaction_property_id')
                ->references('id')
                ->on('transaction_properties')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('trn_guest_details', function (Blueprint $table) {
            $table->dropForeign(['transaction_property_id']);
            $table->dropColumn('transaction_property_id');
        });
    }
};
