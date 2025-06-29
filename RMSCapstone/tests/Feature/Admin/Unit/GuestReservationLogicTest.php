<?php

namespace Tests\Feature\Admin\Unit;

use App\Livewire\Guest\Reservation\ReservationForm;
use App\Models\Activity;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class GuestReservationLogicTest extends TestCase
{
    public function test_it_updates_cart_when_adults_or_kids_are_updated()
    {
        
    $propertyCategory = PropertyCategory::factory()->create();

    $room = Property::factory()->create([
        'name_number' => 'Deluxe Room',
        'amount' => 1500,
        'ideal_guest' => 2,
        'extra_person_charge' => 300,
        'property_type_id' => 1,
        'property_category_id' => $propertyCategory->id,
    ]);

    Livewire::test(ReservationForm::class)
        ->set('check_in_date', now()->toDateString()) // set these first for accurate duration
        ->set('check_out_date', now()->addDays(2)->toDateString())
        ->set('cart', [
            [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'adults' => 2,
                'kids' => 0,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'roomAmount' => 0,
                'total_amount' => 0,
            ]
        ])
        // These two lines trigger the updated() lifecycle method internally
        ->set('adults.' . $room->id, 3)
        ->set('kids.' . $room->id, 1)
        ->assertSet("cart.0.adults", 3)
        ->assertSet("cart.0.kids", 1)
        ->assertSet("cart.0.extra_guest", 2)
        ->assertSet("cart.0.extra_charge", 1200) // 2 guests * 300 * 2 nights
        ->assertSet("cart.0.roomAmount", 3000)   // 1500 * 2 nights
        ->assertSet("cart.0.total_amount", 4200);
    }

    public function test_it_resets_cart_and_step_when_checkin_or_checkout_is_updated()
    {
       $propertyCategory = PropertyCategory::factory()->create();

    $room = Property::factory()->create([
        'name_number' => 'Test Room',
        'property_type_id' => 1,
        'property_category_id' => $propertyCategory->id,
        'amount' => 1000,
        'ideal_guest' => 2,
        'extra_person_charge' => 300,
    ]);

    $cartItem = [
        'type' => 'room',
        'room_id' => $room->id,
        'room_name' => $room->name_number,
        'adults' => 2,
        'kids' => 0,
        'extra_guest' => 0,
        'extra_charge' => 0,
        'roomAmount' => 0,
        'total_amount' => 0,
    ];

    Livewire::test(ReservationForm::class)
        ->set('cart', [$cartItem])
        ->set('currentStep', 2)
        ->set('check_in_date', now()->toDateString()) // triggers updated()
        ->assertSet('cart', [])
        ->assertSet('currentStep', 1);

    Livewire::test(ReservationForm::class)
        ->set('cart', [$cartItem])
        ->set('currentStep', 2)
        ->set('check_out_date', now()->addDay()->toDateString()) // triggers updated()
        ->assertSet('cart', [])
        ->assertSet('currentStep', 1);
    }

    public function test_it_handles_invalid_room_id_on_updated()
    {
        Livewire::test(ReservationForm::class)
        ->set('adults.9999', 2) // Triggers updated() for 'adults.9999'
        ->assertHasErrors('cart');
    }

    public function test_it_computes_stay_duration_correctly()
    {
       $component = Livewire::test(ReservationForm::class)
        ->set('check_in_date', '2025-07-01')
        ->set('check_out_date', '2025-07-04');

        // Call the computed property
        $stayDuration = $component->instance()->getStayDurationProperty();

        $this->assertEquals(3, $stayDuration);
    }

    public function test_it_returns_zero_if_check_out_is_before_check_in()
    {
       $component = Livewire::test(ReservationForm::class)
            ->set('check_in_date', '2025-07-05')
            ->set('check_out_date', '2025-07-04');
    
            $stayDuration = $component->instance()->getStayDurationProperty();

        $this->assertEquals(0, $stayDuration);
    }

    public function test_it_computes_total_pax_correctly()
        {
            Livewire::test(ReservationForm::class)
            ->set('cart', [
            ['type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000],
            ['type' => 'room', 'room_id' => 2, 'room_name' => 'Family Suite', 'adults' => 1, 'kids' => 2, 'extra_charge' => 200, 'total_amount' => 4000],
            ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk' , 'quantity' => 3, 'amount' => 1000,]
            ])
            ->call('computeTotalPax')
            ->assertSet('total_pax', 6); // (2+1) + (1+2)
    }

    public function test_it_computes_total_amount_of_all_rooms()
    {
        $component = Livewire::test(ReservationForm::class)
        ->set('cart', [
            ['type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000],
            ['type' => 'room', 'room_id' => 2, 'room_name' => 'Family Suite', 'adults' => 1, 'kids' => 2, 'extra_charge' => 200, 'total_amount' => 4000],
            ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 1000],
        ]);

        $totalAmount = $component->instance()->computeTotalAmountOfAllRooms();

        $this->assertEquals(8000, $totalAmount);
    }

    public function test_it_computes_total_amount_of_all_activities()
    {
        $component = Livewire::test(ReservationForm::class)
            ->set('cart', [
                ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 1000],
                ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 1000],
                ['type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000],

            ]);
            
            $totalAmount = $component->instance()->computeTotalAmountOfAllActivities();

        $this->assertEquals(2000, $totalAmount);
    }

    public function test_it_computes_total_amount_correctly()
    {
       $component =  Livewire::test(ReservationForm::class)
            ->set('cart', [
                ['type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000],
                ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 1000],

            ]);
            
        $totalAmount = $component->instance()->computeTotalAmount();

        $this->assertEquals(5000, $totalAmount);
    }

    public function test_it_computes_deposit_correctly()
    {
        DB::table('st_settings')->insert([
            'deposit_percentage' => 50,
            'company_name' => 'Test Company',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $component =  Livewire::test(ReservationForm::class)
            ->set('cart', [
                ['type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000],
                ['type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 1000],
            ]);
            
        $depositAmount = $component->instance()->getDepositProperty();

        $this->assertEquals(2500, $depositAmount);
    }

    public function test_adds_room_to_cart_with_correct_amounts()
    {
        $room = Property::factory()->create([
            'name_number' => '101',
            'property_type_id' => 2,
            'property_category_id' => 2,
            'ideal_guest' => 2,
            'amount' => 1500,
            'extra_person_charge' => 500,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('check_in_date', now()->addDay()->toDateString())
            ->set('check_out_date', now()->addDays(3)->toDateString()) // stayDuration = 2
            ->set("adults.{$room->id}", 3)
            ->set("kids.{$room->id}", 1)
            ->call('addRoomToCart', $room->id)
            ->assertHasNoErrors()
            ->assertSet('cart.0.total_amount', 5000); // 3000 + 2000
    }

    public function test_does_not_add_duplicate_room()
    {
        $room = Property::factory()->create();

        Livewire::test(ReservationForm::class)
            ->set('check_in_date', now()->addDay()->toDateString())
            ->set('check_out_date', now()->addDays(2)->toDateString())
            ->set("adults.{$room->id}", 2)
            ->set("cart", [[
                'type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000,
                'room_id' => $room->id,
            ]])
            ->call('addRoomToCart', $room->id)
            ->assertHasErrors('cart');
    }

    public function test_add_room_requires_checkin_and_checkout()
    {
        $room = Property::factory()->create();

        Livewire::test(ReservationForm::class)
            ->call('addRoomToCart', $room->id)
            ->assertHasErrors('cart');
    }

    public function test_adds_activity_to_cart()
    {
        $activity = Activity::factory()->create(['amount' => 300]);

        Livewire::test(ReservationForm::class)
            ->set("quantity.{$activity->id}", 2)
            ->call('addActivityToCart', $activity->id)
            ->assertHasNoErrors()
            ->assertSet('cart.0.amount', 600);
    }

    public function test_increments_activity_quantity_up_to_total_pax()
    {
        $activity = Activity::factory()->create(['amount' => 100]);

        Livewire::test(ReservationForm::class)
            ->set('total_pax', 3)
            ->set("quantity.{$activity->id}", 1)
            ->set("cart", [[
                'type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 100,
                'activity_id' => $activity->id,
                'quantity' => 1,
                'amount' => 100,
            ]])
            ->call('incrementActivity', $activity->id)
            ->assertSet("cart.0.quantity", 2)
            ->assertSet("cart.0.amount", 200);
    }

    public function test_decrements_activity_quantity_but_not_below_one()
    {
        $activity = Activity::factory()->create(['amount' => 100]);

        Livewire::test(ReservationForm::class)
            ->set("quantity.{$activity->id}", 1)
            ->set("cart", [[
                'type' => 'activity', 'activity_id' => 48, 'activity_name' => 'Nature Walk', 'quantity' => 3, 'amount' => 100,
                'activity_id' => $activity->id,
                'quantity' => 1,
                'amount' => 100,
            ]])
            ->call('decrementActivity', $activity->id)
            ->assertSet("cart.0.quantity", 1)
            ->assertSet("cart.0.amount", 100);
    }

    public function test_adds_guest_to_guest_array()
    {
        Livewire::test(ReservationForm::class)
            ->set('guest_first_name', 'Anna')
            ->set('guest_middle_name', 'B.')
            ->set('guest_last_name', 'Cruz')
            ->set('guest_suffix', 'Jr.')
            ->set('guest_gender', 'female')
            ->set('guest_residency', 'local')
            ->set('guest_country_of_origin', 'Philippines')
            ->set('guest_type_id', 1)
            ->call('addMultipleGuests')
            ->assertSet('guests.0.guest_first_name', 'Anna');
    }

    public function test_edits_and_updates_guest()
    {
        Livewire::test(ReservationForm::class)
            ->set('guests', [[
                'guest_first_name' => 'Mark',
                'guest_last_name' => 'Lee',
                'guest_type_id' => 1,
            ]])
            ->call('editGuest', 0)
            ->assertSet('editingGuest.guest_first_name', 'Mark')
            ->set('editingGuest.guest_first_name', 'Marcus')
            ->call('updateGuest')
            ->assertSet('guests.0.guest_first_name', 'Marcus');
    }

    public function test_deletes_guest_by_index()
    {
        Livewire::test(ReservationForm::class)
            ->set('guests', [[
                'guest_first_name' => 'Test',
                'guest_last_name' => 'Tester',
                'guest_type_id' => 1,
            ]])
            ->call('deleteGuest', 0)
            ->assertSet('guests', []);
    }

    public function test_removes_room_from_cart_and_resets_if_empty()
    {
        $property = Property::factory()->create();

        Livewire::test(ReservationForm::class)
            ->set('cart', [[
                'type' => 'room', 'room_id' => 1, 'room_name' => 'Deluxe Room', 'adults' => 2, 'kids' => 1, 'extra_charge' => 0, 'total_amount' => 4000,
                'room_id' => $property->id,
            ]])
            ->call('removeFromCart', 'room', $property->id)
            ->assertSet('cart', [])
            ->assertSet('currentStep', 1);
    }

}
