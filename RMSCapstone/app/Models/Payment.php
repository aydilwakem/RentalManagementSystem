<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

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
        'convenience_fee'
    ];
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'convenience_fee' => 'decimal:2',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ------------------- Activity Log -------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
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
                'currency'
            ])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Payment has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Payment');
    }

    // -------------------- Relationships ------------------ //
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
