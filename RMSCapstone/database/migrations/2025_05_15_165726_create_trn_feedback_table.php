<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trn_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->dateTime('submitted_at');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->references('id')->on('trn_transactions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trn_feedback');
    }
};
