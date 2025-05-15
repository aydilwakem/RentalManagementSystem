<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{

    protected $table = 'trn_receipts';

    protected $fillable = [
        'invoice_id',
        'receipt_number',
        'amount_received',
        'notes',
        'receipt_date',
    ];

    protected $casts = [
        'receipt_date' => 'datetime',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
