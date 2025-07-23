<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ReservationHelpers
{
    public function getStayDuration($checkIn, $checkOut): int
    {
        if ($checkIn && $checkOut) {
            $in = Carbon::parse($checkIn);
            $out = Carbon::parse($checkOut);

            return $out->greaterThan($in) ? $in->diffInDays($out) : 0;
        }

        return 0;
    }

    public function getDeposit(float $totalAmount): float
    {
        $setting = DB::table('st_settings')->first();

        if (!$setting || !$setting->enable_deposit_percentage) {
            return 0;
        }

        $depositPercentage = $setting->deposit_percentage ?? 0;

        return $totalAmount * ($depositPercentage / 100);
    }
}
