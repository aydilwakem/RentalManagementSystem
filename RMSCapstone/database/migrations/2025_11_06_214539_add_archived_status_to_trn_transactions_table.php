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
            DB::statement("ALTER TABLE trn_transactions MODIFY COLUMN transaction_status ENUM('pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing', 'done', 'no_show', 'terminated', 'expired', 'cancelled', 'archived') NOT NULL");
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            DB::statement("ALTER TABLE trn_transactions MODIFY COLUMN transaction_status ENUM('pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing', 'done', 'no_show', 'terminated', 'expired', 'cancelled') NOT NULL");
        
        

    }
};