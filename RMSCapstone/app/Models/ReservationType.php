<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationType extends Model
{
    use HasFactory; 
    protected $table = 'trn_reservation_type';

    protected $fillable = ['name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'reservation_type_id');
    }
}
