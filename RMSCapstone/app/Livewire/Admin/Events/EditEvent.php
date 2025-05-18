<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
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
        $this->country = $event->transactionUser->country ?? '';
    }

    public function updateEvent()
    {
        $this->validate([
            'transaction_status' => 'required|in:pending,confirmed,ongoing,done,terminated',
        ]);

        // Directly use the $transaction model
        $this->event->transaction_status = $this->transaction_status;
        $this->event->save();
        
        $this->confirmEditItem = true;
        session()->flash('success', 'Event status updated successfully!');
        return redirect()->route('admin.events');
    }


    public function render()
    {
        return view('livewire.admin.events.edit-event');
    }
}
