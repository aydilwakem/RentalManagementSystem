<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEvent extends Component
{
     // Create a public property 
     public Event $event;

     public $eventCategories = [];
     public $eventHalls = []; // To store fetched event categories

    //To display foreign keys
     public function mount(Event $event)
    {
         // Fetch event categories and halls when the component mounts
         $this->eventCategories = EventCategory::all();
         $this->eventHalls = EventHall::all();

        // // Load the event with its related category
        // $this->event = $event->load('category');
    }

    public function deleteEventItem(Event $event)
    {
        if (!$event) {
            session()->flash('error', 'Event not found!');
            return;
        }

        // Delete the event
        $event->delete();

        // Flash success message
        session()->flash('message', 'Event successfully deleted!');

        // Redirect to the admin rooms page
        return redirect()->route('admin.events');
    }

    public function render()
    {
        return view('livewire.admin.events.view-event');
    }
}
