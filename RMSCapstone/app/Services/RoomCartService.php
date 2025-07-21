<?php

namespace App\Services;

use App\Models\Property;

/**
 * Service class to load a transaction and all its related data.
 */
class RoomCartService
{

    public function addRoom(array &$cart, int $itemId, array $context = [])
    {
        foreach ($cart as $item) {
            if ($item['type'] === 'room' && $item['room_id'] == $itemId) {
                return false;
            }
        }

        $cart[] = [
            'type'           => 'room',
            'room_id'        => $itemId,
            'room_name'      => $context['room_name'] ?? 'Unknown Room',
            'extra_guest'    => $context['extra_guest'] ?? 0,
            'days'           => $context['days'] ?? 1,
            'adults'         => $context['adults'] ?? 1,
            'kids'           => $context['kids'] ?? 0,
            'roomAmount'     => $context['roomAmount'] ?? 0,
            'roomRateName'   => $context['roomRateName'] ?? '',
            'rate_id'        => $context['rate_id'] ?? null,
            'extra_charge'   => $context['extra_charge'] ?? 0,
            'total_amount'   => $context['total_amount'] ?? 0,
        ];

        return true;
    }


    /**
     * Removes a specific room from the cart.
     */
    public function removeRoom(array $cart, int $roomId)
    {
        $updatedCart = array_filter(
            $cart,
            function ($item) use ($roomId) {
                return $item['type'] !== 'room' || $item['room_id'] != $roomId;
            }
        );

        return array_values($updatedCart);
    }
}
