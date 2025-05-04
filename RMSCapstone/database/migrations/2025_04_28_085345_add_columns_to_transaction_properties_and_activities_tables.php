<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adding columns to transaction_properties table
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->integer('days')->nullable()->after('kids');
            $table->integer('extra_guest')->nullable()->after('days');
            $table->decimal('extra_charge', 10, 2)->nullable()->after('extra_guest');
            $table->decimal('amount', 10, 2)->nullable()->after('extra_charge');
            $table->decimal('total_amount', 10, 2)->nullable()->after('amount');
        });

        // Adding columns to transaction_activities table
        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->dateTime('activity_datetime')->nullable()->after('quantity');
            $table->decimal('amount', 10, 2)->nullable()->after('activity_datetime');
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('pending')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping columns from transaction_properties table
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->dropColumn([
                'extra_guest',
                'extra_charge',
                'amount',
                'days',
                'total_amount',
            ]);
        });

        // Dropping columns from transaction_activities table
        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'activity_datetime',
                'status',
            ]);
        });
    }
};
