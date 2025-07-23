<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasFormattedDates
{
    public function getFormattedCheckInDate()
    {
        return $this->check_in_date ? Carbon::parse($this->check_in_date)->format('F j, Y') : '';
    }

    public function getFormattedCheckOutDate()
    {
        return $this->check_out_date ? Carbon::parse($this->check_out_date)->format('F j, Y') : '';
    }
}
