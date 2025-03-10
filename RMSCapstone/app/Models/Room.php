<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomCategory;

class Room extends Model
{
    protected $table = 'prd_rooms';

    protected $fillable = [
        'name',
        'room_category_id',
        'ideal_guest',
        'max_adults',
        'max_kids',
        'turnover_duration',
        'room_status',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function roomRates()
    {
        return $this->hasMany(RoomRate::class, 'room_id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%");
    }
}
