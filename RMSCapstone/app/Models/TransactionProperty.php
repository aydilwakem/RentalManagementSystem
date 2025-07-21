<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProperty extends Model
{
    protected $table = 'transaction_properties';

    protected $fillable = [
        'transaction_id',
        'property_id',
        'adults',
        'kids',
        'extra_guest',
        'extra_charge',
        'amount',
        'total_amount',
        'days',
        'payment_status',
        'paid_at',
        'remarks',
    ];

    public function guests()
    {
        return $this->hasMany(GuestDetail::class, 'transaction_property_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
