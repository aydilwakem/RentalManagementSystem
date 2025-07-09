<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $table = 'promo_codes';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_uses',
        'uses_count',
        'per_user_limit',
        'min_booking_amount',
        'start_date',
        'end_date',
        'duration_days',
        'has_expiration',
        'is_active',
        'property_category_id',
    ];

    protected $casts = [
        'discount_value'        => 'decimal:2',
        'min_booking_amount'    => 'decimal:2',
        'start_date'            => 'datetime',
        'end_date'              => 'datetime',
        'has_expiration'        => 'boolean',
        'is_active'             => 'boolean',
        'max_uses'              => 'integer',
        'use_count'             => 'integer',
    ];


    /**
     * Get the property category this promo applies to.
     */
    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'promo_id');
    }
}
