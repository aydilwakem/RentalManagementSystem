<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trn_users', function (Blueprint $table) {
            $table->enum('trn_user_type', ['tenant', 'guest'])->after('id'); // change 'id' to appropriate column
        });
    }

    public function down(): void
    {
        Schema::table('trn_users', function (Blueprint $table) {
            $table->dropColumn('trn_user_type');
        });
    }
};
