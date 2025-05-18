<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEvent extends Component
{
     // Create a public property 
    public Transaction $event;
    public $halls; 
    public $guests; 
    public $event_invoice; 
    public $eventTypes; 

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //To display foreign keys
    public function mount()
    {
        $this->eventTypes = EventType::all();
        $this->event_invoice = Invoice::where('invoice_type', 'Event_Hall')->get();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();
    }

    public function exportEventDetails()
    {
        $pdf = Pdf::loadView('livewire.admin.events.event-details', [
            'event' => $this->event,  // Pass the actual event
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'event-details-' . $this->event->start_datetime . '.pdf');
    }

    public function deleteEventItem(Transaction $event)
    {
         if (!$event) {
        session()->flash('error', 'Event not found!');
        return;
        }

        if ($this->confirmItemDelete) {
            if (in_array($event->transaction_status, ['done', 'terminated'])) {
                $event->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Event successfully deleted!');
                return redirect()->route('admin.events');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.events.view-event');
    }
}
