<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\PropertyFeature;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\RoomRate;
use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;

    // ----------------------------------------- Table ------------------------------------------------ //
    protected $table = 'properties';

    protected $fillable = [
        'property_type_id',
        'property_category_id',
        'name_number',
        'ideal_guest',
        'capacity',
        'max_adults',
        'max_kids',
        'max_guests',
        'occupancy_type',
        'occupancy_rules',
        'turnover_duration',
        'property_status',
        'house_number',
        'street',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'postal_code',
        'country',
        'amount',
        'extra_charge_per_hour',
        'extra_person_charge',
        'image',
        'images',
        'description',
        'freebies',
    ];

    protected $casts = [
        'occupancy_rules' => 'array',  // Automatically decode JSON to array
        'images' => 'array',           // Automatically decode JSON to array
    ];

    // -------------------- Activity Logs --------------------- //
    protected static $logOnlyDirty = true; //Only changed attributes are logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
                'property_type_id',
                'property_category_id',
                'name_number',
                'ideal_guest',
                'capacity',
                'max_adults',
                'max_kids',
                'occupancy_rules',
                'turnover_duration',
                'property_status',
                'house_number',
                'street',
                'barangay',
                'city_municipality',
                'region',
                'postal_code',
                'country',
                'amount',
                'extra_charge_per_hour',
                'extra_person_charge',
                'image',
                'images',
                'description'
            ])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Property has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Property');
    }

    // ----------------------------------------- Relationships -------------------------------------------- //

    // A property belongs to one property type - (it can be a Room, House, or Event Hall)
    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class, 'property_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_properties')
            ->withPivot(
                'id',
                'adults',
                'kids',
                'non_chargeable_guests',
                'extra_guest',
                'extra_charge',
                'amount',
                'days',
                'total_amount',
                'room_rate_id'
            )
            ->withTimestamps();
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'property_id');
    }



    // A property belongs to one property category - (for now, this is applicable for rooms. ex: cozy rooms, canopy retreats, pool house)
    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'property_category_id');
    }

    // A property belongs to many features (amenities or inclusions)
    public function features()
    {
        return $this->belongsToMany(PropertyFeature::class, 'property_features_pivot');
    }

    // A property can have many rates (for rooms, houses, and event halls)
    public function rates()
    {
        return $this->hasMany(RoomRate::class, 'property_id');
    }

    public function beds()
    {
        return $this->hasMany(PropertyBed::class, 'property_id');
    }


    // ----------------------------------------- Scopes ------------------------------------------------- //

    /**
     * Scope a query to only include properties of a specific property type name.
     *
     * This uses the 'type' relationship to join with the property_types table
     * and filters based on the type's name (e.g., 'Room', 'House', 'Event Hall').
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $typeName The name of the property type to filter by.
     * @return \Illuminate\Database\Eloquent\Builder
     */


    public function scopeOfType($query, $typeName)
    {
        return $query->whereHas('type', function ($q) use ($typeName) {
            $q->where('name', $typeName);
        });
    }

    public function scopeAvailableRooms($query)
    {
        return $query->where('property_status', 'available')
            ->orderBy('amount', 'asc');
    }

    public function scopeAvailableHouses($query)
    {
        return $query->where('property_status', 'available')
            ->orderBy('amount', 'asc');
    }

    public function scopeAvailableEvents($query)
    {
        return $query->where('property_status', 'available')
            ->orderBy('amount', 'asc');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }

    // ------------------------------------------ Address Name Accessors ----------------------------------- //
    public function getRegionNameAttribute()
    {
        return Region::where('PSGC_REG_CODE', $this->region)->value('PSGC_REG_DESC');
    }

    public function getProvinceNameAttribute()
    {
        return Province::where('PSGC_PROV_CODE', $this->province)->value('PSGC_PROV_DESC');
    }

    public function getMunicipalityNameAttribute()
    {
        return Municipality::where('PSGC_MUNC_CODE', $this->city_municipality)->value('PSGC_MUNC_DESC');
    }

    public function getBarangayNameAttribute()
    {
        return Barangay::where('PSGC_BRGY_CODE', $this->barangay)->value('PSGC_BRGY_DESC');
    }

        public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class, 'property_category_id');
    }

}
