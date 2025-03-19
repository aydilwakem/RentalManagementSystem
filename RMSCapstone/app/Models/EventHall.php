<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventHall extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prd_event_halls';

    protected $fillable = ['name', 'image', 'description', 'capacity', 'amount', 'extra_charge_per_hr'];


    public function events()
    {
        return $this->hasOne(Event::class, 'event_hall_id');
    }

    public function scopeSearch($query, $search){
        $query->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
    }
}
