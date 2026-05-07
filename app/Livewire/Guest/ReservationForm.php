<?php


// ------------------------- NOT THE CORRECT FILE ------------------------------- //

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\Activity;
use App\Models\RoomRate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationForm extends Component
{
    use WithFileUploads;
    public $reservation_type_id = 2; // Room
    public $trn_user_type = 'guest';

    //-------------------------- Step 1 --------------------------------- //

    public $room_id; // room_id
    public $check_in_date = '';
    public $check_out_date = '';
    public $total_adults_by_room = [];
    public $total_kids_by_room = [];
    public $pax = 0; // Total Pax
    public $total_amount = 0;
    public $total_adults = 0; // Default value
    public $total_kids = 0;    // Default value
    public $selectedRoom; // Declare the selectedRoom property
    //-------------------------- Step 2 --------------------------------- //
    public $activity_id;
    public $selectedActivity;

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
        // $this->rooms = Property::ofType('Room')->where('property_status', 'available')->get();

        $this->rooms = Property::ofType('Room')
            ->where('property_status', 'available')
            ->get()
            ->map(function ($room) {
                $room->dynamic_rate = $this->getDynamicRate($room);
                return $room;
            });

        $this->activities = Activity::all();
        $this->currentStep = 1;
    }

    public function getDynamicRate($room)
    {
        $date = now(); // or use a selected check-in date if available
        $dayOfWeek = $date->dayOfWeek; // 0 = Sun, 6 = Sat

        $rateType = ($dayOfWeek === 0 || $dayOfWeek === 6) ? 'Weekend' : 'Weekdays';

        $rate = RoomRate::where('property_id', $room->id)
            ->where('rate_type', $rateType)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereNull('deleted_at')
            ->first();

        return $rate ? $rate->amount : $room->amount;
    }

    public function increaseStep()
    {
        $this->resetErrorBag();
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
        // Trigger the calculation of pax whenever the number of adults or kids changes
        if (strpos($property, 'total_adults_by_room') !== false || strpos($property, 'total_kids_by_room') !== false) {
            $this->calculateTotalPax();
        }

        // Other update logic
        if (in_array($property, ['check_in_date', 'check_out_date'])) {
            $this->getAvailableRooms();
        }

        if (in_array($property, ['room_id', 'activity_id'])) {
            $this->calculateTotalAmount();
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


    // Modified function to calculate pax based on the selected room
    public function calculateTotalPax()
    {
        // Loop through the rooms and calculate the total adults and kids for the updated room
        $totalAdults = 0;
        $totalKids = 0;

        // Check if the update was for a specific room
        foreach ($this->total_adults_by_room as $roomId => $adults) {
            // Get the number of adults for this room
            $totalAdults = (int) ($adults);
        }

        foreach ($this->total_kids_by_room as $roomId => $kids) {
            // Get the number of kids for this room
            $totalKids = (int) ($kids);
        }

        // Calculate the total pax (adults + kids) for the updated room
        $this->pax = $totalAdults + $totalKids;
    }



    public function addToCart($roomId)
    {

        $this->selectedRoom = Property::find($roomId);
        $this->calculateTotalAmount();
        $this->calculateTotalPax();
    }

    public function addActivity($activityId)
    {
        $this->selectedActivity = Activity::find($activityId);
        $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        $roomRate = $this->selectedRoom ? $this->selectedRoom->amount : 0;
        $activityRate = $this->selectedActivity ? Activity::find($this->activity_id)?->amount ?? 0 : 0;
        $this->total_amount = $roomRate + $activityRate;
    }





    public function removeRoom()
    {
        $this->selectedRoom = null;
        $this->calculateTotalAmount();
    }

    public function removeActivity()
    {
        $this->selectedActivity = null;
        $this->calculateTotalAmount();
    }







    public function validateData()
    {
        // Step 1 - Book a Room
        if ($this->currentStep == 1) {
            $this->validate([
                'room_' => 'required|exists:properties,id',
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
        return view('livewire.guest.rooms', [
            'rooms' => $this->rooms,
            'activities' => $this->activities,
        ]);
    }
}
