<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This approach creates a temporary column, copies data, then renames
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->enum('payment_type_temp', [
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
            ])->nullable()->after('payment_type');
        });

        // Copy data from old column to new column
        DB::table('trn_payments')->update([
            'payment_type_temp' => DB::raw('payment_type')
        ]);

        // Remove old column and rename new column
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->renameColumn('payment_type_temp', 'payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->enum('payment_type_old', [
                'Room Rent',
                'House Rent', 
                'Activity Fee',
                'Event Hall',
                'Event Package',
                'Security Deposit',
                'Remaining Balance',
                'Merchandise'
            ])->nullable()->after('payment_type');
        });

        // Copy data back
        DB::table('trn_payments')->update([
            'payment_type_old' => DB::raw('payment_type')
        ]);

        // Remove new column and rename old column back
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->renameColumn('payment_type_old', 'payment_type');
        });
    }
};