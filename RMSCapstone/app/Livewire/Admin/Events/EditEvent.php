<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Municipality;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditEvent extends Component
{
    public Transaction $event;
    // ----------------------- Types ---------------------------- // 
    public $reservation_type_id = 3; // This reservation is for Events
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    
    // ----------------------- Heard From, Status Defaults ---------------------------- // 
    public $reservation_source = 'WebApp';
    public $transaction_status = 'confirmed';

    // ------------------- Address -------------------- //
    public $municipalities = [];
    public $otherCountry;
    
    // ----------------------- EVENT DETAILS ---------------------------- // 
    
    // ----------------------- Guest ---------------------------- // 
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $city_municipality;
    public $country; 

    // ----------------------- Halls (transaction_properties)---------------------------- // 
    public $halls;
    public $selected_hall;
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = [];
    public $hall_amount; 
    // public $hall_total_amount; 

    // ----------------------- Halls (trn_transactions)---------------------------- // 
    public $total_amount; // Total amount for the reservation
    public $pax = 0; // Total number of guests (adults + kids)
    
    public $start_datetime; //Start Date Time of Event
    public $end_datetime; //End Date Time of Event
    public $total_adults;
    public $total_kids; 
    public $actual_start_datetime; // Actual start datetime when the event is ongoing
    //public $heard_from; 

    // ------------------- INVOICE AND EVENT TYPE -------------------- // 
    public $invoice_number;
    public $eventTypes;
    public $event_type_id;
    public $guests; 
    public $hall_id; 

    public $confirmEditItem = false;

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }


    //To fetch data for display
    public function mount(Transaction $event)
    {
        $this->eventTypes = EventType::all();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();

        //Mount all necessary fields
        $this->selected_hall = $event->properties()->first()->id ?? null;
        $this->event_type_id = $event->event_type_id;
        $this->invoice_number = $event->invoice_number;
        $this->total_adults = $event->total_adults;
        $this->total_kids = $event->total_kids;
        $this->pax = $event->pax;

        $this->start_datetime = $event->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $event->end_datetime->format('Y-m-d\TH:i');

        $this->total_amount = $event->total_amount;

        $this->transaction_status = $event->transaction_status;

        $this->first_name = $event->transactionUser->first_name ?? '';
        $this->middle_name = $event->transactionUser->middle_name ?? '';
        $this->last_name = $event->transactionUser->last_name ?? '';
        $this->email = $event->transactionUser->email ?? '';
        $this->contact_number = $event->transactionUser->contact_number ?? '';
        $this->company_name = $event->transactionUser->company_name ?? '';
        $this->city_municipality = $event->transactionUser->city_municipality ?? '';
        
        // ------------------ Country Handling ------------------ //
        $presetCountries = ['Philippines', 'Other'];
        if (!in_array($event->transactionUser->country, $presetCountries)) {
            // Custom country value
            $this->country = 'Other';
            $this->otherCountry = $event->transactionUser->country;
        } else {
            $this->country = $event->transactionUser->country;
            $this->otherCountry = '';
        }

        // ----------------------- Address -------------------- //
        $this->municipalities = Municipality::orderBy('PSGC_MUNC_DESC')->get();
    }

    // --------------------- UPDATED FIELDS METHODS --------------------- //
    public function computeTotalPax()
    {
        $this->pax = (int) $this->total_adults + (int) $this->total_kids;
    }

    public function updatedTotalAdults()
    {
        $this->computeTotalPax();
    }

    public function updatedTotalKids()
    {
        $this->computeTotalPax();
    }

    public function updateEvent()
    {
        //Cannot change status to receipt_verified, reserved, or confirmed if amount paid is zero
        if(
            in_array($this->transaction_status, ['receipt_verified', 'reserved', 'confirmed', 'ongoing', 'done']) &&
            $this->event->invoice && 
            $this->event->invoice->amount_paid == 0
        ){
        $this->addError('transaction_status', 'Cannot change status to "' . str_replace('_', ' ', $this->transaction_status) . '". No payments found.');            
        $this->confirmEditItem = false;
           // $this->cannotEditItem = true;
            return;
        }

    try{
         $this->validate([
                // Transaction User Fields
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'contact_number' => 'required|string|max:20',
                'city_municipality' => 'nullable|string|max:100',
                'company_name' => 'required|string|max:100',
                'country' => 'required|string|max:100',

                // Transaction Fields
                'event_type_id' => 'required|integer|exists:event_types,id',
                'start_datetime' => 'required|date|before_or_equal:end_datetime',
                'end_datetime' => 'required|date|after_or_equal:start_datetime',
                'total_adults' => 'required|integer|min:5|max:200',
                'total_kids' => 'nullable|integer|min:0|max:50',
                'pax' => 'required|integer|min:5|max:200',
                'total_amount' => 'required|numeric|min:10000|max:5000000.00',
                'reservation_source' => 'required|string|max:100',


                // Dynamic guests per hall (optional validation)
                'selected_hall' => 'required|exists:properties,id|not_in:' . implode(',', $this->halls->where('is_booked', true)->pluck('id')->toArray()),
                'adults.*' => 'nullable|integer|min:0|max:200',
                'kids.*' => 'nullable|integer|min:0|max:50',
                'extra_guest.*' => 'nullable|integer|min:0',
                'extra_charge.*' => 'nullable|numeric|min:0',

                //Status
                'transaction_status' => 'required|in:pending,confirmed,reserved,receipt_verified,ongoing,done,terminated',
    ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        $this->confirmEditItem = false;
        throw $e;
    }

    $finalCountry = $this->country === 'Other' ? $this->otherCountry : $this->country;
    
    DB::transaction(function() use ($finalCountry){
        //Only update transacation details
        $this->event->update([
            'event_type_id' => $this->event_type_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'total_adults' => $this->total_adults,
            'total_kids' => $this->total_kids ?? 0,
            'pax' => $this->pax,
            'total_amount' => $this->total_amount,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
        ]);

        //If status is changed to ongoing
        if (
            $this->transaction_status === 'ongoing' &&
            $this->event->transaction_status !== 'ongoing' &&
            $this->event->actual_start_datetime === null
        ) {
            $this->event->actual_start_datetime = now();
            $this->event->save();
        }

        $this->event->properties()->sync([
            $this->selected_hall => [
                'adults' => $this->total_adults,
                'kids' => $this->total_kids ?? 0,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $this->total_amount,
                'total_amount' => $this->total_amount,
                'days' => $this->stayDuration ?? 1,
            ]
            ]); 

        if($this->event->invoice){
            $this->event->invoice->update([
                'sub_total' => $this->total_amount,
                'balance_due' => $this->total_amount - $this->event->invoice->amount_paid,
                'due_date' => $this->end_datetime,
            ]); 
        }
    });
        $this->confirmEditItem = true;
        session()->flash('success', 'Event status updated successfully!');
        return redirect()->route('admin.events');
    }


    public function render()
    {
        return view('livewire.admin.events.edit-event');
    }
}
