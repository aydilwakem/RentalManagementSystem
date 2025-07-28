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

        $property = Property::find($itemId);

        $cart[] = [
            'type'           => 'room',
            'room_id'        => $itemId,

            'room_name'      => $context['room_name'] ?? 'Unknown Room',
            'days'           => $context['days'] ?? 1,
            'adults'         => $context['adults'] ?? 1,
            'kids'           => $context['kids'] ?? 0,
            'included_guests' => $context['included_guests'] ?? 0,
            'extra_guest'    => $context['extra_guest'] ?? 0,
            'rate_id'        => $context['rate_id'] ?? null,
            'roomRateName'   => $context['roomRateName'] ?? '',
            'roomRate'     => $context['roomRate'] ?? 0,
            'extra_charge'   => $context['extra_charge'] ?? 0,

            'roomAmount'     => $context['roomAmount'] ?? 0,
            'extra_charge_total'   => $context['extra_charge_total'] ?? 0,
            'total_amount'   => $context['total_amount'] ?? 0,
            'payment_status' => $context['payment_status'] ?? 'unpaid',
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
