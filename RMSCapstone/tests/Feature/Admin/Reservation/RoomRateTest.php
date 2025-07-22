<?php

namespace Tests\Feature\Admin\Reservation;

use Tests\TestCase;
use App\Services\RoomRateService;
use App\Models\Property;
use App\Models\RoomRate;
use Carbon\Carbon;

class RoomRateTest extends TestCase
{


    /** @test */
    public function it_returns_peak_rate_for_a_room()
    {
        $room = Property::where('type', 'Room')->firstOrFail();

        $date = Carbon::today();
        $rate = RoomRate::where('property_id', $room->id)
            ->where('rate_type', 'Peak')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        $this->assertNotNull($rate, 'Expected a Peak rate to exist for the test');

        $roomRateService = new RoomRateService();
        $result = $roomRateService->getDynamicRate($room, $date->toDateString());

        $this->assertEquals($rate->id, $result['rate_id']);
        $this->assertEquals($rate->amount, $result['amount']);
        $this->assertEquals('Peak', $result['rate_type']);
    }

    /** @test */
    public function it_returns_base_rate_if_no_rate_found()
    {
        $room = Property::where('type', 'Room')->firstOrFail();

        // pick a date you know has no rate
        $date = Carbon::create(2030, 1, 1);

        $roomRateService = new RoomRateService();
        $result = $roomRateService->getDynamicRate($room, $date->toDateString());

        $this->assertNull($result['rate_id']);
        $this->assertEquals($room->amount, $result['amount']);
        $this->assertEquals('Base Rate', $result['name']);
        $this->assertNull($result['rate_type']);
    }
}
