<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->unsignedTinyInteger('non_chargeable_guests')->default(0)->after('kids');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->dropColumn('non_chargeable_guests');
        });
    }
};
