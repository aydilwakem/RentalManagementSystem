<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class ReservationType extends Model
{
    protected $table = 'trn_reservation_type';

    protected $fillable = ['name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'reservation_type_id');
    }
}
