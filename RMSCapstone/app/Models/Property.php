<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction;
use App\Models\PropertyFeature;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Builder;

class Property extends Model
{
    use SoftDeletes;

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
        'image',
        'description',
    ];

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
        return $this->belongsToMany(Transaction::class, 'transaction_properties');
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
        return $query->whereRaw('LOWER(property_status) = ?', ['available']);
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }
}
