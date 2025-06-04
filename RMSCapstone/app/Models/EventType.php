<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name', 'description', 'image'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'event_type_id');
    }

    public function scopeSearch($query, $search)
    {
       $search = trim($search);

        if ($search === '') {
            return $query;
        }

        return $query->where('name', 'like', '%' . $search . '%');
    }
}
