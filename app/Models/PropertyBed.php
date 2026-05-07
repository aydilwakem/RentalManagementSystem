<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyBed extends Model
{
    protected $table = 'property_beds';

     protected $fillable = [
        'property_id',
        'bed_type',
        'bed_quantity',
     ];

     public function property(){
        return $this->belongsTo(Property::class); 
     }
}
