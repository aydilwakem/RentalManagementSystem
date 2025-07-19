<?php

namespace App\Services;

use App\Models\Property;

/**
 * Service class to load a transaction and all its related data.
 */
class RoomCartService
{

    /**
     * Removes a specific activity from the cart.
     */
    public function removeRoom(array $cart, int $roomId)
    {
        // Filter the cart: keep all items that are NOT the target activity
        $updatedCart = array_filter($cart, function ($item) use ($roomId) {
            // Keep the item if:
            // - It's not a room
            // - OR it's a room but NOT the one we're trying to remove
            return $item['type'] !== 'room' || $item['room_id'] != $roomId;
        });

        // Reindex the array to avoid gaps in numeric keys
        return array_values($updatedCart);
    }
}
