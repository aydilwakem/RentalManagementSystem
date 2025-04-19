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
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable()->after('id');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('trn_transactions')
                ->onDelete('cascade'); // or 'cascade', 'restrict', etc.

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};
