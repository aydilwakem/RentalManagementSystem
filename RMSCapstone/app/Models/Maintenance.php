<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'mnt_maintenance';

    protected $fillable = ['name', 'description', 'reported_at', 'resolved_at', 'priority_status'];

    protected $casts = [
        'reported_at' => 'date:Y-m-d',
        'resolved_at' => 'date:Y-m-d',
    ];

    public function scopeSearch($query, $search)
    {
        $query->where('description', 'like', "%{$search}%");
    }

    public function scopeFinishedMaintenances($query)
    {
        return $query->whereNotNull('resolved_at');
    }


    public function scopePendingMaintenances($query)
    {
        return $query->whereNull('resolved_at');
    }
}
