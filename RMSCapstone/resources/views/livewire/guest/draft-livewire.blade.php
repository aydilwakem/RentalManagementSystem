<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Property;
use App\Models\Activity;
use App\Models\TransactionUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationForm extends Component
{
    //-------------------------- Step 1 --------------------------------- //

    public $reservation_type_id = 2; // Room
    public $trn_user_type = 'guest';
    public $property_id; // room_id
    public $check_in_date = '';
    public $check_out_date = '';
    public $pax = 0;
    public $total_amount = 0;
    public $total_kids;
    public $total_adults;

    //-------------------------- Step 2 --------------------------------- //
    public $activity_id;

    //-------------------------- Step 3 --------------------------------- //
    public $first_name;
    public $last_name;
    public $email;

    //-------------------------- Dropdowns --------------------------------- //
    public $rooms;
    public $activities;

    public $debug;



    public $totalSteps = 3;
    public $currentStep = 1;

    public function mount()
    {
        // Fetch all Activities
        $this->activities = Activity::all();
        $this->rooms = Property::ofType('Room')->where('property_status', 'available')->get();
        $this->currentStep = 1;
    }

    public function increaseStep()
    {
        $this->resetErrorBag(); // Clears previous validation error messages stored in the component

        $this->validateData(); // Runs the validateData function before proceeding to the next step

        $this->currentStep++;
        if ($this->currentStep > $this->totalSteps) {
            $this->currentStep = $this->totalSteps;
        }
    }

    public function decreaseStep()
    {
        $this->currentStep--;
        if ($this->currentStep < 1) {
            $this->currentStep = 1;
        }
    }

    public function updated($property)
    {
        if (in_array($property, ['check_in_date', 'check_out_date'])) {
            $this->getAvailableRooms();
        }

        if (in_array($property, ['property_id', 'activity_id'])) {
            $this->calculateTotalAmount();
        }

        if (in_array($property, ['total_adults', 'total_kids'])) {
            $this->calculateTotalPax();
        }
    }

    // Get available rooms according to dates
    public function getAvailableRooms()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);
        $checkOut = \Carbon\Carbon::parse($this->check_out_date);

        $this->rooms = Property::ofType('Room')
            ->where('property_status', 'available')
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                });
            })
            ->get();
    }

    // Calculates the total amount based on selected room and activity
    public function calculateTotalAmount()
    {
        // Get the room rate if a property is selected, otherwise default to 0
        $roomRate = $this->property_id ? Property::find($this->property_id)?->amount ?? 0 : 0;

        // Get the activity rate if an activity is selected, otherwise default to 0
        $activityRate = $this->activity_id ? Activity::find($this->activity_id)?->amount ?? 0 : 0;

        // Set the total amount as the sum of room and activity rates
        $this->total_amount = $roomRate + $activityRate;
    }

    // Calculates the total number of people (pax) from adults and kids
    public function calculateTotalPax()
    {
        $adults = is_numeric($this->total_adults) ? (int) $this->total_adults : 0;
        $kids = is_numeric($this->total_kids) ? (int) $this->total_kids : 0;

        $this->pax = $adults + $kids;
    }

    public function selectRoom($roomId)
    {
        $this->room_id = $roomId; // Set the room_id to the selected room's ID
    }

    public function removeRoom()
    {
        $this->room_id = null; // Clear the selected room ID
    }


    public function validateData()
    {
        // Step 1 - Book a Room
        if ($this->currentStep == 1) {
            $this->validate([
                'property_id' => 'required|exists:properties,id',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_adults' => 'required|integer|min:1',
                'total_kids' => 'nullable|integer|min:0',
                'pax' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
            ]);
        }

        // Step 2 - Choose Activity
        elseif ($this->currentStep == 2) {
            $this->validate([
                'activity_id' => 'nullable|integer|exists:prd_activities,id',
            ]);
        }
    }

    public function register()
    {
        $this->resetErrorBag();

        // Step 3 - Enter Guest Details
        if ($this->currentStep == 3) {
            $this->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
            ]);
        }

        DB::transaction(function () {
            $transactionUser = TransactionUser::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'trn_user_type' => $this->trn_user_type,
            ]);

            Transaction::create([
                'property_id' => $this->property_id,
                'reservation_type_id' => $this->reservation_type_id,
                'created_by' => $transactionUser->id,
                'activity_id' => $this->activity_id,
                'check_in_date' => $this->check_in_date,
                'check_out_date' => $this->check_out_date,
                'total_adults' => $this->total_adults,
                'total_kids' => $this->total_kids,
                'pax' => $this->pax,
                'total_amount' => $this->total_amount,
            ]);
        });

        session()->flash('success', 'Registration completed successfully!');

        // Redirect back to reservations list
        return redirect()->route('guest.reservation-form');
    }

    public function render()
    {
        return view('livewire.guest.reservation-form', [
            'rooms' => $this->rooms,
            'activities' => $this->activities,
        ]);
    }
}
