<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Property;
use App\Models\Transaction;

class SpecificRoomAvailabilityService
{
    protected $roomRateService;

    public function __construct(RoomRateService $roomRateService)
    {
        $this->roomRateService = $roomRateService;
    }

    /**
     * Check if a specific room is available for the given dates
     */
    public function isRoomAvailable($roomId, $checkInDate, $checkOutDate)
    {
        if (!$checkInDate || !$checkOutDate) {
            return false;
        }

        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        // Check if room exists and is not out of service or held
        $room = Property::ofType('Room')
            ->where('id', $roomId)
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->first();

        if (!$room) {
            return false;
        }

        // Check for conflicting transactions
        $conflictingTransactions = Transaction::whereHas('properties', function ($query) use ($roomId) {
                $query->where('property_id', $roomId);
            })
            ->whereIn('transaction_status', [
                'pending',
                'reserved',
                'receipt_verified',
                'confirmed',
                'ongoing'
            ])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('start_datetime', '<', $checkOut)
                    ->where('end_datetime', '>', $checkIn);
            })
            ->exists();

        return !$conflictingTransactions;
    }

    /**
     * Get detailed availability information for a specific room
     */
    public function getRoomAvailabilityDetails($roomId, $checkInDate, $checkOutDate)
    {
        if (!$checkInDate || !$checkOutDate) {
            return [
                'available' => false,
                'message' => 'Check-in and check-out dates are required'
            ];
        }

        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        // Validate dates
        if ($checkIn->isPast()) {
            return [
                'available' => false,
                'message' => 'Check-in date cannot be in the past'
            ];
        }

        if ($checkOut->lte($checkIn)) {
            return [
                'available' => false,
                'message' => 'Check-out date must be after check-in date'
            ];
        }

        // Get room with details
        $room = Property::ofType('Room')
            ->where('id', $roomId)
            ->with([
                'features',
                'type',
                'category',
                'beds',
                'rates',
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
            return [
                'available' => false,
                'message' => 'Room not found or not available for booking'
            ];
        }

        // Check room status
        if (in_array($room->property_status, ['out_of_service', 'held'])) {
            return [
                'available' => false,
                'message' => 'Room is currently not available for booking'
            ];
        }

        // Check for maintenance
        $maintenanceConflict = $this->checkMaintenanceConflict($room, $checkIn, $checkOut);
        if ($maintenanceConflict['has_conflict']) {
            return [
                'available' => false,
                'message' => $maintenanceConflict['message'],
                'maintenance_conflict' => true
            ];
        }

        // Get rate information
        $rate = $this->roomRateService->getDynamicRate($room, $checkIn->toDateString());
        
        // Calculate duration
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $rate['amount'] * $nights;

        $availabilityDetails = [
            'available' => $room->transactions->isEmpty(),
            'room' => [
                'id' => $room->id,
                'name' => $room->name_number,
                'type' => $room->type->name ?? null,
                'category' => $room->category->name ?? null,
                'capacity' => $room->capacity,
                'max_adults' => $room->max_adults,
                'max_kids' => $room->max_kids,
                'max_guests' => $room->max_guests,
                'base_amount' => $room->amount,
                'features' => $room->features,
                'beds' => $room->beds,
                'images' => $room->images,
                'description' => $room->description
            ],
            'dates' => [
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'nights' => $nights
            ],
            'pricing' => [
                'rate_name' => $rate['name'],
                'rate_type' => $rate['rate_type'],
                'daily_rate' => $rate['amount'],
                'total_amount' => $totalAmount,
                'extra_charge_per_hour' => $room->extra_charge_per_hour,
                'extra_person_charge' => $room->extra_person_charge
            ],
            'conflicts' => [
                'has_conflict' => !$room->transactions->isEmpty(),
                'conflicting_transactions' => $room->transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'status' => $transaction->transaction_status,
                        'start_datetime' => $transaction->start_datetime,
                        'end_datetime' => $transaction->end_datetime
                    ];
                })
            ]
        ];

        if (!$room->transactions->isEmpty()) {
            $availabilityDetails['message'] = 'Room is already booked for the selected dates';
        }

        return $availabilityDetails;
    }

    /**
     * Check for maintenance conflicts
     */
    protected function checkMaintenanceConflict(Property $room, Carbon $checkIn, Carbon $checkOut)
    {
        $maintenance = $room->maintenance()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('start_date', '<', $checkOut)
                    ->where('end_date', '>', $checkIn);
            })
            ->where('status', 'scheduled')
            ->first();

        if ($maintenance) {
            return [
                'has_conflict' => true,
                'message' => "Room is under maintenance from {$maintenance->start_date} to {$maintenance->end_date}"
            ];
        }

        return [
            'has_conflict' => false,
            'message' => null
        ];
    }

    /**
     * Get available date ranges for a specific room within a period
     */
    public function getAvailableDateRanges($roomId, $startDate, $endDate, $maxStayDays = 30)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        if ($end->diffInDays($start) > $maxStayDays) {
            $end = $start->copy()->addDays($maxStayDays);
        }

        $room = Property::ofType('Room')
            ->where('id', $roomId)
            ->whereNotIn('property_status', ['out_of_service', 'held'])
            ->first();

        if (!$room) {
            return [];
        }

        // Get all bookings for the room in the date range
        $bookings = Transaction::whereHas('properties', function ($query) use ($roomId) {
                $query->where('property_id', $roomId);
            })
            ->whereIn('transaction_status', [
                'pending',
                'reserved',
                'receipt_verified',
                'confirmed',
                'ongoing'
            ])
            ->where(function ($query) use ($start, $end) {
                $query->where('start_datetime', '<', $end)
                    ->where('end_datetime', '>', $start);
            })
            ->orderBy('start_datetime')
            ->get(['id', 'start_datetime', 'end_datetime']);

        $availableRanges = [];
        $currentDate = $start->copy();

        while ($currentDate < $end) {
            // Find the next available start date
            $nextBooking = $bookings->first(function ($booking) use ($currentDate) {
                $bookingStart = Carbon::parse($booking->start_datetime);
                $bookingEnd = Carbon::parse($booking->end_datetime);
                return $currentDate < $bookingEnd && $currentDate >= $bookingStart;
            });

            if (!$nextBooking) {
                // No booking conflict, find how many consecutive days are available
                $availableStart = $currentDate->copy();
                
                // Find the next booking after current date
                $nextBookingAfter = $bookings->first(function ($booking) use ($currentDate) {
                    $bookingStart = Carbon::parse($booking->start_datetime);
                    return $bookingStart > $currentDate;
                });

                if ($nextBookingAfter) {
                    $availableEnd = Carbon::parse($nextBookingAfter->start_datetime);
                    $currentDate = Carbon::parse($nextBookingAfter->end_datetime);
                } else {
                    $availableEnd = $end->copy();
                    $currentDate = $end;
                }

                $availableRanges[] = [
                    'start_date' => $availableStart->toDateString(),
                    'end_date' => $availableEnd->toDateString(),
                    'available_nights' => $availableStart->diffInDays($availableEnd)
                ];
            } else {
                // Skip to after this booking
                $currentDate = Carbon::parse($nextBooking->end_datetime);
            }

            // Move to next day if we're still in the same date
            if ($currentDate <= $currentDate->copy()->addDay()) {
                $currentDate->addDay();
            }
        }

        return $availableRanges;
    }

    /**
     * Check multiple date ranges for availability
     */
    public function checkMultipleDateRanges($roomId, array $dateRanges)
    {
        $results = [];

        foreach ($dateRanges as $index => $dateRange) {
            $checkIn = $dateRange['check_in'] ?? null;
            $checkOut = $dateRange['check_out'] ?? null;

            if (!$checkIn || !$checkOut) {
                $results[$index] = [
                    'available' => false,
                    'message' => 'Invalid date range provided'
                ];
                continue;
            }

            $results[$index] = $this->getRoomAvailabilityDetails($roomId, $checkIn, $checkOut);
        }

        return $results;
    }
}