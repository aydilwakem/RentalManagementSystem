<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;

class PropertyCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_category_id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }
}
