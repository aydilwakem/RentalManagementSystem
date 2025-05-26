<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    use HasFactory; 

    protected $table = 'prd_activities';

    protected $fillable = ['name', 'description', 'amount', 'inclusions', 'image'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function scopeSearch($query, $value)
    {
        $query->where('name', 'like', "%{$value}%")->orWhere('description', 'like', "%{$value}%");
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_activities')
            ->withPivot(
                'quantity',
                'amount',
                'activity_datetime',
                'status',
            )
            ->withTimestamps();
    }

    public function scopeAvailableActivities($query)
    {
        return $query->where('amount', '!=', 0);
    }
}
