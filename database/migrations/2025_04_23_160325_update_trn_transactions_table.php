<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrnTransactionsTable extends Migration
{
    public function up()
    {
        // Update transaction_status enum
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->enum('transaction_status', [
                'pending',
                'reserved',
                'confirmed',
                'ongoing',
                'done',
                'no_show',
                'terminated',
                'expired'
            ])->default('pending')->change();
        });

        // Add new columns for datetime fields
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->dateTime('actual_start_datetime')->nullable();
            $table->dateTime('actual_end_datetime')->nullable();
        });

        // Add foreign key for event_type_id to link to event_types table
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('event_type_id')->nullable()->after('created_by');
            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('set null');
        });
    }

    public function down()
    {
        // Reverse the changes in case of rollback
        Schema::table('trn_transactions', function (Blueprint $table) {
            // Revert the enum change back to original values
            $table->enum('transaction_status', ['pending', 'confirmed'])->default('pending')->change();

            // Remove the new datetime columns
            $table->dropColumn(['start_datetime', 'end_datetime', 'actual_start_datetime', 'actual_end_datetime']);

            // Drop the foreign key and column for event_type_id
            $table->dropForeign(['event_type_id']);
            $table->dropColumn('event_type_id');
        });
    }
}
