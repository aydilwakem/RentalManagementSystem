<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'trn_invoice';

    protected $fillable = [
        'transaction_id',
        'invoice_number',
        'invoice_type',
        'total_amount',
        'payment_status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Invoice::class, 'transaction_id');
    }

    // An invoice can have many payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
