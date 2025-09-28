<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Property;

class RoomAvailabilityService
{
    protected $roomRateService;

    public function __construct(RoomRateService $roomRateService)
    {
        $this->roomRateService = $roomRateService;
    }

    public function getAvailableRooms($checkInDate, $checkOutDate)
    {
        if (!$checkInDate || !$checkOutDate) {
            return collect(); // return empty collection
        }

        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        return Property::ofType('Room')
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->with([
                'features',
                'transactions' => function ($query) use ($checkIn, $checkOut) {
                    $query->whereIn('transaction_status', [
                        'pending',
                        'reserved',
                        'receipt_verified',
                        'confirmed',
                        'ongoing'
                    ])
                        ->where(function ($q) use ($checkIn, $checkOut) {
                            $q->where('start_datetime', '<', $checkOut)
                                ->where('end_datetime', '>', $checkIn);
                        });
                }
            ])
            ->get()
            ->map(function ($room) use ($checkIn) {
                $rate = $this->roomRateService->getDynamicRate($room, $checkIn->toDateString());

                $room->is_booked = $room->transactions->isNotEmpty();
                $room->dynamic_rate = $rate['amount'];
                $room->rate_name = $rate['name'];
                $room->rate_type = $rate['rate_type'];

                return $room;
            })
            ->sortBy('is_booked');
    }

    // NEW METHOD: Check if a specific room is available for given dates
    public function isRoomAvailable($roomId, $checkInDate, $checkOutDate)
    {
        if (!$checkInDate || !$checkOutDate) {
            return false;
        }

        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        $room = Property::ofType('Room')
            ->where('id', $roomId)
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->with([
                'transactions' => function ($query) use ($checkIn, $checkOut) {
                    $query->whereIn('transaction_status', [
                        'pending',
                        'reserved',
                        'receipt_verified',
                        'confirmed',
                        'ongoing'
                    ])
                        ->where(function ($q) use ($checkIn, $checkOut) {
                            $q->where('start_datetime', '<', $checkOut)
                                ->where('end_datetime', '>', $checkIn);
                        });
                }
            ])
            ->first();

        if (!$room) {
            return false;
        }

        return $room->transactions->isEmpty();
    }

    // NEW METHOD: Get available dates for a specific room
    public function getRoomAvailability($roomId, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $availableDates = [];

        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            // Check availability for a single night
            if ($this->isRoomAvailable($roomId, $currentDate->format('Y-m-d'), $currentDate->copy()->addDay()->format('Y-m-d'))) {
                $availableDates[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $availableDates;
    }

    
    

}
