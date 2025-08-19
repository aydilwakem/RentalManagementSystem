<?php

namespace App\Services;

use App\Models\Activity;
use App\Services\ActivityCartService;
use App\Services\ServiceCartService;
use App\Services\RoomCartService;

class CartService
{
    protected $activityCartService;
    protected $serviceCartService;
    protected $roomCartService;

    public function __construct(ActivityCartService $activityCartService, ServiceCartService $serviceCartService, RoomCartService $roomCartService)
    {
        $this->activityCartService = $activityCartService;
        $this->serviceCartService = $serviceCartService;
        $this->roomCartService = $roomCartService;
    }

    public function addItem($type, $itemId, &$cart, &$quantity, &$status, &$paymentStatus, $context = [])
    {
        switch ($type) {
            case 'room':
                return $this->roomCartService->addRoom($cart, $itemId, $context);

            case 'activity':
                return $this->activityCartService->addActivity($cart, $itemId, $quantity, $status, $paymentStatus, $context);

            case 'service':
                return $this->serviceCartService->addService($cart, $itemId, $quantity, $status, $paymentStatus, $context);

            default:
                return false;
        }
    }

    public function removeItem($type, $itemId, $cart)
    {
        switch ($type) {
            case 'room':
                return $this->roomCartService->removeRoom($cart, $itemId);
                // Extend here for 'room' or 'service'

            case 'activity':
                return $this->activityCartService->removeActivity($cart, $itemId);
                // Extend here for 'room' or 'service'

            case 'service':
                return $this->serviceCartService->removeService($cart, $itemId);
                // Extend here for 'room' or 'service'
        }



        return $cart;
    }


    public function updateQuantity($type, $cart, $itemId, $quantity)
    {
        switch ($type) {
            case 'activity':
                return $this->activityCartService->updateQuantity($cart, $itemId, $quantity);
                // Extend here for 'room' or 'service'
        }

        switch ($type) {
            case 'service':
                return $this->serviceCartService->updateServiceQuantity($cart, $itemId, $quantity);
                // Extend here for 'room' or 'service'
        }

        return $cart;
    }
}
