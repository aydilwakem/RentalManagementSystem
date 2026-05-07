<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prd_amenities';

    protected $fillable = ['name'];

    public function roomCategories()
    {
        return $this->belongsToMany(RoomCategory::class, 'prd_room_category_amenities');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }
}
