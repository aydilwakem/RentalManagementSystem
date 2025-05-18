<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\TransactionUser;
use App\Models\GuestDetail;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSubmittedMail;
use App\Models\PaymentMethod;
use App\Models\GuestType;
use Illuminate\Support\Facades\Log;

class CreateReservation extends Component
{

    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';

    // Room related public properties
    public $check_in_date;
    public $check_out_date;
    public $rooms = [];
    public $adults = [];
    public $kids = [];
    public $total_pax = 0;
    public $roomsTotalAmount = [];
    public $extra_guest = [];
    public $extra_charge = []; // extra_guest * extra_person_charge * days
    public $roomAmount = []; // base rate * days


    // Activity related public properties

    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];
    public $activityImage;
    public $activityDescription;

    // Primary Guest related public properties

    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $country;
    public $heard_from;
    public $terms = 1;

    // Additional Guest related public properties

    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin;

    public $guest_types = [];
    public $guests = [];
    public $editingGuestIndex = null;

    public $editingGuest = [
        'guest_first_name' => '',
        'guest_middle_name' => '',
        'guest_last_name' => '',
        'guest_suffix' => '',
        'guest_type_id' => '',
        'guest_gender' => '',
        'guest_residency' => '',
        'guest_country_of_origin' => '',
    ];

    // Invoice related public properties
    public $invoice_number;
    public $paymentMethod;

    // Setting related public properties
    public $expirationHours;


    // Selected Items (Rooms and Activities)

    public $selectedRooms = [];
    public $selectedActivities = [];

    // Modals (Rooms and Activities)
    public $roomModal = false;
    public $activityModal = false;
    public $showGuestModal = false;
    public $showEditModal = false;

    public $addRoomFirstModal = false;


    // Summary
    public $total_amount;




    // ------------------------- RENDER --------------------------- //

    public function render()
    {
        return view('livewire.admin.reservations.create-reservation');
    }







    // ------------------------- MOUNT ----------------------------- //
    public function mount()
    {

        Log::info('Mount is called.');

        $this->rooms = Property::ofType('Room')->availableRooms()->get();
        $this->activities = Activity::availableActivities()->get();
        $this->paymentMethod = PaymentMethod::all();
        $this->guest_types = GuestType::all();
    }


    // ------------------ UPDATED PROPERTIES ---------------------- //
    public function updated($property)
    {
        Log::info('Adults and Kids are changed.');

        // ---------------------------- ADULTS AND KIDS -------------------------- //
        if (Str::startsWith($property, 'adults.') || Str::startsWith($property, 'kids.')) {

            $roomId = explode('.', $property)[1];

            $room = Property::find($roomId);
            if (!$room) {
                $this->addError('selectedRooms', 'Room not found.');
                return;
            }

            foreach ($this->selectedRooms as $index => $item) {
                if ($item['room_id'] == $roomId) {

                    $adults = (int) ($this->adults[$roomId] ?? 1);
                    $kids = (int) ($this->kids[$roomId] ?? 0);
                    $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
                    $stayDuration = $this->getStayDurationProperty();
                    $roomAmount = $room->amount * $stayDuration;
                    $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;

                    $this->selectedRooms[$index]['adults'] = $adults;
                    $this->selectedRooms[$index]['kids'] = $kids;
                    $this->selectedRooms[$index]['extra_guest'] = $extraGuests;
                    $this->selectedRooms[$index]['extra_charge'] = $extraCharge;
                    $this->selectedRooms[$index]['roomAmount'] = $roomAmount;
                    $this->selectedRooms[$index]['total_amount'] = $roomAmount + $extraCharge;
                }
            }

            // Triggers compute total pax method
            $this->computeTotalPax();
        }

        Log::info('Dates are changed.');

        // --------------- CHECK-IN AND CHECK-OUT DATES ------------------- //
        if (in_array($property, ['check_in_date', 'check_out_date'])) {

            $this->getAvailableRooms();
        }
    }






    // ------------------------- MODALS ----------------------------- //
    public function OpenRoomModal()
    {
        Log::info('Room Modal is opened.');

        // Resets any previous error messages
        $this->resetErrorBag();

        $this->roomModal = true;
    }

    public function OpenActivityModal()
    {
        Log::info('Activity Modal request received.');

        // Check if selectedRooms is empty
        if (empty($this->selectedRooms) || count($this->selectedRooms) === 0) {
            $this->addRoomFirstModal = true;  // Show modal to add rooms first
        } else {
            $this->activityModal = true;      // Show activity modal
        }
    }


    public function openGuestModal()
    {
        $this->showGuestModal = true;
    }

    public function closeGuestModal()
    {
        $this->showGuestModal = false;
    }






    // ------------------------- COMPUTED PROPERTIES ----------------------------- //

    public function computeTotalPax()
    {
        $total = 0;

        foreach ($this->selectedRooms as $item) {

            $adults = (int) ($item['adults'] ?? 1);
            $kids = (int) ($item['kids'] ?? 0);

            $guestsInRoom = $adults + $kids;

            $total += $guestsInRoom;
        }

        $this->total_pax = $total;
    }

    public function computeTotalAmountOfAllRooms()
    {
        $total = 0;

        foreach ($this->selectedRooms as $item) {
            $total += $item['total_amount'];
        }

        return $total;
    }

    public function computeTotalAmountOfAllActivities()
    {
        $total = 0;

        foreach ($this->selectedActivities as $item) {
            $total += $item['amount'];
        }

        return $total;
    }

    public function computeTotalAmount()
    {
        $this->total_amount = $this->computeTotalAmountOfAllRooms() + $this->computeTotalAmountOfAllActivities();
        // dd($this->total_amount);
        return $this->total_amount;
    }

    // ------------------------- ACCESSOR PROPERTIES ---------------------------- //

    public function getStayDurationProperty()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 'Dates not set';
        }
        $in = \Carbon\Carbon::parse($this->check_in_date);
        $out = \Carbon\Carbon::parse($this->check_out_date);
        return $in->diffInDays($out);
    }

    public function getAvailableRooms()
    {

        Log::info('Available rooms fetched');

        if (!$this->check_in_date || !$this->check_out_date) {
            return;
        }

        // dd($this->check_in_date, $this->check_out_date);

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);
        $checkOut = \Carbon\Carbon::parse($this->check_out_date);

        $this->rooms = Property::ofType('Room')
            ->where('property_status', 'available') // only explicitly include available
            ->where('property_status', '!=', ['out_of_service', 'held', 'booked']) // explicitly exclude out_of_service
            ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('transaction_status', ['pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->where('start_datetime', '<', $checkOut) // Any booking that starts before the user checks out
                            ->where('end_datetime', '>', $checkIn); // Ends after the user checks in
                    });
            })
            ->get();
    }

    public function getDepositProperty()
    {
        // Retrieve deposit percentage from database
        $depositPercentage = DB::table('st_settings')->value('deposit_percentage');

        // Ensure computeTotalAmount() returns a valid amount
        return $this->computeTotalAmount() * ($depositPercentage / 100);
    }



    // ----------------------- ROOMS ------------------------------ //

    public function SelectedRooms($roomId)
    {

        // Resets any previous error messages
        $this->resetErrorBag();

        Log::Info('Selected Rooms from Modal Method Called');
        Log::Info('Room is added to the cart.');

        // Check if check-in and check-out dates are provided
        if (!$this->check_in_date || !$this->check_out_date) {
            $this->addError('selectedRooms', 'Please select check-in and check-out dates before adding a room.');
            return; // Exit the function if dates are not set
        }

        // Find the room using the provided roomId, or fail if it doesn't exist
        $room = Property::findOrFail($roomId);

        // Check if the room is already in the cart
        foreach ($this->selectedRooms as $item) {
            if ($item['room_id'] == $roomId) {
                $this->addError('selectedRooms', 'This room is already in the cart.');
                return;
            }
        }

        $adults = (int) ($this->adults[$roomId] ?? 1);
        $kids = (int) ($this->kids[$roomId] ?? 0);
        $stayDuration = $this->getStayDurationProperty();
        $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
        $roomAmount = $room->amount * $stayDuration;
        $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;


        $this->selectedRooms[] = [
            'room_id' => $room->id,
            'room_name' => $room->name_number,
            'roomRate' => $room->amount,
            'roomMaxAdults' => $room->roomMaxAdults,
            'roomMaxKids' => $room->roomMaxKids,
            'roomIdealGuest' => $room->roomIdealGuest,
            'extra_guest' => $extraGuests,
            'days' => $stayDuration,
            'adults' => $adults,
            'kids' => $kids,
            'roomAmount' => $roomAmount, // base rate * days
            'extra_charge' => $extraCharge, // extra_guest * extra_person_charge * days
            'total_amount' => $roomAmount + $extraCharge,
        ];

        $this->computeTotalPax();
    }

    public function RemoveRoom($roomId)
    {
        $this->selectedRooms = collect($this->selectedRooms)->reject(function ($room) use ($roomId) {
            return $room['room_id'] == $roomId;
        })->values()->toArray();
    }




    // ----------------------- ACTIVITIES ------------------------- //

    public function SelectedActivities($activityId)
    {

        Log::Info('Selected Activites from Modal Method Called');

        // Resets any previous error messages
        $this->resetErrorBag();

        // Find the activity using the provided activityId, or fail if it doesn't exist
        $activity = Activity::findOrFail($activityId);

        // If the activity is already in the cart, show an error and return
        foreach ($this->selectedActivities as $item) {
            if ($item['activity_id'] == $activityId) {
                $this->addError('selectedActivities', 'This activity is already in the cart.');
                return; // Exit the function to avoid adding the same activity again
            }
        }

        // Calculate the total amount for the activity based on the quantity
        $quantity = (int) ($this->quantity[$activityId] ?? 1); // Default to 1 if not set
        $activityAmount = $activity->amount * $quantity; // Calculate the total amount for the activity
        $activitystatus = $this->status[$activityId] = 'pending'; // Set the status of the activity to 'pending'

        // Add the activity to the cart if it isn't already present
        $this->selectedActivities[] = [
            'activity_id' => $activity->id, // Set the activity ID from the activity object
            'activity_name' => $activity->name, // Set the activity name
            'activity_rate' => $activity->amount,
            'quantity' => $quantity, // Set the quantity from the input or default to 1
            'amount' => $activityAmount, // Set the calculated amount for the activity
            'status' => $activitystatus, // Set the status of the activity
            'image' => $activity->image, // Set the activity image
            'description' => $activity->description, // Set the activity description
        ];
    }

    public function RemoveActivity($activityId)
    {
        $this->selectedActivities = collect($this->selectedActivities)->reject(function ($activity) use ($activityId) {
            return $activity['activity_id'] == $activityId;
        })->values()->toArray();
    }

    public function incrementActivity($activityId)
    {
        $activity = Activity::find($activityId);
        if (!$activity) return;

        // Get the current quantity or default to 1
        $currentQuantity = $this->quantity[$activityId] ?? 1;

        // Check if the current quantity is less than total_pax before incrementing
        if ($currentQuantity < $this->total_pax) {
            $this->quantity[$activityId] = $currentQuantity + 1;

            foreach ($this->selectedActivities as $index => $item) {
                if ($item['activity_id'] == $activityId) {
                    $quantity = $this->quantity[$activityId];
                    $this->selectedActivities[$index]['quantity'] = $quantity;
                    $this->selectedActivities[$index]['amount'] = $activity->amount * $quantity;
                }
            }
        }
    }

    public function decrementActivity($activityId)
    {
        $activity = Activity::find($activityId);
        if (!$activity) return;

        // Decrease the quantity, but prevent going below 1
        $this->quantity[$activityId] = max(1, ($this->quantity[$activityId] ?? 1) - 1);

        // Update the cart with the new quantity and amount
        foreach ($this->selectedActivities as $index => $item) {
            if ($item['activity_id'] == $activityId) {
                $quantity = $this->quantity[$activityId];
                $this->selectedActivities[$index]['quantity'] = $quantity;
                $this->selectedActivities[$index]['amount'] = $activity->amount * $quantity;
            }
        }
    }



    public function addMultipleGuests()
    {

        // Validate the guest details
        $this->validate([
            'guest_first_name' => 'required|string',
            'guest_middle_name' => 'nullable|string',
            'guest_last_name' => 'required|string',
            'guest_suffix' => 'nullable|string|max:10',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'nullable|in:local,foreigner',
            'guest_country_of_origin' => 'nullable|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);

        // Add the guest details to the guests array
        $this->guests[] = [
            'guest_first_name' => $this->guest_first_name,
            'guest_middle_name' => $this->guest_middle_name,
            'guest_last_name' => $this->guest_last_name,
            'guest_suffix' => $this->guest_suffix,
            'guest_type_id' => $this->guest_type_id,
            'guest_gender' => $this->guest_gender,
            'guest_residency' => $this->guest_residency,
            'guest_country_of_origin' => $this->guest_country_of_origin,
        ];

        $this->showGuestModal = false;

        // Optionally clear the form inputs after adding a guest
        $this->reset(['guest_first_name', 'guest_middle_name', 'guest_last_name', 'guest_suffix', 'guest_type_id', 'guest_gender', 'guest_residency', 'guest_country_of_origin']);
    }


    public function editGuest($index)
    {
        $this->editingGuestIndex = $index;
        $this->editingGuest = $this->guests[$index];
        $this->showEditModal = true;
    }

    public function updateGuest()
    {
        if (!is_null($this->editingGuestIndex)) {
            $this->guests[$this->editingGuestIndex] = $this->editingGuest;
        }

        $this->showEditModal = false;
        $this->reset('editingGuestIndex', 'editingGuest');
    }

    public function deleteGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
    }


    public function debug()
    {
        dd($this->selectedActivities);
    }

    public function validateData()
    {
        $this->validate([
            'selectedRooms' => 'required|array|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|string',
            'country' => 'required|string',
            'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
        ]);
    }


    public function CreateReservation()
    {

        $this->resetErrorBag(); // Reset any previous error messages

        $reservationData = []; // Initialize an empty array to store reservation data for email

        DB::transaction(function () use (&$reservationData) {
            // Step 1: Create transaction user
            $transactionUser = TransactionUser::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'company_name' => $this->company_name,
                'country' => $this->country,
                'trn_user_type' => $this->trn_user_type,
            ]);

            $depositPercentage = DB::table('st_settings')->value('deposit_percentage');

            // Step 2: Create transaction
            $transaction = Transaction::create([
                'reservation_type_id' => $this->reservation_type_id,
                'created_by' => $transactionUser->id,
                'start_datetime' => $this->check_in_date,
                'end_datetime' => $this->check_out_date,
                'total_adults' => collect($this->selectedRooms)->sum('adults'),
                'total_kids' => collect($this->selectedRooms)->sum('kids'),
                'pax' => $this->total_pax,
                'total_amount' => $this->computeTotalAmount(),
                'deposit_amount' => $this->computeTotalAmount() * ($depositPercentage / 100),
                'heard_from' => $this->heard_from,
                'reservation_source' => $this->reservation_source,
                'transaction_status' => $this->transaction_status,
                'terms' => $this->terms,
            ]);

            // Step 3 & 4: Generate invoice number
            $latestInvoice = Invoice::whereYear('created_at', now()->year)->orderBy('created_at', 'desc')->first();
            $invoiceNumber = 'INV-' . now()->year . '-' . str_pad($latestInvoice ? (int) substr($latestInvoice->invoice_number, -3) + 1 : 1, 3, '0', STR_PAD_LEFT);

            // Step 5: Create invoice
            $invoice = Invoice::create([
                'transaction_id' => $transaction->id,
                'invoice_number' => $invoiceNumber,
                'invoice_type' => 'Room',
                'sub_total' => $this->computeTotalAmount(),
                'deposit_paid' => 0,
                'amount_paid' => 0,
                'balance_due' => $this->computeTotalAmount(),
                'due_date' => $this->check_out_date,
                'invoice_status' => 'pending',
            ]);

            // Step 6: Attach rooms and activities
            foreach ($this->selectedRooms as $item) {
                $transaction->properties()->attach($item['room_id'], [
                    'adults' => $item['adults'],
                    'kids' => $item['kids'],
                    'days' => $item['days'],
                    'extra_charge' => $item['extra_charge'],
                    'amount' => $item['roomAmount'],
                    'total_amount' => $item['total_amount'],
                    'extra_guest' => $item['extra_guest'],
                ]);
            }

            foreach ($this->selectedActivities as $item) {
                $transaction->activities()->attach($item['activity_id'], [
                    'quantity' => $item['quantity'],
                    'amount' => $item['amount'],
                ]);
            }

            // Step 7: Insert GuestDetails
            foreach ($this->guests as $guest) {
                GuestDetail::create([
                    'transaction_id' => $transaction->id,
                    'first_name' => $guest['guest_first_name'],
                    'middle_name' => $guest['guest_middle_name'],
                    'last_name' => $guest['guest_last_name'],
                    'suffix' => $guest['guest_suffix'],
                    'gender' => $guest['guest_gender'],
                    'residency' => $guest['guest_residency'],
                    'country_of_origin' => $guest['guest_country_of_origin'],
                    'guest_type_id' => $guest['guest_type_id'],
                ]);
            }

            // Get the payment_proof_expiration_hours from database
            $setting = Setting::first();
            $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24; // default value

            // Prepare data for the email (accessible outside transaction)
            $reservationData = [
                'name' => $this->first_name . ' ' . $this->last_name,
                'transaction_number' => $transaction->id,
                'email' => $this->email,
                'invoice_number' => $invoiceNumber,
                'check_in' => $this->check_in_date,
                'check_out' => $this->check_out_date,
                'total_amount' => $this->computeTotalAmount(),
                'deposit' => $this->computeTotalAmount() * ($depositPercentage / 100),
                'expirationHours' => $this->expirationHours,
            ];
        });

        // Step 7: Send confirmation email
        try {
            Mail::to($reservationData['email'])->send(new ReservationSubmittedMail($reservationData));
        } catch (\Exception $e) {
            logger()->error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        // Step 8: Flash success message and redirect
        session()->flash('success', 'Reservation successfully created!');
        return redirect()->route('admin.reservations-list');
    }
}
