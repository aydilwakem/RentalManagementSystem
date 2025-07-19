<?php

namespace App\Services;

use App\Models\Activity;

class ActivityCartService
{
    public function addActivity(array $cart, int $activityId, array &$quantity = [], array &$status = [], array &$paymentStatus = [])
    {
        $activity = Activity::findOrFail($activityId);

        foreach ($cart as $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                return false;
            }
        }

        $qty = (int) ($quantity[$activityId] ?? 1);
        $amount = $activity->amount * $qty;

        $status[$activityId] = 'pending';
        $paymentStatus[$activityId] = 'unpaid';

        $cart[] = [
            'type' => 'activity',
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'quantity' => $qty,
            'amount' => $amount,
            'status' => $status[$activityId],
            'payment_status' => $paymentStatus[$activityId],
        ];

        return $cart;
    }


    /**
     * Removes a specific activity from the cart.
     */
    public function removeActivity(array $cart, int $activityId)
    {
        // Filter the cart: keep all items that are NOT the target activity
        $updatedCart = array_filter($cart, function ($item) use ($activityId) {
            // Keep the item if:
            // - It's not an activity
            // - OR it's an activity but NOT the one we're trying to remove
            return $item['type'] !== 'activity' || $item['activity_id'] != $activityId;
        });

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
