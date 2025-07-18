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
use App\Models\PromoCode;
use Spatie\Activitylog\Traits\LogsActivity; // 1. Add this line to use activity Logging
use Spatie\Activitylog\LogOptions; // 2. Import LogOptions for activity logging



class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    use LogsActivity; // 3. Use the LogsActivity trait to enable activity logging

    /**
     * Summary of table
     * @var string
     * 
     * This property defines the table name associated with the Transaction model.
     */
    protected $table = 'trn_transactions';

    /**
     * Summary of fillable
     * @var array
     * 
     * This array defines the attributes that are mass assignable.
     */
    protected $fillable = [
        'transaction_number',
        'reservation_type_id',
        'created_by',
        'event_type_id',
        'promo_id',
        'total_adults',
        'total_kids',
        'pax',
        'sub_total',
        'promo_discount_amount',
        'convenience_fee',
        'total_amount',
        'deposit_amount',
        'terms',
        'heard_from',
        'reservation_source',
        'transaction_status',
        'actual_start_datetime',
        'actual_end_datetime',
        'start_datetime',
        'end_datetime'
    ];

    /**
     * Summary of casts
     * @var array
     * 
     * This array defines the data types for specific attributes in the model.
     */
    protected $casts = [

        // Decimals
        'sub_total' => 'decimal:2',
        'promo_discount_amount' => 'decimal:2',
        'convenience_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',

        // Decimal (Optional)
        'deposit_amount' => 'decimal:2',

        // Date
        'actual_start_datetime' => 'datetime',
        'actual_end_datetime' => 'datetime',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * 4. Define the activity log options
     * 
     */

    protected static $logOnlyDirty = true; // Log Only Dirty - Only attributes that have changed will be logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['transaction_number', 'reservation_type_id', 'created_by', 'event_type_id', 'promo_id', 'total_adults', 'total_kids', 'pax', 'total_amount', 'deposit_amount', 'terms', 'heard_from', 'reservation_source', 'transaction_status', 'actual_start_datetime', 'actual_end_datetime', 'start_datetime', 'end_datetime'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Transaction has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Transaction Model
            ->useLogName('Transaction');
    }

    //  ------------------------ RELATIONSHIPS -------------------------------- //

    /**
     * Summary of reservationType
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<ReservationType, Transaction>
     * 
     * This method defines a relationship where one transaction belongs to one reservation type.
     * It allows you to access the reservation type associated with a transaction.
     * 
     * For example, you can use `$transaction->reservationType` to get the reservation type of a transaction.
     */
    public function reservationType()
    {
        return $this->belongsTo(ReservationType::class, 'reservation_type_id');
    }

    /**
     * Summary of event_type
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<EventType, Transaction>
     * 
     * This method defines a relationship where one transaction belongs to one event type.
     * It allows you to access the event type associated with a transaction.
     * 
     * For example, you can use `$transaction->event_type` to get the event type of a transaction.
     * 
     */

    public function event_type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    /**
     * Summary of transactionUser
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransactionUser, Transaction>
     * 
     * This method defines a relationship where one transaction belongs to one transaction user.
     * It allows you to access the user who created the transaction.
     * 
     * For example, you can use `$transaction->transactionUser` to get the user who created the transaction.
     */
    public function transactionUser()
    {
        return $this->belongsTo(TransactionUser::class, 'created_by');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_id');
    }

    /**
     * Summary of invoice
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Invoice, Transaction>
     * 
     * This method defines a relationship where one transaction has one invoice.
     * It allows you to access the invoice associated with a transaction.
     * 
     * For example, you can use `$transaction->invoice` to get the invoice of a transaction.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'transaction_id');
    }

    /**
     * One transaction has many guest details.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GuestDetail>
     *
     * This method defines a one-to-many relationship between a transaction and its guest details.
     * 
     * For example, use `$transaction->guestDetails` to retrieve all guest details associated with the transaction.
     */
    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'transaction_id');
    }

    /**
     * One transaction has many feedback ratings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FeedbackRating>
     *
     * This method defines a one-to-many relationship between a transaction and feedbacks.
     * 
     * For example, use `$transaction->feedbacks` to get all feedback ratings linked to the transaction.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'transaction_id');
    }

    /**
     * One transaction can have many properties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Property>
     *
     * This method defines a many-to-many relationship between a transaction and properties.
     * Additional data like number of adults, kids, extra guests, charges, and days are stored in the pivot table.
     * 
     * Example: `$transaction->properties` returns all associated properties with pivot data.
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'transaction_properties')
            ->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days', 'payment_status', 'paid_at', 'remarks')
            ->withTimestamps()
            ->as('pivot')
            ->orderByPivot('created_at');
    }

    /**
     * One transaction can have many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Activity>
     *
     * This method defines a many-to-many relationship between a transaction and activities.
     * It includes additional pivot data such as quantity, amount, datetime, and status.
     * 
     * Example: `$transaction->activities` gets all linked activities with details.
     */

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'transaction_activities')
            ->withPivot('id', 'quantity', 'amount', 'activity_datetime', 'status', 'payment_status', 'paid_at', 'remarks')
            ->withTimestamps()
            ->as('pivot')
            ->orderByPivot('created_at');
    }


    //  ------------------------------ SCOPES --------------------------------- //

    /**
     * Scope for new reservations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * This scope filters transactions to those with a status of 'pending', 'reserved', or 'receipt_verified'.
     * 
     * Example: `Transaction::newReservations()->get();`
     */
    public function scopeNewReservations($query)
    {
        return $query->whereIn('transaction_status', ['pending', 'reserved', 'receipt_verified']);
    }


    //  ------------------------------ ACCESSORS --------------------------------- //

    /**
     * Accessor to get total amount from all properties in the transaction.
     *
     * @return float|int
     *
     * Sums the 'total_amount' from the pivot table of associated properties.
     * 
     * Example: `$transaction->total_rooms` gives the total room cost.
     */
    public function getTotalRoomsAttribute()
    {
        return $this->properties->sum('pivot.total_amount');
    }

    /**
     * Accessor to get total amount from all activities in the transaction.
     *
     * @return float|int
     *
     * Sums the 'amount' from the pivot table of associated activities.
     * 
     * Example: `$transaction->total_addons` gives the total activity/add-on cost.
     */
    public function getTotalAddonsAttribute()
    {
        return $this->activities->sum('pivot.amount');
    }
}
