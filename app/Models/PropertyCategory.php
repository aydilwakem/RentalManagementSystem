<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PropertyCategory extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity; 

    protected $fillable = ['name', 'description'];

    // -------------------- Activity Log ---------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name',  'description'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Property Category has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Property Category');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_category_id');
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class, 'property_category_id');
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
