<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('activity_id');

            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('trn_transactions')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('prd_activities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_activities');
    }
};
