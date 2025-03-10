<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'prd_events';

    protected $fillable = [
        'name',
        'event_category_id',
        'event_hall_id',
        'company_name',
        'contact_person',
        'email',
        'event_date_start', 
        'event_date_end', 
        'event_time', 
        'capacity', 
        'total_amount', 
        'status',
        'requests',
    ];

    protected $casts = [
        'event_date_start' => 'date:Y-m-d',
        'event_date_end' => 'date:Y-m-d',
        'event_time' => 'datetime:H:i', // Cast event_time to 'HH:MM' format
    ];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function eventHall(){
        return $this->belongsTo(EventHall::class, 'event_hall_id');
    }

    public function scopeSearch($query, $search){
        $query->where('name', 'like', "%{$search}%")->orWhere('company_name', 'like', "%{$search}%");
    }
}
