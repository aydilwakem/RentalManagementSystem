<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;

class PropertyType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_type_id');
    }

    public function propertyFeatures(){
        return $this->hasMany(PropertyFeature::class, 'property_type_id');
    }
}
