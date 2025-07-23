<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestPet extends Model
{
    protected $table = 'trn_guest_pets';

    protected $fillable = [
        'transaction_id',
        'pet_breed',
        'pet_count',
        'nights_stayed',
        'total_fee',
        'vaccination_card',
    ];

    protected $casts = [
        'pet_breed' => 'array',
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
