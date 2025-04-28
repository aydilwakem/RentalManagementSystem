<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $table = 'prd_activities';

    protected $fillable = ['name', 'description', 'amount', 'inclusions', 'image'];

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_activities')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
