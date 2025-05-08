<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Activity;
use App\Models\Property;

class CreateTransaction extends Component
{
    use WithFileUploads;

    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';
    public $cart = []; // Keeps all the selected rooms and activities
    public $total_amount; // Total amount for the reservation
    public $total_pax = 0; // Total number of guests (adults + kids)
    public $check_in_date;
    public $check_out_date;



    // ----------------------- ROOMS ---------------------------- // 


    // rooms - adults - kids - extra-guest - extra-charge - amount
    public $rooms = [];
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = []; // extra_guest * extra_person_charge * days
    public $roomAmount = []; // base rate * days
    public $roomsTotalAmount = [];


    // --------------------- ACTIVITIES ------------------------- // 

    // activities - quantity - activity_datetime - activityAmount - status
    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];

    // ------------------- GUEST DETAIL ------------------------ // 
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $country;
    public $heard_from;


    // ------------------- INVOICE -------------------- // 

    public $invoice_number;
    public $showRoomModal = false;
    public $selectedRoomIds = [];


    public function mount()
    {
        $this->rooms = Property::ofType('Room')->get();
        $this->activities = Activity::all();
    }

    public function updated($property)
    {
        // --------------- CHECK-IN AND CHECK-OUT DATES ------------------- //
        if (in_array($property, ['check_in_date', 'check_out_date'])) {
            $this->getAvailableRooms();
        }
    }


    public function getAvailableRooms()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);
        $checkOut = \Carbon\Carbon::parse($this->check_out_date);

        $this->rooms = Property::ofType('Room')
            ->where('property_status', 'available')
            ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('start_datetime', '<', $checkOut)
                        ->where('end_datetime', '>', $checkIn);
                });
            })
            ->get();
    }




    public function render()
    {


        return view('livewire.admin.transactions.new-transaction.create-transaction');
    }
}
