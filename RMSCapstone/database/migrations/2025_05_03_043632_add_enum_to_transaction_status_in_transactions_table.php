<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change the enum column to include the new value
        DB::statement("ALTER TABLE trn_transactions 
            MODIFY COLUMN transaction_status 
            ENUM('pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing', 'done', 'no_show', 'terminated', 'expired', 'cancelled')
            NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the column to remove the added value
        DB::statement("ALTER TABLE trn_transactions 
            MODIFY COLUMN transaction_status 
            ENUM('pending', 'reserved', 'confirmed', 'ongoing', 'done', 'no_show', 'terminated', 'expired')
            NOT NULL");
    }
};
