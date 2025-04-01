<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use Livewire\Component;

class CreateEvent extends Component
{
    public $name;
    public $event_category_id;
    public $event_hall_id;
    public $company_name;
    public $contact_person;
    public $email;
    public $event_date_start;
    public $event_date_end;
    public $event_time;
    public $capacity;
    public $total_amount;
    public $status;
    public $requests;

    public $eventCategories = [];
    public $eventHalls = []; // To store fetched event categories

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    public function mount()
    {
        // Fetch event categories and halls when the component mounts
        $this->eventCategories = EventCategory::all();
        $this->eventHalls = EventHall::all();
    }

    public function saveEvent()
    {
        try {
            // Validate form input 
            $this->validate([
                'name' => 'required|string|max:255',
                'event_category_id' => 'required|exists:prd_event_categories,id',
                'event_hall_id' => 'required|exists:prd_event_halls,id',
                'company_name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'event_date_start' => 'required|date|after_or_equal:today',
                'event_date_end' => 'nullable|date|after_or_equal:event_date_start',
                'event_time' => 'required|date_format:H:i',
                'capacity' => 'required|numeric|min:10|max:200',
                'total_amount' => 'required|numeric|min:100|max:1000000.00',
                'status' => 'required|in:confirmed,on-going,completed,cancelled',
                'requests' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }


        // Create Event
        $event = Event::create([
            'name' => $this->name,
            'event_category_id' => $this->event_category_id,
            'event_hall_id' => $this->event_hall_id,
            'company_name' => $this->company_name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'event_date_start' => $this->event_date_start,
            'event_date_end' => $this->event_date_end,
            'event_time' => $this->event_time,
            'capacity' => $this->capacity,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'requests' => $this->requests,
        ]);

        // Reset form fields
        $this->reset([
            'name',
            'event_category_id',
            'event_hall_id',
            'company_name',
            'contact_person',
            'email',
            'event_date_start',
            'event_date_end',
            'event_time',
            'capacity',
            'total_amount',
            'status',
            'requests',
        ]);

        // Flash message for success
        session()->flash('message', 'Event successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.events');
    }


    public function render()
    {
        return view('livewire.admin.events.create-event', [
            'eventCategories' => $this->eventCategories,
            'eventHalls' => $this->eventHalls, // Pass categories to view
        ]);
    }
}
