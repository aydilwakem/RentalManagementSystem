<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'st_settings';

    protected $fillable = [
        'company_name',
        'logo',
        'email',
        'contact_number',
        'address',
        'facebook',
        'instagram',
        'terms_and_conditions',
        'privacy_policy',
        'refund_policy',
        'rental_agreement',
        'custom_css',
        'custom_js',
    ];
}
