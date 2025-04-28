<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ReservationType;
use App\Models\GuestDetail;
use App\Models\TransactionUser;
use App\Models\Property;
use App\Models\Invoice;
use App\Models\EventType;



class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trn_transactions';

    protected $fillable = [
        'reservation_type_id',
        'created_by',
        'event_type_id',
        'total_adults',
        'total_kids',
        'pax',
        'total_amount',
        'terms',
        'heard_from',
        'reservation_source',
        'transaction_status',
        'actual_start_datetime',
        'actual_end_datetime',
        'start_datetime',
        'end_datetime'
    ];


    // Automatically convert attributes to specific data types when retrieving or setting them
    protected $casts = [
        'total_amount' => 'decimal:2',
        'actual_start_datetime' => 'datetime',
        'actual_end_datetime' => 'datetime',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];



    //  ------------------------------ RELATIONSHIPS --------------------------------- //

    // One Transaction belongs to One Reservation Type
    public function reservationType()
    {
        return $this->belongsTo(ReservationType::class, 'reservation_type_id');
    }

    public function event_type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }


    // One transaction belongs to one Transaction User
    public function transactionUser()
    {
        return $this->belongsTo(TransactionUser::class, 'created_by');
    }

    // One transaction has many Invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }

    // One transaction has many Guest Details
    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'transaction_id');
    }



    // One transaction can have many properties
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'transaction_properties')
            ->withPivot('adults', 'kids')
            ->withTimestamps();
    }

    // One transaction can have many activities
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'transaction_activities')
            ->withPivot('quantity')
            ->withTimestamps();
    }




    //  ------------------------------ SCOPES --------------------------------- //

    // public function scopeSearch($query, $search)
    // {
    //     $query->where('total_adults', 'like', "%{$search}%")->where('total_pax', 'like', "%{$search}%");
    // }


    public function scopeNewReservations($query)
    {
        return $query->where('transaction_status', 'pending');
    }
}
