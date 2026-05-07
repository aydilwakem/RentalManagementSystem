<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentMethod extends Model
{
  use HasFactory;
  use SoftDeletes;
  use LogsActivity;

  protected $table = 'pm_payment_methods';

  protected $fillable =
  [
    'mode_of_payment_name',
    'account_name',
    'account_number',
    'mode_of_payment_qr_image',
  ];

  // ------------------- Activity Logs --------------------- //
  protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['mode_of_payment_name',
              'account_name',
              'account_number',
              'mode_of_payment_qr_image'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Payment Method has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Payment Method');
    }

  // ------------------------ Relationships ----------------- //

  public function scopeSearch($query, $search)
  {
    $query->where('mode_of_payment_name', 'like', "%{$search}%")->orWhere('account_name', 'like', "%{$search}%");
  }

  // A payment method can have many payments
  public function payments()
  {
    return $this->hasMany(Payment::class, 'payment_method_id');
  }
}
