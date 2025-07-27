<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePetBreedColumnOnTrnGuestPets extends Migration
{
    public function up()
    {
        Schema::table('trn_guest_pets', function (Blueprint $table) {
            $table->renameColumn('pet_breed', 'breed');
            $table->string('breed')->change();
        });
    }

    public function down()
    {
        Schema::table('trn_guest_pets', function (Blueprint $table) {
            $table->renameColumn('breed', 'pet_breed');
            $table->json('pet_breed')->change();
        });
    }
};
