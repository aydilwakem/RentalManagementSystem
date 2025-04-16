<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseCategory extends Model
{
    use SoftDeletes;

    protected $table = 'house_categories';
    protected $fillable = ['name', 'description'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'house_category_id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }
}
