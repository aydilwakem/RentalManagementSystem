<?php

namespace App\Services;

use App\Models\Service;

class ServiceCartService
{

    public function addService(array &$cart, int $itemId, array &$quantity = [], array &$status = [], array &$paymentStatus = [], array $context = [])
    {
        $service = Service::findOrFail($itemId);

        // Prevent duplicate service
        foreach ($cart as $item) {
            if ($item['type'] === 'service' && $item['service_id'] == $itemId) {
                return false;
            }
        }

        $qty = (int) ($quantity[$itemId] ?? 1);
        $service_rate = $service->amount;
        $service_unit = $service->unit;
        $amount = $service_rate * $qty;
        $status[$itemId] = 'pending';
        $paymentStatus[$itemId] = 'unpaid';

        $cart[] = [
            'type' => 'service',
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_rate' => $service_rate,
            'service_unit' => $service_unit,
            'quantity' => $qty,
            'amount' => $amount,
            'status' => $status[$itemId],
            'payment_status' => $paymentStatus[$itemId],
            'properties_with_extra_hour' => $context['properties'] ?? null,
        ];

        return $cart;
    }



    /**
     * Removes a specific activity from the cart.
     */
    public function removeService(array $cart, int $serviceId)
    {
        // Filter the cart: keep all items that are NOT the target activity
        $updatedCart = array_filter(

            $cart,

            // Callback function that returns a value and pass to the array_filter
            function ($item) use ($serviceId) {
                return $item['type'] !== 'service' || $item['service_id'] != $serviceId;
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
    public function updateServiceQuantity(array $cart, int $serviceId, int $quantity)
    {
        foreach ($cart as $index => $item) {
            if ($item['type'] === 'service' && $item['service_id'] == $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $cart[$index]['quantity'] = $quantity;
                    $cart[$index]['amount'] = $service->amount * $quantity;
                }
            }
        }

        return $cart;
    }
}
