<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\GuestType;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestDetail extends Model
{
    use SoftDeletes;

    protected $table = 'trn_guest_details';

    protected $fillable = ['transaction_id', 'guest_type_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'gender', 'residency', 'country_of_origin'];

    // One Guest Detail belongs to one transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    // One Guest Detail belongs to one Guest Type
    public function guestType()
    {
        return $this->belongsTo(GuestType::class, 'guest_type_id');
    }
}
