<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // For MySQL: Alter the ENUM column to add new value
        DB::statement("ALTER TABLE trn_invoice MODIFY COLUMN invoice_type ENUM('House','Event_Hall','Room','Activity','Day_Tour')");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Remove 'Day_Tour' from the ENUM when rolling back
        DB::statement("ALTER TABLE trn_invoice MODIFY COLUMN invoice_type ENUM('House','Event_Hall','Room','Activity')");
    }
};