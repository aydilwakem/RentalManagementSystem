<?php

namespace Tests\Feature\Admin\Reservation;

use App\Livewire\Guest\Reservation\ReservationForm;
use App\Mail\NewReservationMail;
use App\Mail\ReservationSubmittedMail;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class GuestReservationTest extends TestCase
{
    // -------------------- THIS TEST DOES NOT INCLUDE PAYMONGGO LINK
    //--------------------- IT ONLY TESTS GUEST RESERVATION PROCESS 


    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Http::fake([
            'https://api.paymongo.com/*' => Http::response([
                'data' => [
                    'attributes' => [
                        'checkout_url' => 'https://paymongo.test/checkout/abc123',
                    ]
                ]
            ], 200)
        ]);

        // Insert default Setting manually (not using factory)
        Setting::create([
            'company_name' => 'Test Company',
            'email' => 'test@example.com',
            'contact_number' => '09123456789',
            'address' => '123 Test St.',
            'payment_proof_expiration_hours' => 24,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_creates_transaction_user()
    {
        Livewire::test(ReservationForm::class)
            ->set('first_name', 'Maria')
            ->set('middle_name', 'Dela')
            ->set('last_name', 'Cruz')
            ->set('email', 'maria@example.com')
            ->set('contact_number', '09123456789')
            ->set('company_name', 'ABC Corp')
            ->set('country', 'PH')
            ->set('trn_user_type', 'guest')
            ->call('register');

        $this->assertDatabaseHas('trn_users', [
            'first_name' => 'Maria',
            'email' => 'maria@example.com',
        ]);
    }

     public function test_creates_transaction_with_correct_data()
    {
        $property = Property::factory()->create(['amount' => 1200]);

        $cart = [[
            'type' => 'room',
            'room_id' => $property->id,
            'room_name' => '101',
            'extra_guest' => 0,
            'days' => 2,
            'adults' => 2,
            'kids' => 1,
            'roomAmount' => 2400,
            'extra_charge' => 0,
            'total_amount' => 2400,
            'roomRateName' => 'Base Rate', 
        ]];

        Livewire::test(ReservationForm::class)
            ->set('first_name', 'Leo')
            ->set('last_name', 'Martinez')
            ->set('email', 'leo@example.com')
            ->set('contact_number', '09121234567')
            ->set('country', 'PH')
            ->set('trn_user_type', 'guest')
            ->set('reservation_type_id', 1)
            ->set('check_in_date', now()->addDay()->toDateString())
            ->set('check_out_date', now()->addDays(3)->toDateString())
            ->set('cart', $cart)
            ->set('total_pax', 3)
            ->set('heard_from', 'Facebook')
            ->set('reservation_source', 'Website')
            ->set('transaction_status', 'pending')
            ->set('terms', true)
            ->call('register');

        $this->assertDatabaseHas('trn_transactions', [
            'total_adults' => 2,
            'total_kids' => 1,
            'pax' => 3,
        ]);
    }

 
    public function test_creates_invoice_for_transaction()
    {
        $this->submitBasicReservation();

        $this->assertDatabaseHas('trn_invoice', [
            'invoice_type' => 'Room',
            'invoice_status' => 'pending',
        ]);
    }

   
    public function test_saves_guest_details_if_provided()
    {
        $this->submitBasicReservation([
            'guests' => [[
                'guest_first_name' => 'Sofia',
                'guest_middle_name' => '',
                'guest_last_name' => 'Reyes',
                'guest_suffix' => null,
                'guest_gender' => 'female',
                'guest_residency' => 'local',
                'guest_country_of_origin' => 'Philippines',
                'guest_type_id' => 1,
            ]]
        ]);

        $this->assertDatabaseHas('trn_guest_details', [
            'first_name' => 'Sofia',
            'guest_type_id' => 1,
        ]);
    }


    public function test_sends_confirmation_emails()
    {
        $this->submitBasicReservation();

        Mail::assertSent(ReservationSubmittedMail::class);
        Mail::assertSent(NewReservationMail::class);
    }

  
    public function test_redirects_to_payment_page_if_payment_link_exists()
    {
        Livewire::test(ReservationForm::class)
            ->set('first_name', 'Daniel')
            ->set('last_name', 'Santos')
            ->set('email', 'daniel@example.com')
            ->set('contact_number', '09999999999')
            ->set('country', 'PH')
            ->set('trn_user_type', 'guest')
            ->set('reservation_type_id', 1)
            ->set('check_in_date', now()->addDay()->toDateString())
            ->set('check_out_date', now()->addDays(2)->toDateString())
            ->set('cart', [[
                'type' => 'room',
                'room_id' => Property::factory()->create()->id,
                'room_name' => '101',
                'extra_guest' => 0,
                'days' => 1,
                'adults' => 2,
                'kids' => 0,
                'roomAmount' => 1500,
                'extra_charge' => 0,
                'total_amount' => 1500,
            ]])
            ->set('total_pax', 2)
            ->set('terms', true)
            ->set('heard_from', 'Google')
            ->set('transaction_status', 'pending')
            ->call('register')
            ->assertRedirect('http://localhost/guest/proof-of-payment-page');
    }

    /** Helper to submit a basic reservation with optional overrides */
    private function submitBasicReservation(array $overrides = [])
    {
        $cart = $overrides['cart'] ?? [[
            'type' => 'room',
            'room_id' => Property::factory()->create(['amount' => 1500])->id,
            'room_name' => '101',
            'extra_guest' => 0,
            'days' => 1,
            'adults' => 2,
            'kids' => 0,
            'roomAmount' => 1500,
            'extra_charge' => 0,
            'total_amount' => 1500,
        ]];

        Livewire::test(ReservationForm::class)
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@example.com')
            ->set('contact_number', '09123456789')
            ->set('country', 'PH')
            ->set('trn_user_type', 'guest')
            ->set('reservation_type_id', 1)
            ->set('check_in_date', now()->addDay()->toDateString())
            ->set('check_out_date', now()->addDays(2)->toDateString())
            ->set('cart', $cart)
            ->set('guests', $overrides['guests'] ?? [])
            ->set('total_pax', 2)
            ->set('terms', true)
            ->set('heard_from', 'Google')
            ->set('transaction_status', 'pending')
            ->call('register');
    }
}

