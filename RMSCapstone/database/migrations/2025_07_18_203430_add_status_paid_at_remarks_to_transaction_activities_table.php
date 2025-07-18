<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('amount');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->text('remarks')->nullable()->after('paid_at');
        });
    }

    public function down()
    {
        Schema::table('transaction_activities', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_at', 'remarks']);
        });
    }
};
