<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prd_room_categories';

    protected $fillable = ['name', 'image', 'description'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_category_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_category_amenity');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%($value)%");
    }
}
