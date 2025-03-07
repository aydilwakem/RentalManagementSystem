<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    use HasFactory;

    protected $table = 'prd_room_categories';

    protected $fillable = ['name', 'image', 'description'];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_category_amenity');
    }
}
