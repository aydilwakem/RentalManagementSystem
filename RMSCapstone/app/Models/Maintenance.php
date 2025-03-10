<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'mnt_maintenance';

    protected $fillable = ['description', 'reported_at', 'resolved_at', 'status'];

    // public function scopeSearch($query, $search){
    //     $query->where('description', 'like', "%{$search}%");
    // }
}
