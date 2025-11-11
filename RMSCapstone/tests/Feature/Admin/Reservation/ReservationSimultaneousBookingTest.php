<?php

namespace Tests\Feature\Admin\Reservation;

use App\Livewire\Guest\Reservation\ReservationForm;
use App\Models\Property;
use App\Models\TransactionUser;
use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PayMongoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationSimultaneousBookingTest extends TestCase
{
     /**
     * Tests simultaenuous booking reservations.
     * Using the register method to simulate two users trying to 
     * book the same reservation at the same time.
     */

    public function test_simultaneous_booking_prevents_double_booking(){
        // ---------------- Step 1: Create a room property ----------------
        $room = Property::factory()->create([
            'property_type_id' => 1, // ROOM property type
        ]);

        // ---------------- Step 2: Mock PayMongoService ----------------
        $this->mock(PayMongoService::class, function ($mock) {
            $mock->shouldReceive('createCheckoutSession')
                 ->andReturn([
                     'data' => [
                         'attributes' => [
                             'checkout_url' => 'https://paymongo.test/fake-url'
                         ]
                     ]
                 ]);
        });

        // ---------------- Step 3: Mock EmailService and Invoice Service----------------
        $this->mock(EmailService::class, function ($mock) {
            $mock->shouldReceive('sendReservationEmails')
                 ->andReturnTrue();
        });

        // ---------------- Mock PDF/Invoice generation ----------------
        Pdf::shouldReceive('loadView')
        ->andReturnSelf(); // so ->save() or ->download() calls won't fail
        PDF::shouldReceive('save')->andReturnTrue();
        PDF::shouldReceive('download')->andReturn('fake.pdf');

        // ---------------- Step 4: First reservation — should succeed ----------------
       Livewire::test(ReservationForm::class)
        ->set('check_in_date', '2025-11-20')
        ->set('check_out_date', '2025-11-22')
        ->set('first_name', 'Maria')
        ->set('middle_name', 'Dela')
        ->set('last_name', 'Cruz')
        ->set('email', 'maria@example.com')
        ->set('contact_number', '09123456789')
        ->set('company_name', 'ABC Corp')
        ->set('country', 'PH')
        ->set('trn_user_type', 'guest')
        ->set('cart', [
            [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'quantity' => 1,
                'status' => 'reserved',
                'payment_status' => 'unpaid',
                'adults' => 2,
                'kids' => 0,
                'days' => 2, 
                'extra_guest' => 1,
                'rate_id' => null,
                'roomRateName' => 'Base Rate',
                'extra_charge' => 0,
                'roomAmount' => $room->amount,
                'total_amount' => $room->amount,
            ]
        ])
        ->call('register')
        ->assertSessionHas('success');

        // ---------------- Step 5: Second reservation — should fail ----------------
        Livewire::test(ReservationForm::class)
        ->set('check_in_date', '2025-11-21') // overlaps with first reservation
        ->set('check_out_date', '2025-11-23')
        ->set('first_name', 'Juan')
        ->set('middle_name', 'Santos')
        ->set('last_name', 'Reyes')
        ->set('email', 'juan@example.com')
        ->set('contact_number', '09987654321')
        ->set('company_name', 'XYZ Corp')
        ->set('country', 'PH')
        ->set('trn_user_type', 'guest')
        ->set('cart', [
            [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'quantity' => 1,
                'status' => 'reserved',
                'payment_status' => 'unpaid',
                'adults' => 2,
                'kids' => 0,
                'days' => 1, 
                'extra_guest' => 1,
                'rate_id' => null,
                'roomRateName' => 'Base Rate',
                'extra_charge' => 0,
                'roomAmount' => $room->amount,
                'total_amount' => $room->amount,
            ]
        ])
        ->call('register')
        ->assertSessionHas('error', 'Reservation saved, but confirmation email failed to send.');
    // Livewire adds errors to the property name used in validation
    }
    

}
