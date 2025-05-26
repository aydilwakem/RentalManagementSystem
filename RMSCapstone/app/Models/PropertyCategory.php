<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyCategory extends Model
{
    use SoftDeletes;
    use HasFactory; 

    protected $fillable = ['name', 'description'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_category_id');
    }

    public function scopeSearch($query, $search)
    {
        $search = trim($search);

        if ($search === '') {
            return $query;
        }

        return $query->where('name', 'like', '%' . $search . '%');
    }
}
