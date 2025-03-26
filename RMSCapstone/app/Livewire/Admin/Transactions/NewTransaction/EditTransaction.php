<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Room;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\PaymentMethod;

#[Layout('layouts.app')]
class EditTransaction extends Component
{

    use WithFileUploads;
    public Transaction $transaction;

    // Reservation Holder Details
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $contact_number;
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $province;
    public $region;
    public $postal_code;
    public $country;

    // Transaction Details
    public $room_id;
    public $check_in_time;
    public $check_out_time;
    public $check_in_date;
    public $check_out_date;
    public $total_adults;
    public $total_kids;
    public $pax; // Default number of residents
    public $activity_id;
    public $total_amount;
    public $pets;


    // Payment Details
    public $payment_method_id;
    public $payment_screenshot; // Stores uploaded proof of payment
    public $newImage;
    public $payment_reference_number;
    public $isPaid = false;
    public $isReserved = false;
    public $isConfirmed = false;

    public $terms;
    public $confirmCreateItem = false; // Flag for confirmation before creating a transaction

    // Dropdown data
    public $paymentMethods;
    public $rooms;
    public $activities;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(Transaction $transaction)
    {
        // Reservation Holder Details
        $this->first_name = $transaction->first_name;
        $this->middle_name = $transaction->middle_name;
        $this->last_name = $transaction->last_name;
        $this->suffix = $transaction->suffix;
        $this->email = $transaction->email;
        $this->contact_number = $transaction->contact_number;
        $this->house_number = $transaction->house_number;
        $this->street = $transaction->street;
        $this->barangay = $transaction->barangay;
        $this->city_municipality = $transaction->city_municipality;
        $this->province = $transaction->province;
        $this->region = $transaction->region;
        $this->postal_code = $transaction->postal_code;
        $this->country = $transaction->country;

        // Transaction Details
        $this->room_id = $transaction->room_id;
        $this->activity_id = $transaction->activity_id;
        $this->check_in_date = optional($transaction->check_in_date)->format('Y-m-d');
        $this->check_out_date = optional($transaction->check_out_date)->format('Y-m-d');
        $this->check_in_time = optional($transaction->check_in_time)->format('H:i');
        $this->check_out_time = optional($transaction->check_out_time)->format('H:i');
        $this->total_adults = $transaction->total_adults;
        $this->total_kids = $transaction->total_kids;
        $this->pax = $transaction->pax ?? 1; // Default to 1 if null
        $this->total_amount = $transaction->total_amount;
        $this->pets = $transaction->pets;
        $this->terms = $transaction->terms;

        // Payment Details
        $this->payment_method_id = $transaction->payment_method_id;
        $this->payment_screenshot = $transaction->payment_screenshot;
        $this->payment_reference_number = $transaction->payment_reference_number;
        $this->isPaid = $transaction->isPaid ?? false;
        $this->isReserved = $transaction->isReserved ?? false;
        $this->isConfirmed = $transaction->isConfirmed ?? false;

        // Dropdown Data (Ensure these are populated in the component)
        $this->paymentMethods = PaymentMethod::all(); // Assuming PaymentMethod model
        $this->rooms = Room::all(); // Assuming Room model
        $this->activities = Activity::all(); // Assuming Activity model
    }

    public function updateTransaction()
    {
        try {
            // Validate form input
            $this->validate([
                'room_id' => 'required|exists:prd_rooms,id',
                'check_in_time' => 'required|date_format:H:i',
                'check_out_time' => 'required|date_format:H:i',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after_or_equal:check_in_date',
                'total_adults' => 'required|integer|min:1',
                'total_kids' => 'nullable|integer|min:0',
                'pax' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'first_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'last_name' => 'required|string',
                'suffix' => 'nullable|string',
                'email' => 'required|email',
                'contact_number' => 'required|string',
                'house_number' => 'nullable|string',
                'street' => 'nullable|string',
                'barangay' => 'nullable|string',
                'city_municipality' => 'required|string',
                'province' => 'required|string',
                'region' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
                'pets' => 'integer|min:1',
                'terms' => 'boolean',
                'payment_method_id' => 'required|integer|exists:pm_payment_methods,id',
                'payment_screenshot' => 'nullable|image|max:1024',
                'payment_reference_number' => 'nullable|string',
                'isPaid' => 'boolean',
                'isReserved' => 'boolean',
                'isConfirmed' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmEditItem = false;
            session()->flash('error', 'Validation failed. Please check your input.');
            return;
        }
        // Ensure image upload is complete before storing
        $imagePath = $this->payment_screenshot ? $this->payment_screenshot->store('transactions', 'public') : null;

        // Find transaction
        $transaction = Transaction::find($this->transaction_id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        // Update transaction
        $transaction->update([
            'room_id' => $this->room_id,
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
            'house_number' => $this->house_number,
            'street' => $this->street,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'province' => $this->province,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'pets' => $this->pets,
            'terms' => true, // Ensure it's stored as true'
            'payment_method_id' => $this->payment_method_id,
            'payment_screenshot' => $imagePath ?? $transaction->payment_screenshot,
            'payment_reference_number' => $this->payment_reference_number,
            'isPaid' => $this->isPaid ?? false,
            'isReserved' => $this->isReserved ?? false,
            'isConfirmed' => $this->isConfirmed ?? false,
        ]);

        // Reset form fields
        $this->reset();

        // Flash message for success
        session()->flash('message', 'Transaction successfully updated!');

        // Redirect back to transactions list
        return redirect()->route('admin.view-new-transactions');
    }



    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.edit-transaction');
    }
}
