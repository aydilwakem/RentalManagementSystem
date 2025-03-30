<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;

class Tenant extends Model
{
    use SoftDeletes;
    protected $table = 'lt_tenants';

    // Mass assignable attributes
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'house_id',
        'email',
        'phone',
        'birthdate',
        'gender',
        'occupation',
        'notes',
    ];

    // Define the relationship with LtHouse (assuming you have a corresponding model)
    public function house()
    {
        return $this->belongsTo(Property::class, 'house_id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%");
    }
}
