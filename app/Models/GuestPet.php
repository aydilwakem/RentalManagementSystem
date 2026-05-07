<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestPet extends Model
{
    protected $table = 'trn_guest_pets';

    protected $fillable = [
        'transaction_id',
        'breed',
        'pet_count',
        'nights_stayed',
        'total_fee',
        'vaccination_card',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getTotalPetsAttribute(): int
    {
        return $this->guestPets->sum('pet_count');
    }
}
