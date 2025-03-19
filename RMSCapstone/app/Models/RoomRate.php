<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomRate extends Model
{
    use SoftDeletes;

    protected $table = 'prd_room_rates';

    protected $fillable = [
        'name',
        'room_id',
        'start_date',
        'end_date',
        'amount',
        'extra_person_charge',
        'extended_stay_charge_per_hr',
        'description',
        'rate_type',
    ];

    /**
     * Get the room associated with this rate.
     */

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%");
    }
}
