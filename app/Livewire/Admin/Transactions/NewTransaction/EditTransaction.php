<?php


namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Property;
use App\Models\Activity;
use App\Models\PaymentMethod;


#[Layout('layouts.app')]
class EditTransaction extends Component
{
    use WithFileUploads;

    public Transaction $transaction; // Store the model received

    // Reservation Holder Details
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $contact_number;
    public $city_municipality;
    public $country;

    // Transaction Details
    public $check_in_time;
    public $check_out_time;
    public $check_in_date;
    public $check_out_date;
    public $total_adults;
    public $total_kids;
    public $pax;
    public $pets;
    public $total_amount;

    // Payment Details
    public $payment_screenshot; // Stores new proof of payment
    public $newImage;
    public $payment_reference_number;
    public $terms = true;
    public $isPaid;
    public $isReserved;
    public $isConfirmed;

    public $property_id;
    public $reservation_type_id = 2;
    public $created_by = 1;

    public $activity_id;
    public $payment_method_id;

    // Dropdown data
    public $rooms;
    public $activities;
    public $paymentMethods;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.edit-transaction');
    }

    // Mount the fields to pre-fill the edit form
    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->first_name = $transaction->transactionUser->first_name;
        $this->middle_name = $transaction->transactionUser->middle_name;
        $this->last_name = $transaction->transactionUser->last_name;
        $this->suffix = $transaction->transactionUser->suffix;
        $this->email = $transaction->transactionUser->email;
        $this->contact_number = $transaction->transactionUser->contact_number;
        $this->city_municipality = $transaction->transactionUser->city_municipality;
        $this->country = $transaction->transactionUser->country;

        // Reservation Details
        $this->check_in_date = optional($transaction->check_in_date)->format('Y-m-d');
        $this->check_out_date = optional($transaction->check_out_date)->format('Y-m-d');
        $this->check_in_time = optional($transaction->check_in_time)->format('H:i');
        $this->check_out_time = optional($transaction->check_out_time)->format('H:i');

        $this->total_kids = $transaction->total_kids;
        $this->total_adults = $transaction->total_adults;
        $this->pax = $transaction->pax;
        $this->pets = $transaction->pets;
        $this->total_amount = $transaction->total_amount;

        // Payment Details
        $this->payment_reference_number = $transaction->payment_reference_number;
        $this->payment_screenshot = $transaction->payment_screenshot;

        // Foreign Keys
        $this->property_id = $transaction->property_id;
        $this->activity_id = $transaction->activity_id;
        $this->payment_method_id = $transaction->payment_method_id;
        $this->rooms = Property::ofType('Room')->get();
        $this->activities = Activity::all();
        $this->paymentMethods = PaymentMethod::all();
    }

    public function updateTransaction()
    {
        try {
            $this->validate([
                'property_id' => 'required|exists:properties,id',
                'reservation_type_id' => 'required|exists:trn_reservation_type,id',
                'created_by' => 'required|exists:trn_users,id',
                'check_in_time' => 'required|date_format:H:i',
                'check_out_time' => 'required|date_format:H:i',
                'check_in_date' => 'required|date',
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
                'newImage' => 'nullable|image|max:2048',
                'payment_reference_number' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->transaction->payment_screenshot) {
                Storage::disk('public')->delete($this->transaction->payment_screenshot);
            }
            // Save the image in public folder
            $this->payment_screenshot = $this->newImage->store('transactions', 'public');
        }

        // Update Room Category
        $this->transaction->update([
            'property_id' => $this->property_id, // stores the room
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
            'payment_screenshot' => $this->payment_screenshot,
            'payment_reference_number' => $this->payment_reference_number,
        ]);

        session()->flash('message', 'Room Category successfully updated!');

        return redirect()->route('admin.view-new-transactions');
    }
}
