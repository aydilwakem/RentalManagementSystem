<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;

class PropertyCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'image'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_category_id');
    }
}
