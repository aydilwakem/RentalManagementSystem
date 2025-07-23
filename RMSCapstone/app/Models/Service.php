<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

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
                'quantity',
                'amount',
                'service_datetime',
                'status',
            )
            ->withTimestamps();
    }
}
