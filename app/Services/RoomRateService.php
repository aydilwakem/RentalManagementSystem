<?php

namespace App\Services;

use App\Models\RoomRate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RoomRateService
{
    public function getDynamicRate($room, $checkInDate = null)
    {
        $date = $checkInDate ? Carbon::parse($checkInDate) : now();
        $dayOfWeek = $date->dayOfWeek;

        $rate = $this->getRate($room->id, 'Peak', $date)
            ?? $this->getRate($room->id, 'Holiday', $date)
            ?? $this->getSpecificDayRate($room->id, $dayOfWeek, $date); // Use the new specific day logic

        return $rate
            ? $this->formatRate($rate)
            : [
                'rate_id' => null,
                'amount' => $room->amount,
                'name' => 'Base Rate',
                'rate_type' => null,
            ];
    }

    /**
     * Get rate based on specific day logic:
     * - Sunday to Thursday: Weekday rate (or Base rate if no weekday rate exists)
     * - Friday to Saturday: Weekend rate
     */
    protected function getSpecificDayRate($propertyId, $dayOfWeek, $date)
    {
        // Friday (5) and Saturday (6): Weekend rate
        if (in_array($dayOfWeek, [5, 6])) {
            return $this->getRate($propertyId, 'Weekend', $date);
        }
        
        // Sunday (0) to Thursday (4): Weekday rate
        return $this->getRate($propertyId, 'Weekdays', $date);
    }

    /**
     * Calculate total rate for a stay period considering different rates per day
     * Only counts nights actually stayed (check-in day to check-out day minus 1)
     */
    public function calculateTotalRateForStay($room, $checkInDate, $checkOutDate)
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        $nights = $checkIn->diffInDays($checkOut);
        
        if ($nights <= 0) {
            return 0;
        }

        $totalAmount = 0;
        
        // Calculate rate for each night stayed (excludes check-out day)
        for ($i = 0; $i < $nights; $i++) {
            $currentDate = $checkIn->copy()->addDays($i);
            $rate = $this->getDynamicRate($room, $currentDate);
            $totalAmount += $rate['amount'];
        }

        return $totalAmount;
    }

    /**
     * Get detailed rate breakdown for a stay period
     * Only counts nights actually stayed (excludes check-out day)
     */
    public function getRateBreakdown($room, $checkInDate, $checkOutDate)
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        
        // Calculate nights properly: from check-in date to day before check-out
        $nights = $checkIn->diffInDays($checkOut);
        
        if ($nights <= 0) {
            return [
                'breakdown' => [],
                'total_amount' => 0,
                'nights' => 0,
                'stay_period' => 'No stay period',
            ];
        }

        $breakdown = [];
        $totalAmount = 0;
        
        // Calculate rate for each night stayed
        for ($i = 0; $i < $nights; $i++) {
            $currentDate = $checkIn->copy()->addDays($i);
            $rate = $this->getDynamicRate($room, $currentDate);
            
            $breakdown[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->format('l'),
                'rate_type' => $rate['rate_type'] ?? 'Base Rate',
                'rate_name' => $rate['name'],
                'amount' => $rate['amount'],
                'rate_id' => $rate['rate_id'],
                'is_weekend' => $this->isWeekend($currentDate),
                'night_number' => $i + 1, // Night 1, Night 2, etc.
            ];
            
            $totalAmount += $rate['amount'];
        }

        // Create a descriptive stay period string
        $stayPeriod = $checkIn->format('M j, Y') . ' (' . $checkIn->format('l') . ') to ' . 
                     $checkOut->format('M j, Y') . ' (' . $checkOut->format('l') . ') - ' . 
                     $nights . ' night' . ($nights > 1 ? 's' : '');

        return [
            'breakdown' => $breakdown,
            'total_amount' => $totalAmount,
            'nights' => $nights,
            'stay_period' => $stayPeriod,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
        ];
    }

    /**
     * Get a summary of rates by type for display
     */
    public function getRateSummary($room, $checkInDate, $checkOutDate)
    {
        $breakdown = $this->getRateBreakdown($room, $checkInDate, $checkOutDate);
        
        if (empty($breakdown['breakdown'])) {
            return [
                'summary' => [],
                'total_amount' => 0,
                'nights' => 0,
            ];
        }

        $summary = [];
        $groupedRates = collect($breakdown['breakdown'])->groupBy('rate_type');
        
        foreach ($groupedRates as $rateType => $days) {
            $count = count($days);
            $total = $days->sum('amount');
            $average = $count > 0 ? $total / $count : 0;
            
            $summary[] = [
                'rate_type' => $rateType,
                'nights' => $count,
                'total_amount' => $total,
                'average_rate' => $average,
                'days' => $days->pluck('day_name')->unique()->implode(', '),
                'date_range' => $days->pluck('date')->unique()->values()->all(),
            ];
        }

        return [
            'summary' => $summary,
            'total_amount' => $breakdown['total_amount'],
            'nights' => $breakdown['nights'],
            'stay_period' => $breakdown['stay_period'],
        ];
    }

    protected function getRate($propertyId, $rateType, $date)
    {
        return RoomRate::where('property_id', $propertyId)
            ->where('rate_type', $rateType)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * Check if a specific day of week is weekend (Friday or Saturday)
     */
    protected function isWeekendByDay($dayOfWeek)
    {
        return in_array($dayOfWeek, [5, 6]); // Friday=5, Saturday=6
    }

    /**
     * Check if a Carbon date is weekend (Friday or Saturday)
     */
    protected function isWeekend($date)
    {
        return $this->isWeekendByDay($date->dayOfWeek);
    }

    protected function formatRate($rate)
    {
        $rateName = $rate->name ?? ucfirst($rate->rate_type) . ' Rate';
        return [
            'rate_id' => $rate->id,
            'amount' => $rate->amount,
            'name' => $rateName,
            'rate_type' => $rate->rate_type,
        ];
    }

    /**
     * Get all rates that will be applied during the stay period
     */
    public function getAppliedRatesForStay($room, $checkInDate, $checkOutDate)
    {
        $breakdown = $this->getRateBreakdown($room, $checkInDate, $checkOutDate);
        
        if (empty($breakdown['breakdown'])) {
            return [];
        }
        
        // Group by rate type and calculate summary
        $appliedRates = collect($breakdown['breakdown'])
            ->groupBy('rate_type')
            ->map(function($days, $rateType) {
                $firstDay = $days->first();
                $count = $days->count();
                $total = $days->sum('amount');
                $average = $count > 0 ? $total / $count : 0;
                
                return [
                    'rate_type' => $rateType,
                    'name' => $firstDay['rate_name'],
                    'nights' => $count,
                    'total_amount' => $total,
                    'average_rate' => $average,
                    'percentage' => 0, // calculate percentage if needed
                ];
            })
            ->values()
            ->toArray();
        
        return $appliedRates;
    }
}