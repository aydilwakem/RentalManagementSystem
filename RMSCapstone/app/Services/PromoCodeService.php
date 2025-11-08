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
     * Applies discount only to room charges that match the property category.
     *
     * @param string $promoCode      The promo code entered by the user.
     * @param float  $bookingAmount  The total booking amount before applying promo.
     * @param float  $roomSubTotal   The subtotal of room charges only.
     * @param array  $roomBreakdown  Array containing property category breakdown for discount calculation
     * @return array                 Result containing success status, message, and discount.
     */
    public function validateAndApply(string $promoCode, float $bookingAmount, float $roomSubTotal, array $roomBreakdown = [], ?string $checkInDate = null, ?string $checkOutDate = null): array
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

    // ADD THIS VALIDATION - Check stay date range (only if dates are provided)
    if ($checkInDate && $checkOutDate && !$this->isWithinValidStayDateRange($promo, $checkInDate, $checkOutDate)) {
        $stayStart = $promo->stay_start_date ? Carbon::parse($promo->stay_start_date)->format('M j, Y') : 'any date';
        $stayEnd = $promo->stay_end_date ? Carbon::parse($promo->stay_end_date)->format('M j, Y') : 'any date';
        return $this->error("This promo code is only valid for stays between {$stayStart} and {$stayEnd}.");
    }


        if (!$promo->is_active) {
            return $this->error('This promo code is currently inactive.');
        }

        // Calculate discount based on eligible room charges (considering property categories)
        $discount = $this->calculateDiscount($promo, $roomSubTotal, $roomBreakdown);

        // Check if discount is applicable to any rooms in the cart
        if ($discount <= 0 && !empty($roomBreakdown) && $promo->isCategorySpecific()) {
            $categoryName = $promo->propertyCategory?->name ?? 'the selected category';
            return $this->error("This promo code only applies to {$categoryName} rooms. No eligible rooms found in your cart.");
        }

        return [
            'success' => true,
            'discount' => $discount,
            'message' => $this->generateSuccessMessage($promo, $discount),
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
     * Considers property categories for discount calculation.
     *
     * @param PromoCode $promo
     * @param float $roomSubTotal  The subtotal of room charges only
     * @param array $roomBreakdown Array with property category breakdown
     * @return float
     */
    protected function calculateDiscount(PromoCode $promo, float $roomSubTotal, array $roomBreakdown): float
    {
        // If no room breakdown provided or promo is not category-specific, apply to all room charges
        if (empty($roomBreakdown) || !$promo->isCategorySpecific()) {
            return match ($promo->discount_type) {
                'percentage' => ($promo->discount_value / 100) * $roomSubTotal,
                'fixed' => min($promo->discount_value, $roomSubTotal),
                default => 0,
            };
        }

        // Calculate discount only for eligible property categories
        $eligibleBaseRoomAmount = 0;

        foreach ($roomBreakdown as $room) {
            $propertyCategoryId = $room['property_category_id'] ?? null;

            if ($promo->appliesToPropertyCategory($propertyCategoryId)) {
                $eligibleBaseRoomAmount += $room['base_amount'] ?? $room['roomAmount'] ?? 0;
            }
        }

        if ($eligibleBaseRoomAmount <= 0) {
            return 0; // No eligible rooms for this promo
        }

        return match ($promo->discount_type) {
            'percentage' => ($promo->discount_value / 100) * $eligibleBaseRoomAmount,
            'fixed' => min($promo->discount_value, $eligibleBaseRoomAmount),
            default => 0,
        };
    }

    /**
     * Generates appropriate success message based on promo type and property category
     *
     * @param PromoCode $promo
     * @param float $discount
     * @return string
     */
    protected function generateSuccessMessage(PromoCode $promo, float $discount): string
    {
        $baseMessage = 'Promo code applied! You saved ₱' . number_format($discount, 2);

        if (!$promo->isCategorySpecific()) {
            return $baseMessage . ' on base room charges.';
        }

        if ($promo->propertyCategory) {
            return $baseMessage . ' on ' . $promo->propertyCategory->name . ' rooms.';
        }

        return $baseMessage . ' on eligible room charges.';
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

/**
 * Checks if the reservation dates are within the valid stay date range of the promo code.
 *
 * @param PromoCode $promo
 * @param string|null $checkInDate
 * @param string|null $checkOutDate
 * @return bool
 */
protected function isWithinValidStayDateRange(PromoCode $promo, ?string $checkInDate, ?string $checkOutDate): bool
{
    // If no stay date range is set, promo applies to all stay dates
    if (is_null($promo->stay_start_date) && is_null($promo->stay_end_date)) {
        return true;
    }

    // If no check-in/check-out dates provided, we can't validate stay dates
    if (is_null($checkInDate) || is_null($checkOutDate)) {
        return true;
    }

    $checkIn = Carbon::parse($checkInDate);
    $checkOut = Carbon::parse($checkOutDate);

    // Check if the entire stay falls within the promo's valid stay period
    if ($promo->stay_start_date && $checkIn->lessThan(Carbon::parse($promo->stay_start_date))) {
        return false; // Check-in is before promo's valid stay start
    }

    if ($promo->stay_end_date && $checkOut->greaterThan(Carbon::parse($promo->stay_end_date))) {
        return false; // Check-out is after promo's valid stay end
    }

    return true;
}

}
