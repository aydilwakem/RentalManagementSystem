<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PropertyFeature extends Model
{
    use SoftDeletes;
    use HasFactory; 
    use LogsActivity; 

    protected $fillable = ['name', 
    'property_type_id'];

    // ------------------- Activity Logs ---------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name',  'property_type_id'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Property Feature has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Property Feature');
    }

    
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_features_pivot');
    }

    public function propertyType(){
        return $this->belongsTo(PropertyType::class);
    }
}
