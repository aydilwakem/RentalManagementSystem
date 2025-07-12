<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\GuestType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GuestDetail extends Model
{
    use SoftDeletes;
    use LogsActivity; 

    protected $table = 'trn_guest_details';

    protected $fillable = ['transaction_id', 'guest_type_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'gender', 'residency', 'country_of_origin'];

    // ---------------------- Activity Log --------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['transaction_id', 'guest_type_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'gender', 'residency', 'country_of_origin'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Guest Detail has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Guest Detail');
    }
    
    // ---------------------- Relationships --------------------- //
    // One Guest Detail belongs to one transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    // One Guest Detail belongs to one Guest Type
    public function guestType()
    {
        return $this->belongsTo(GuestType::class, 'guest_type_id');
    }
}
