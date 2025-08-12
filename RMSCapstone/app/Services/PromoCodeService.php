<?php

namespace App\Services;

use App\Models\PromoCode;
use Carbon\Carbon;

/**
 * Service for handling promo code validation and discount calculation logic.
 */
class PromoCodeService
{
    /**
     * Validates the promo code and applies the appropriate discount if valid.
     *
     * @param string $promoCode      The promo code entered by the user.
     * @param float  $bookingAmount  The total booking amount before applying promo.
     * @param float  $subTotal       The subtotal before tax/fees to apply discount on.
     * @return array                 Result containing success status, message, and discount.
     */
    public function validateAndApply(string $promoCode, float $bookingAmount, float $subTotal): array
    {
        $promo = $this->findPromo($promoCode);

        if (!$promo) {
            return $this->error('Invalid promo code. Please try again.');
        }

        if (!$this->meetsMinimumBookingAmount($promo, $bookingAmount)) {
            return $this->error('This promo requires a minimum booking of ₱' .
                number_format($promo->min_booking_amount, 2) . '.');
        }

        if (!$this->isWithinValidDateRange($promo)) {
            return $this->error('This promo code has expired or is not yet active.');
        }

        if ($this->hasReachedUsageLimit($promo)) {
            return $this->error('This promo code has reached its maximum usage limit.');
        }

        if (!$promo->is_active) {
            return $this->error('This promo code is currently inactive.');
        }

        $discount = $this->calculateDiscount($promo, $subTotal);

        return [
            'success' => true,
            'discount' => $discount,
            'message' => 'Promo code applied! You saved ₱' . number_format($discount, 2) . '.',
            'promo' => $promo,
        ];
    }

    /**
     * Finds the promo code from the database.
     *
     * @param string $code  The promo code entered by the user.
     * @return PromoCode|null
     */
    protected function findPromo(string $code): ?PromoCode
    {
        return PromoCode::where('code', $code)->first();
    }

    /**
     * Checks if the booking amount meets the minimum requirement for the promo code.
     *
     * @param PromoCode $promo
     * @param float $bookingAmount
     * @return bool
     */
    protected function meetsMinimumBookingAmount(PromoCode $promo, float $bookingAmount): bool
    {
        if (is_null($promo->min_booking_amount)) {
            return true; // No minimum requirement
        }
        return $bookingAmount >= $promo->min_booking_amount;
    }

    /**
     * Checks if the current date is within the valid range of the promo code.
     *
     * @param PromoCode $promo
     * @return bool
     */
    protected function isWithinValidDateRange(PromoCode $promo): bool
    {
        if (!$promo->has_expiration || !$promo->start_date || !$promo->end_date) {
            return true;
        }

        $now = Carbon::now('Asia/Manila');
        return $now->between(Carbon::parse($promo->start_date), Carbon::parse($promo->end_date));
    }

    /**
     * Determines if the promo code has reached its usage limit.
     *
     * @param PromoCode $promo
     * @return bool
     */
    protected function hasReachedUsageLimit(PromoCode $promo): bool
    {
        if (is_null($promo->max_uses)) {
            return false; // Unlimited uses
        }

        return $promo->max_uses > 0 && $promo->uses_count >= $promo->max_uses;
    }

    /**
     * Calculates the discount amount based on the promo code's type and value.
     *
     * @param PromoCode $promo
     * @param float $subTotal
     * @return float
     */
    protected function calculateDiscount(PromoCode $promo, float $subTotal): float
    {
        return match ($promo->discount_type) {
            'percentage' => ($promo->discount_value / 100) * $subTotal,
            'fixed' => $promo->discount_value,
            default => 0,
        };
    }

    /**
     * Returns a standardized error response.
     *
     * @param string $message  Error message to return.
     * @return array
     */
    protected function error(string $message): array
    {
        return [
            'success' => false,
            'discount' => 0,
            'message' => $message,
            'promo' => null,
        ];
    }
}
