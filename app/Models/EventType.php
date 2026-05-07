<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EventType extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity; 

    protected $fillable = ['name', 'description', 'image'];

    // ---------------------- Activity Log ------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name', 'description', 'image'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Event Type has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Event Type');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'event_type_id');
    }

    public function scopeSearch($query, $search)
    {
       $search = trim($search);

        if ($search === '') {
            return $query;
        }

        return $query->where('name', 'like', '%' . $search . '%');
    }
}
