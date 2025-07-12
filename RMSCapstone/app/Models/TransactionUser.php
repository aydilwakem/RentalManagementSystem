<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionUser extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity; 

    protected $table = 'trn_users';

    protected $fillable = [
        'trn_user_type',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'contact_number',
        'company_name',
        'house_number',
        'street',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'postal_code',
        'country',
    ];

    // -------------------- Activity Log ----------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['trn_user_type',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'email',
            'contact_number',
            'company_name',
            'house_number',
            'street',
            'barangay',
            'city_municipality',
            'province',
            'region',
            'postal_code',
            'country'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Transaction User has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Transaction User');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }
}
