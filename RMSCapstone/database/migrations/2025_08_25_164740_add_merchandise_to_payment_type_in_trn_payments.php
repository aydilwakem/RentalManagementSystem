<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE trn_payments 
            MODIFY COLUMN payment_type 
            ENUM(
                'Room Rent',
                'House Rent',
                'Activity Fee',
                'Event Hall',
                'Event Package',
                'Security Deposit',
                'Remaining Balance',
                'Merchandise'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE trn_payments 
            MODIFY COLUMN payment_type 
            ENUM(
                'Room Rent',
                'House Rent',
                'Activity Fee',
                'Event Hall',
                'Event Package',
                'Security Deposit',
                'Remaining Balance'
            ) NOT NULL
        ");
    }
};
