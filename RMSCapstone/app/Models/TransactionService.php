<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionService extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_services';

    protected $fillable = [
        'transaction_id',
        'service_id',
        'property_id',
        'quantity',
        'service_datetime',
        'amount',
        'days',
        'paid_amount',
        'payment_status',
        'status',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'service_datetime' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    // Relationships
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
