<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    protected $table = 'prd_event_categories';

    protected $fillable = ['name', 'image', 'description'];

    public function scopeSearch($query, $value){
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }


}
