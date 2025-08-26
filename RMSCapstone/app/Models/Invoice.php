<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use SoftDeletes;
    use HasFactory;

    use LogsActivity;

    protected $table = 'trn_invoice';

    protected $fillable = [
        'transaction_id',
        'invoice_number',
        'invoice_type',
        'sub_total',
        'base_subtotal',
        'total_discount',
        'requested_remaining_balance',
        'deposit_paid',
        'amount_paid',
        'balance_due',
        'due_date',
        'invoice_status',
        'completed_at',
    ];

    protected $casts = [
        'sub_total'     => 'decimal:2',
        'base_subtotal'     => 'decimal:2',
        'total_discount'     => 'decimal:2',
        'deposit_paid'  => 'decimal:2',
        'amount_paid'   => 'decimal:2',
        'balance_due'   => 'decimal:2',
        'due_date'      => 'date',
        'completed_at'  => 'datetime',
        'requested_remaining_balance' => 'boolean',
    ];

    // --------------------- Activity Logs ------------------ //
    protected static $logOnlyDirty = true; // Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
                'transaction_id',
                'invoice_number',
                'invoice_type',
                'sub_total',
                'base_subtotal',
                'total_discount',
                'requested_remaining_balance',
                'deposit_paid',
                'amount_paid',
                'balance_due',
                'due_date',
                'invoice_status',
                'completed_at'
            ])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Invoice has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Invoice');
    }

    // --------------------- Relationships ------------------ //
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    // An invoice can have many payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'invoice_id');
    }

    /**
     * Get all discounts applied to this invoice
     */
    public function discounts()
    {
        return $this->hasMany(InvoiceDiscount::class, 'invoice_id');
    }
}
