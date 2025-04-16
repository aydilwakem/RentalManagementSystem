<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Room;
use App\Models\Activity;
use App\Models\ReservationType;
use App\Models\TransactionUser;

class CreateTransaction extends Component
{
    use WithFileUploads;

    // Reservation Holder Details
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $contact_number;
    public $city_municipality;
    public $country;
    public $available;

    // Transaction Details
    public $room_id;
    public $reservation_type_id = 2;
    public $reservation_id;
    public $created_by = 1;

    public $activity_id;
    public $total_amount;
    public $check_in_time;
    public $check_out_time;
    public $check_in_date;
    public $check_out_date;
    public $total_adults;
    public $total_kids;
    public $pax;
    public $pets;

    // Payment Details
    public $payment_method_id;
    public $payment_screenshot; // Stores uploaded proof of payment
    public $payment_reference_number;
    public $terms = true;
    public $isPaid = false;
    public $isReserved = false;
    public $isConfirmed = false;

    public $confirmCreateItem = false; // Flag for confirmation before creating a transaction

    // Dropdown data
    public $paymentMethods;
    public $rooms;
    public $activities;

    /**
     * Sets the confirmation flag when a transaction creation is initiated.
     */
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    /**
     * Loads initial data when the component is mounted.
     * Retrieves available payment methods, rooms, and activities.
     */
    public function mount()
    {
        $this->paymentMethods = PaymentMethod::all(); // Fetch all payment methods
        $this->rooms = Room::availableRooms()->get(); // ✅ Fetch only available rooms
        $this->activities = Activity::all(); // Fetch all activities
    }


    public function updated($property)
    {
        if ($property === 'room_id' || $property === 'activity_id') {
            $this->calculateTotalAmount();
        }

        if ($property === 'total_adults' || $property === 'total_kids') {
            $this->calculateTotalPax();
        }
    }

    public function calculateTotalAmount()
    {
        $roomRate = $this->room_id ? Room::find($this->room_id)?->base_rate ?? 0 : 0;
        $activityRate = $this->activity_id ? Activity::find($this->activity_id)?->amount ?? 0 : 0;

        $this->total_amount = $roomRate + $activityRate;
    }

    public function calculateTotalPax()
    {
        $this->pax = ($this->total_adults ?? 0) + ($this->total_kids ?? 0);
    }

    /**
     * This method validates user input, handles image uploads, creates a new 
     * transaction, and associates residents with it. It also manages errors 
     * and redirects the user upon success or failure.
     */
    public function saveTransaction()
    {
        try {
            // Validate form input
            $this->validate([
                // 'room_id' => 'required|exists:prd_rooms,id',
                'reservation_id' => 'required|exists:prd_rooms,id',
                'reservation_type_id' => 'required|exists:trn_reservation_type,id',
                'created_by' => 'required|exists:trn_users,id',
                'check_in_time' => 'required|date_format:H:i',
                'check_out_time' => 'required|date_format:H:i',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_adults' => 'required|integer|min:1',
                'total_kids' => 'nullable|integer|min:0',
                'pax' => 'required|integer|min:1',
                'activity_id' => 'nullable|integer|exists:prd_activities,id',
                'total_amount' => 'required|numeric|min:0',
                'first_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'last_name' => 'required|string',
                'suffix' => 'nullable|string',
                'email' => 'required|email',
                'contact_number' => 'required|string',
                'city_municipality' => 'required|string',
                'country' => 'required|string',
                'pets' => 'nullable|integer|min:0',
                'payment_method_id' => 'required|integer|exists:pm_payment_methods,id',
                'payment_screenshot' => 'nullable|image|max:1024',
                'payment_reference_number' => 'nullable|string',
                'terms' => 'boolean',
                'isPaid' => 'boolean',
                'isReserved' => 'boolean',
                'isConfirmed' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Ensure image upload is complete before storing
        $imagePath = null;
        if ($this->payment_screenshot) {
            if (!$this->payment_screenshot->isValid()) {
                session()->flash('error', 'Image upload failed. Please try again.');
                return;
            }
            $imagePath = $this->payment_screenshot->store('transactions', 'public'); // Saves in storage/app/public/transactions
        }

        // Create Transaction and retrieve its ID
        $transaction = Transaction::create([
            'reservation_id' => $this->reservation_id, // stores the room
            'reservation_type_id' => $this->reservation_type_id,
            'created_by' => $this->created_by,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'total_adults' => $this->total_adults,
            'total_kids' => $this->total_kids,
            'pax' => $this->pax,
            'activity_id' => $this->activity_id,
            'total_amount' => $this->total_amount,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'city_municipality' => $this->city_municipality,
            'country' => $this->country,
            'pets' => $this->pets,
            'payment_method_id' => $this->payment_method_id,
            'payment_screenshot' => $imagePath,
            'payment_reference_number' => $this->payment_reference_number,
            'terms' => true, // Initially set to true
            'isPaid' => false, // Initially set to false
            'isReserved' => false, // Initially set to false
            'isConfirmed' => false, // Initially set to false
        ]);

        // Reset form fields
        $this->reset();

        // Flash message for success
        session()->flash('message', 'Reservation successfully created!');

        // Redirect back to reservations list
        return redirect()->route('admin.view-new-transactions');
    }

    /**
     * Renders the Create Transaction component.
     *
     * This method returns the Livewire view responsible for displaying
     * the transaction creation form, including available payment methods, 
     * rooms, and activities.
     */
    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.create-transaction', [
            'paymentMethods' => $this->paymentMethods,
            'rooms' =>  $this->rooms,
            'activities' => $this->activities,
        ]);
    }
}
