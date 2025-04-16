<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionUser extends Model
{
    use SoftDeletes;

    protected $table = 'trn_users';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'contact_number',
        'company_name',
        'house_number',
        'street',
        'barangay',
        'city_municipality',
        'province',
        'region',
        'postal_code',
        'country',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }
}
