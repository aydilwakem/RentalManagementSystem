<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends SpatieRole
{
    use LogsActivity;



    // ----------------- Activity Logs --------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['name', 'guard_name'])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Role has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Role');
    }
}
