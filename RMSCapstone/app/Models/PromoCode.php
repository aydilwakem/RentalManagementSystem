<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PromoCode extends Model
{
    use SoftDeletes;
    use LogsActivity; 

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

    // ------------------ Activity Logs ----------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([ 
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
            'property_category_id'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Promo Code has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Promo Code');
    }

    // ------------------ Relationships ------------------ //
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
