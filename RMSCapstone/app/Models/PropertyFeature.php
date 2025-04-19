<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeature extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_features_pivot');
    }
}
