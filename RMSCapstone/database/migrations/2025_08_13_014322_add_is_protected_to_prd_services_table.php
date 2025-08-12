<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prd_services', function (Blueprint $table) {
            $table->boolean('is_protected')->default(false)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('prd_services', function (Blueprint $table) {
            $table->dropColumn('is_protected');
        });
    }
};
