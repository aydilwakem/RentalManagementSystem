<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RoomRate extends Model
{
    use SoftDeletes;
    use LogsActivity;

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
        'freebies' => 'boolean',
    ];

    // --------------------------- Activity Logs --------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
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
                'max_stay_nights'])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Room Rate has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Room Rate');
    }

    // --------------------------- Relationships --------------------- //
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
