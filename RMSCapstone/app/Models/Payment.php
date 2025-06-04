<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    use HasFactory; 

    protected $table = 'trn_payments';

    protected $fillable = [
        'invoice_id',
        'payment_method_id',
        'mode_of_payment',
        'amount_paid',
        'payment_type',
        'payment_screenshot',
        'payment_reference_number',
        'payment_date',
        'payment_status',
        'rejection_reason',
        'notes',
        'verified_at',
        'currency',
    ];
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
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
