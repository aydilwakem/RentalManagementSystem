<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $table = 'prd_amenities';

    protected $fillable = ['name'];

    public function roomCategories()
    {
        return $this->belongsToMany(RoomCategory::class, 'prd_room_category_amenities');
    }
}
