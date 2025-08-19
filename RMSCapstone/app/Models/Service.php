<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'prd_services';
    protected $fillable = [
        'name',
        'description',
        'amount',
        'unit',
        'type',
        'is_active',
    ];

    protected $cast = [
        'amount' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_services')
            ->withPivot(
                'service_id',
                'property_id',
                'quantity',
                'days',
                'amount',
                'service_datetime',
                'status',
            )
            ->withTimestamps();
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
