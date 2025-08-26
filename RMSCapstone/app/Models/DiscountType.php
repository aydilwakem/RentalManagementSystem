<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    use HasFactory;

    protected $table = 'discount_types';

    protected $fillable = [
        'name',
        'rate',
        'type',
        'is_active',
    ];

    /**
     * Get all invoice discounts using this discount type
     */
    public function invoiceDiscounts()
    {
        return $this->hasMany(InvoiceDiscount::class, 'discount_type_id');
    }
}
