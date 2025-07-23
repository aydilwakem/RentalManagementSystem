<?php

namespace App\Services;

use App\Models\RoomRate;
use Carbon\Carbon;

class RoomRateService
{
    public function getDynamicRate($room, $checkInDate = null)
    {
        $date = $checkInDate ? Carbon::parse($checkInDate) : now();
        $dayOfWeek = $date->dayOfWeek;

        $rate = $this->getRate($room->id, 'Peak', $date)
            ?? $this->getRate($room->id, 'Holiday', $date)
            ?? $this->getRate($room->id, $this->getDayRateType($dayOfWeek), $date);

        return $rate
            ? $this->formatRate($rate)
            : [
                'rate_id' => null,
                'amount' => $room->amount,
                'name' => 'Base Rate',
                'rate_type' => null,
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

    protected function getDayRateType($dayOfWeek)
    {
        return in_array($dayOfWeek, [0, 6]) ? 'Weekend' : 'Weekdays';
    }

    protected function formatRate($rate)
    {
        return [
            'rate_id' => $rate->id,
            'amount' => $rate->amount,
            'name' => $rate->name ?? $rate->rate_type . ' Rate',
            'rate_type' => $rate->rate_type,
        ];
    }
}
