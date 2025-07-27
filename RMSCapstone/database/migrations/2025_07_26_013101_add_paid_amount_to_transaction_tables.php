<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaction_services', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0.00)->after('amount');
        });

        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0.00)->after('amount');
        });

        Schema::table('trn_guest_pets', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0.00)->after('total_fee');
        });

        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0.00)->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_services', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });

        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });

        Schema::table('trn_guest_pets', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });

        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
