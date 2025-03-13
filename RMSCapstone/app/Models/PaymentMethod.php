<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'pm_payment_methods';

    protected $fillable = 
    ['mode_of_payment_name',
    'account_name', 
    'account_number', 
    'mode_of_payment_qr_image', 
  ];

    public function scopeSearch($query, $search){
    $query->where('mode_of_payment_name', 'like', "%{$search}%")->orWhere('account_name', 'like', "%{$search}%");
  }

}
