<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TransactionActivity extends Model
{

    protected $table = 'transaction_activities';

    protected $fillable = [
        'transaction_id',
        'activity_id',
        'quantity',
        'activity_datetime',
        'amount',
        'paid_amount',
        'payment_status',
        'status',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'activity_datetime' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
