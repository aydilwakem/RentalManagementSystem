<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE trn_transactions MODIFY COLUMN reservation_source
            ENUM('Website', 'AirBnb', 'Facebook Messenger', 'Instagram', 'Walk-In', 'Other')"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE trn_transactions MODIFY COLUMN reservation_source
            ENUM('Website', 'AirBnb', 'Facebook Messenger', 'Instagram', 'Walk-In', 'Other')"); 
        });
    }
};
