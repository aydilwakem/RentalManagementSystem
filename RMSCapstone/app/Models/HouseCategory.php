<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseCategory extends Model
{
    use SoftDeletes;

    protected $table = 'lt_house_categories';
    protected $fillable = ['name', 'description'];
}
