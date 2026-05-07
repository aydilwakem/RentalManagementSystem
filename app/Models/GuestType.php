<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestType extends Model
{
    use SoftDeletes;

    protected $table = 'trn_guest_type';

    protected $fillable = ['name', 'description'];

    public function guestDetails()
    {
        return $this->hasMany(GuestType::class, 'guest_type_id');
    }
}
