<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'trn_payments';

    protected $fillable = [
        'invoice_id',
        'payment_method_id',
        'amount_paid',
        'payment_type',
        'payment_screenshot',
        'payment_reference_number',
        'payment_date',
        'payment_status',
        'notes',
        'paid_at',
        'currency',
    ];
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // A payment belongs to an invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // A payment uses a payment method
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
