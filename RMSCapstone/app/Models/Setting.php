<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'st_settings';

    protected $fillable = [
        'company_name',
        'logo',
        'email',
        'contact_number',
        'address',
        'facebook',
        'instagram',
        'terms_and_conditions',
        'privacy_policy',
        'refund_policy',
        'rental_agreement',
        'custom_css',
        'custom_js',
        'enable_deposit_percentage',
        'deposit_percentage',
        'room_payment_proof_expiration_hours',
        'event_payment_proof_expiration_hours',
        'day_tour_payment_proof_expiration_hours'
    ];

    protected $casts = [
        'enable_deposit_percentage' => 'boolean',
    ];

    // ------------------- Activity Logs ------------ //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([ 
                'company_name',
                'logo',
                'email',
                'contact_number',
                'address',
                'facebook',
                'instagram',
                'terms_and_conditions',
                'privacy_policy',
                'refund_policy',
                'rental_agreement',
                'custom_css',
                'custom_js',
                'enable_deposit_percentage',
                'deposit_percentage',
                'room_payment_proof_expiration_hours',
                'event_payment_proof_expiration_hours',
                'day_tour_payment_proof_expiration_hours'
            ])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Branding/Settings has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Branding/Settings');
    }

}
