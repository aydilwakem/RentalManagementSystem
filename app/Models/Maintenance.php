<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Maintenance extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity; 

    protected $table = 'mnt_maintenance';

    protected $fillable = ['name', 
    'description', 
    'property_id', 
    'reported_at', 
    'resolved_at', 
    'planned_datetime', 
    'priority_status', 
    'routine_datetime',
    'maintenance_images',
    'resolved_images'];

    protected $casts = [
        'reported_at' => 'date:Y-m-d',
        'resolved_at' => 'date:Y-m-d',
        'maintenance_images' => 'array',
        'resolved_images' => 'array',
    ];

    // ---------------------- Activity Logs ----------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name', 
                'description', 
                'property_id', 
                'reported_at', 
                'resolved_at', 
                'planned_datetime', 
                'priority_status'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Maintenance has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Maintenance');
    }

    // ---------------------- Relationships ----------------- //
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function scopeSearch($query, $search)
    {
        $search = trim($search);

        if ($search === '') {
            return $query;
        }

        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeFinishedMaintenances($query)
    {
        return $query->whereNotNull('resolved_at');
    }


    public function scopePendingMaintenances($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function setResolvedAtAttribute($value)
    {
        $this->attributes['resolved_at'] = $value ?: null;
    }
}
