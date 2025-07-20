<?php

namespace App\Services;

use App\Models\Activity;
use App\Services\ActivityCartService;
use App\Services\ServiceCartService;

class CartService
{
    protected $activityCartService;
    protected $serviceCartService;

    public function __construct(ActivityCartService $activityCartService, ServiceCartService $serviceCartService)
    {
        $this->activityCartService = $activityCartService;
        $this->serviceCartService = $serviceCartService;
    }

    public function addItem($type, $itemId, &$cart, &$quantity, &$status, &$paymentStatus)
    {
        switch ($type) {
            case 'activity':
                return $this->activityCartService->addActivity($cart, $itemId, $quantity, $status, $paymentStatus);
                // Add other types here in the future (e.g., room, service)
        }

        switch ($type) {
            case 'service':
                return $this->serviceCartService->addService($cart, $itemId, $quantity, $status, $paymentStatus);
                // Add other types here in the future (e.g., room, service)
        }

        return false;
    }

    public function removeItem($type, $itemId, $cart)
    {
        switch ($type) {
            case 'activity':
                return $this->activityCartService->removeActivity($cart, $itemId);
                // Extend here for 'room' or 'service'
        }

        switch ($type) {
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
