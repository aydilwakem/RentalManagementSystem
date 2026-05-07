<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Receipt extends Model
{
    use LogsActivity;

    protected $table = 'trn_receipts';

    protected $fillable = [
        'invoice_id',
        'receipt_number',
        'amount_received',
        'notes',
        'receipt_date',
    ];

    protected $casts = [
        'receipt_date' => 'datetime',
    ];

    // ---------------------- Activity Logs ---------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
                'invoice_id',
                'receipt_number',
                'amount_received',
                'notes',
                'receipt_date'
            ])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Receipt has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Receipt');
    }

    // ---------------------- Relationships ----------------------- //
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
