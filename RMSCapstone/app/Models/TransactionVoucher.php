<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'voucher_type',
        'voucher_amount',
    ];

    // Relationship to transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
