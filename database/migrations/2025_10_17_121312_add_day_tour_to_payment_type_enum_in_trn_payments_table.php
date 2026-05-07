<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDayTourToPaymentTypeEnumInTrnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Method 1: Using DB::statement to modify the enum
        DB::statement("ALTER TABLE trn_payments MODIFY COLUMN payment_type ENUM(
            'Room Rent',
            'House Rent', 
            'Activity Fee',
            'Event Hall',
            'Event Package',
            'Security Deposit',
            'Remaining Balance',
            'Merchandise',
            'Accommodation Fully Paid',
            'Accommodation Downpayment',
            'Accommodation Balance',
            'Day Tour'
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove 'Day Tour' from the enum in rollback
        DB::statement("ALTER TABLE trn_payments MODIFY COLUMN payment_type ENUM(
            'Room Rent',
            'House Rent',
            'Activity Fee',
            'Event Hall',
            'Event Package',
            'Security Deposit',
            'Remaining Balance',
            'Merchandise',
            'Accommodation Fully Paid',
            'Accommodation Downpayment',
            'Accommodation Balance'
        )");
    }
}
