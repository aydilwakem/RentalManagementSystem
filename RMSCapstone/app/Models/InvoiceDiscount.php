<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDiscount extends Model
{
    use HasFactory;

    protected $table = 'trn_invoice_discounts';

    protected $fillable = [
        'invoice_id',
        'discount_type_id',
        'discount_value',
    ];

    /**
     * Get the invoice this discount belongs to
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Get the discount type details
     */
    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'discount_type_id');
    }
}
