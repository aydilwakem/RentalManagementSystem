<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{

    use SoftDeletes;

    protected $table = 'prd_events';

    protected $fillable = [
        'name',
        'event_category_id',
        'event_hall_id',
        'company_name',
        'contact_person',
        'email',
        'event_date_start',
        'event_date_end',
        'event_time',
        'capacity',
        'total_amount',
        'status',
        'requests',
    ];

    protected $casts = [
        'event_date_start' => 'date:Y-m-d',
        'event_date_end' => 'date:Y-m-d',
        'event_time' => 'datetime:H:i', // Cast event_time to 'HH:MM' format
    ];

    use LogsActivity;
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
                'name',
                'event_category_id',
                'event_hall_id',
                'company_name',
                'contact_person',
                'email',
                'event_date_start',
                'event_date_end',
                'event_time',
                'capacity',
                'total_amount',
                'status',
                'requests',
            ])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Event has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Event');
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function eventHall()
    {
        return $this->belongsTo(EventHall::class, 'event_hall_id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%")->orWhere('company_name', 'like', "%{$search}%");
    }
}
