<?php

namespace App\Services;

use App\Models\Activity;

class ActivityCartService
{

    public function addActivity(array $cart, int $itemId, array &$quantity = [], array &$status = [], array &$paymentStatus = [], array $context = [])
    {
        $activity = Activity::findOrFail($itemId);

        $qty = (int) ($quantity[$itemId] ?? 1);
        $activity_rate = $activity->amount;
        $amount = $activity_rate * $qty;
        $activity_schedule_type = $activity->schedule_type ?? null;
        $status[$itemId] = 'pending';
        $paymentStatus[$itemId] = 'unpaid';

        $availableTimes = null;
        if ($activity_schedule_type === 'system') {
            $availableTimes = $activity->available_times ?? [];
        }

        $cart[] = [
            'activity_datetime' => $context['activity_datetime'] ?? null,
            'type' => 'activity',
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'activity_rate' => $activity_rate,
            'quantity' => $qty,
            'amount' => $amount,
            'status' => $status[$itemId],
            'payment_status' => $paymentStatus[$itemId],
            'activity_schedule_type' => $activity_schedule_type,
            'available_times' => $availableTimes,
        ];

        session()->put('cart', $cart);

        return $cart;
    }





    /**
     * Removes a specific activity from the cart.
     */
    public function removeActivity(array $cart, int $activityId)
    {
        // Filter the cart: keep all items that are NOT the target activity
        $updatedCart = array_filter(

            $cart,

            // Callback function that returns a value and pass to the array_filter
            function ($item) use ($activityId) {
                return $item['type'] !== 'activity' || $item['activity_id'] != $activityId;
            }

        );

        // Reindex
        $updatedCart = array_values($updatedCart);

        // Sync session
        session()->put('cart', $updatedCart);

        return $updatedCart;
    }



    /**
     * Updates the quantity and calculated amount for a specific activity item in the cart.
     *
     * This method loops through the given cart array, finds the activity by its ID,
     * and updates its quantity and total amount (quantity × activity rate).
     *
     * @param array $cart The current cart containing items (activities, etc.).
     * @param int $activityId The ID of the activity to update.
     * @param int $quantity The new quantity to set for the activity.
     * @return array The updated cart with the modified activity item.
     */
    public function updateQuantity(array $cart, int $activityId, int $quantity)
    {
        foreach ($cart as $index => $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                $activity = Activity::find($activityId);
                if ($activity) {
                    $cart[$index]['quantity'] = $quantity;
                    $cart[$index]['amount'] = $activity->amount * $quantity;
                }
            }
        }

        session()->put('cart', $cart);


        return $cart;
    }
}
