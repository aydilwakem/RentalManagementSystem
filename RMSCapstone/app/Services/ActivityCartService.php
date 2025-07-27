<?php

namespace App\Services;

use App\Models\Activity;

class ActivityCartService
{
    public function addActivity(array $cart, int $itemId, array &$quantity = [], array &$status = [], array &$paymentStatus = [])
    {
        $activity = Activity::findOrFail($itemId);

        // Sets the values for the activity
        $qty = (int) ($quantity[$itemId] ?? 1);
        $activity_rate = $activity->amount;
        $amount = $activity->amount * $qty;
        $status[$itemId] = 'pending';
        $paymentStatus[$itemId] = 'unpaid';

        // Inserts the activity to the cart
        $cart[] = [
            'type' => 'activity',
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'activity_rate' => $activity_rate,
            'quantity' => $qty,
            'amount' => $amount,
            'status' => $status[$itemId],
            'payment_status' => $paymentStatus[$itemId],
        ];

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

        // Reindex the array to avoid gaps in numeric keys
        return array_values($updatedCart);
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

        return $cart;
    }
}
