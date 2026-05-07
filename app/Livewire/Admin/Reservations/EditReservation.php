<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\GuestType;
use App\Models\GuestDetail;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class EditReservation extends Component
{
    // Relationship: Transaction->Invoice->Payments
    public $transaction; // Holds the current transaction
    public $invoice; // Holds the invoice associated with the transaction
    public $activities; // Holds all activities associated with the transaction
    public $guestDetails;
    public $total_pax;
    public $payments; // Holds all payments associated with the invoice
    public $totalAddons; // Holds the total amount of addons
    public $totalRooms; // Holds the total amount of 


    // ---------------------- Room Details ---------------------- //
    public $properties;
    public $maxAdults;
    public $maxKids;
    public $selectedPropertyId;
    public $existingAdults;
    public $existingKids;
    public $existingDays;
    public $existingExtraGuests;
    public $existingRoomAmount;
    public $existingExtraCharge;
    public $editedRoomPivot = [];

    public $editingRooms = [];
    public $maxAdultsPerRoom = []; // e.g., ['property_id' => 4]
    public $maxKidsPerRoom = [];
    public $showRoomModal = false;


    // ---------------------- Guest Details ---------------------- //

    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin;
    public $guests = [];
    public $guest_types = [];

    public $showGuestModal = false;
    public $editingGuestIndex = null;
    public $showEditModal = false;

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


    public function render()
    {
        // Load the activities with the pivot data
        $this->activities = $this->transaction->activities()->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();

        return view('livewire.admin.reservations.edit-reservation', [
            'activities' => $this->activities,  // Pass activities to the view properly
            'properties' => $this->properties,  // Pass activities to the view properly
        ]);
    }

    public function mount(Transaction $transaction)
    {
        // Eager-load related models to avoid N+1 query problem.
        // This loads relationships only if they haven't already been loaded.
        $transaction->load([
            'invoice.payments',     // Load the invoice and its related payments
            'transactionUser',      // Load the user related to the transaction
            'guestDetails',         // Load additional guest details associated with the transaction
            'properties',
            'activities'
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;                 // Store the full transaction
        $this->invoice = $transaction->invoice;            // Store the invoice details
        $this->guestDetails = $transaction->guestDetails;  // Store guest details for display
        $this->activities = $transaction->activities;
        $this->properties = $transaction->properties;      // Store properties (rooms)

        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();

        // Get computed totals from model accessors (defined in the Transaction model)
        $this->totalRooms = $transaction->total_rooms;     // Total cost from rooms (via accessor)
        $this->totalAddons = $transaction->total_addons;   // Total cost from addons (via accessor)

        $this->total_pax = $transaction->pax;
        $this->guest_types = GuestType::all();
    }

    public function updateRoomPivotData()
    {
        if ($this->selectedPropertyId) {
            $this->editedPivots[$this->selectedPropertyId] = [
                'adults' => $this->existingAdults,
                'kids' => $this->existingKids,
                'days' => $this->existingDays,
                'extra_guest' => $this->existingExtraGuests,
                'amount' => $this->existingRoomAmount,
                'extra_charge' => $this->existingExtraCharge,
            ];
        }

        dd($this->editedPivots);

        $this->showRoomModal = false;
    }

    public function loadActivityData($activityId) {}

    public function openGuestModal()
    {
        $this->showGuestModal = true;
    }

    public function closeGuestModal()
    {
        $this->showGuestModal = false;
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

        // If the transaction->guestDetail (one to many relationship) count is ==  to the transaction->pax 
        // GuestDetail count = 2 == transaction->pax = 2
        // Adding another guest will add charge to the invoice->subtotal
        // if ($this->transaction->guestDetails->count() >= $this->transaction->pax) {
        //     // Add charge to the invoice subtotal based
        //     $this->invoice->increment('sub_total', 100); // Assuming a fixed charge of 100 for each additional guest
        // }

        $this->showGuestModal = false;

        // Optionally clear the form inputs after adding a guest
        $this->reset(['guest_first_name', 'guest_middle_name', 'guest_last_name', 'guest_suffix', 'guest_type_id', 'guest_gender', 'guest_residency', 'guest_country_of_origin']);
    }


    public function saveChanges($transactionId)
    {

        foreach ($this->guests as $guest) {
            GuestDetail::create([
                'transaction_id' => $transactionId,
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

        return redirect()->route('admin.reservations-list');
    }

    public function cancelEdit()
    {

        return redirect()->route('admin.reservations-list');
    }
}




   //   public function editRoom($propertyId)
    // {
    //     $this->selectedPropertyId = $propertyId;

    //     $property = $this->transaction->properties()->where('property_id', $propertyId)->first();

    //     if ($property) {
    //         $pivot = $property->pivot;

    //         // Store the edited room data in the class property array (not session)
    //         $this->editingRooms[$propertyId] = [
    //             'adults' => $pivot->adults,
    //             'kids' => $pivot->kids,
    //             'extra_guest' => $pivot->extra_guest ?? 'N/A',
    //             'amount' => $pivot->amount ?? 0,
    //             'extra_charge' => $pivot->extra_charge ?? 0,
    //             'total_amount' => $pivot->total_amount ?? 0
    //         ];

    //         // Store max adults and kids per room
    //         $this->maxAdultsPerRoom[$propertyId] = $property->max_adults ?? 0;
    //         $this->maxKidsPerRoom[$propertyId] = $property->max_kids ?? 0;

    //         // Open the modal to edit
    //         $this->showRoomModal = true;
    //     }
    // }


    // public function updateRoom()
    // {

    //     dd($this->editingRooms);
    //     $this->showRoomModal = false;

    //     // Optional: emit event or show flash message
    //     session()->flash('message', 'Room changes stored temporarily.');
    // }
