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
        'sub_total',
        'deposit_paid',
        'amount_paid',
        'balance_due',
        'due_date',
        'invoice_status',
        'completed_at',
    ];

    protected $casts = [
        'sub_total'     => 'decimal:2',
        'deposit_paid'  => 'decimal:2',
        'amount_paid'   => 'decimal:2',
        'balance_due'   => 'decimal:2',
        'due_date'      => 'date',
        'completed_at'  => 'datetime',
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
