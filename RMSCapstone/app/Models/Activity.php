<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    protected $table = 'prd_activities';

    protected $fillable = ['name', 'description', 'amount', 'inclusions', 'images'];

    protected $casts = [
        'amount' => 'decimal:2',
        'images' => 'array',           // Automatically decode JSON to array

    ];

    // ----------------- Activity Logs --------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name', 'description', 'amount', 'inclusions', 'image'])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Activity has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Activity');
    }

    // --------------------- Relationships ----------------- //

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_activities')
            ->withPivot(
                'quantity',
                'amount',
                'activity_datetime',
                'status',
            )
            ->withTimestamps();
    }

    public function scopeAvailableActivities($query)
    {
        return $query->where('amount', '!=', 0);
    }
}
