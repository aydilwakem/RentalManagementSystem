<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ReservationType;
use App\Models\GuestDetail;
use App\Models\TransactionUser;


class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trn_transactions';

    protected $fillable = [
        'reservation_type_id',
        'reservation_id',
        'created_by',

        'room_id',
        'check_in_time',
        'check_out_time',
        'check_in_date',
        'check_out_date',
        'total_adults',
        'total_kids',
        'pax',
        'activity_id',
        'total_amount',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'contact_number',
        'house_number',
        'street',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'postal_code',
        'country',
        'total_females',
        'total_males',
        'total_infants',
        'total_people',
        'terms',
        'pets',
        'payment_method_id',
        'payment_screenshot',
        'payment_reference_number',
        'isPaid',
        'isReserved',
        'isConfirmed',
        'actual_check_in_time',
        'actual_check_out_time',
        'actual_check_in_date',
        'actual_check_out_date'
    ];


    // Automatically convert attributes to specific data types when retrieving or setting them
    protected $casts = [
        'check_in_date' => 'date:Y-m-d', // Ensure it's stored/displayed correctly
        'check_out_date' => 'date:Y-m-d',
        'actual_check_in_date' => 'date:Y-m-d',
        'actual_check_out_date' => 'date:Y-m-d',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'actual_check_in_time' => 'datetime:H:i:s',
        'actual_check_out_time' => 'datetime:H:i:s',
        'total_amount' => 'decimal:2',
        'isPaid' => 'boolean',
        'isReserved' => 'boolean',
        'isConfirmed' => 'boolean',
        'terms' => 'boolean',
    ];

    /**
     * Relationships
     */


    // One Transaction belongs to One Reservation Type
    public function reservationType()
    {
        return $this->belongsTo(ReservationType::class, 'reservation_type_id');
    }

    // One Transaction belongs to One Activity - revise this to one to many
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    // One transaction belongs to one Transaction User
    public function transactionUser()
    {
        return $this->belongsTo(TransactionUser::class, 'created_by');
    }

    // One transaction has many Guest Details
    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'transaction_id');
    }

    public function reservation()
    {
        return $this->morphTo(); // Polymorphic relation to Room, House, or Event
    }

    // Polymorphic Relationships
    public function room()
    {
        return $this->belongsTo(Room::class, 'reservation_id');
    }

    public function house()
    {
        return $this->belongsTo(Property::class, 'reservation_id');
    }

    public function event_halls()
    {
        return $this->belongsTo(EventHall::class, 'reservation_id');
    }




    // To be removed
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function residents()
    {
        return $this->hasMany(TransactionResident::class, 'transaction_id');
    }




    public function scopeSearch($query, $search)
    {
        $query->where('first_name', 'like', "%{$search}%")->where('last_name', 'like', "%{$search}%");
    }


    public function scopeNewReservations($query)
    {
        return $query
            ->where('isReserved', false)
            ->where('isConfirmed', false);
    }
}
