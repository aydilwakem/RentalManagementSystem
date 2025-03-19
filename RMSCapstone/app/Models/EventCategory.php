<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prd_event_categories';

    protected $fillable = ['name', 'image', 'description'];

    public function events()
    {
        return $this->hasMany(Event::class, 'event_category_id');
    }

    public function scopeSearch($query, $value){
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }


}
