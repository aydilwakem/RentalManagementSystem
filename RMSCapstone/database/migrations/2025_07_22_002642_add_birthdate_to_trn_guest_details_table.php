<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_guest_details', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable()->after('suffix');
        });
    }

    public function down()
    {
        Schema::table('trn_guest_details', function (Blueprint $table) {
            $table->dropColumn('age');
        });
    }
};
