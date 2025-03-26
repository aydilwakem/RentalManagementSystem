<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;
    protected $table = 'lt_houses';

    protected $fillable = [
        'name',
        'house_category_id',
        'description',
        'monthly_rent',
        'availability',
        'house_number',
        'street',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'postal_code',
        'country',
        'reported_at',
        'resolved_at',
        'priority_status'
    ];

    public function category()
    {
        return $this->belongsTo(HouseCategory::class, 'house_category_id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }
}
