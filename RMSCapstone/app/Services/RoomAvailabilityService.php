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
            return collect();
        }

        // Force standard times kahit anong ipasa
        $checkIn = Carbon::parse($checkInDate)->setTime(15, 0, 0); // Force 3PM
        $checkOut = Carbon::parse($checkOutDate)->setTime(12, 0, 0); // Force 12PM

        return Property::ofType('Room')
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->with(['transactions' => function ($query) use ($checkIn, $checkOut) {
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
            }])
            ->get()
            ->filter(function ($room) use ($checkIn, $checkOut) {
                // Check blocked dates (dates only, ignore time)
                $blockedDates = $room->blocked_dates ?? [];

                $currentDate = $checkIn->copy()->startOfDay();
                $lastDate = $checkOut->copy()->startOfDay();

                while ($currentDate->lte($lastDate)) {
                    if (in_array($currentDate->format('Y-m-d'), $blockedDates)) {
                        return false;
                    }
                    $currentDate->addDay();
                }

                return true;
            })
            ->map(function ($room) use ($checkIn) {
                $room->is_booked = $room->transactions->isNotEmpty();

                // Pass only date for rate calculation
                $rate = $this->roomRateService->getDynamicRate($room, $checkIn->format('Y-m-d'));

                $room->dynamic_rate = $rate['amount'];
                $room->rate_name = $rate['name'];
                $room->rate_type = $rate['rate_type'];

                return $room;
            })
            ->sortBy('is_booked')
            ->values();
    }

    public function isRoomAvailable($roomId, $checkInDate, $checkOutDate)
    {
        if (!$checkInDate || !$checkOutDate) {
            return false;
        }

        // Force standard times
        $checkIn = Carbon::parse($checkInDate)->setTime(15, 0, 0);
        $checkOut = Carbon::parse($checkOutDate)->setTime(12, 0, 0);

        $room = Property::ofType('Room')
            ->where('id', $roomId)
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->with(['transactions' => function ($query) use ($checkIn, $checkOut) {
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
            }])
            ->first();

        if (!$room) {
            return false;
        }

        // Check blocked dates
        $blockedDates = $room->blocked_dates ?? [];
        $currentDate = $checkIn->copy()->startOfDay();
        $lastDate = $checkOut->copy()->startOfDay();

        while ($currentDate->lte($lastDate)) {
            if (in_array($currentDate->format('Y-m-d'), $blockedDates)) {
                return false;
            }
            $currentDate->addDay();
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
