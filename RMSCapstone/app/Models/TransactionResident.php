<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionResident extends Model
{
    protected $table = 'trn_residents'; // Ensure it matches the migration

    protected $fillable = [
        'transaction_id',
        'name',
        'residency_status',
        'residency',
        'country',
        'demographic',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
