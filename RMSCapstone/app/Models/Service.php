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
}
