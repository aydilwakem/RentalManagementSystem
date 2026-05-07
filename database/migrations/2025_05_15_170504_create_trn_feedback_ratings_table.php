<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trn_feedback_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feedback_id');
            $table->unsignedBigInteger('rating_type_id');
            $table->integer('rating_value');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('feedback_id')->references('id')->on('trn_feedback')->onDelete('cascade');
            $table->foreign('rating_type_id')->references('id')->on('trn_feedback_rating_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trn_feedback_ratings');
    }
};
