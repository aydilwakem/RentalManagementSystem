<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomRate extends Model
{
    use SoftDeletes;

    protected $table = 'property_rates';

    protected $fillable = [
        'name',
        'property_id',
        'start_date',
        'end_date',
        'amount',
        'description',
        'rate_type',
        'freebies',
        'priority',
        'is_active',
        'min_stay_nights',
        'max_stay_nights',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the room associated with this rate.
     */

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }



    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%");
    }
}
