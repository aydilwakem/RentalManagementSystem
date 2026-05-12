<?php
// database/migrations/2024_xx_xx_xxxxxx_add_paymongo_url_to_trn_transactions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            // 🟢 ITO LANG - yung PayMongo checkout link
            $table->text('paymongo_checkout_url')->nullable()->after('transaction_status');
        });
    }

    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropColumn('paymongo_checkout_url');
        });
    }
};
