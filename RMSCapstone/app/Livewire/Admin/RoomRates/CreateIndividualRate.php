<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use App\Models\Property;
use App\Models\RoomRate;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CreateIndividualRate extends Component
{
    public int $roomId;
    public $room;

    public $name;
    public $start_date;
    public $end_date;
    public $amount;
    public $extra_person_charge;
    public $extended_stay_charge_per_hr;
    public $description;
    public $rate_type = 'Weekdays';
    public $freebies;
    public $priority;
    public $is_active = true;
    public $min_stay_nights;
    public $max_stay_nights;


    public function render()
    {
        return view('livewire.admin.room-rates.create-individual-rate', [
            'room' => $this->room,
        ]);
    }

    public function mount($roomId)
    {
        $this->roomId = (int) $roomId;
        $this->room = Property::findOrFail($this->roomId);
    }

    public function saveIndividualRoomRate()
    {
        // Validate the form input
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'rate_type' => 'required|in:Weekdays,Weekend,Holiday,Peak',
            'freebies' => 'nullable|string|max:1000',
            'priority' => 'nullable|integer|min:1|max:10',
            'is_active' => 'required|boolean',
            'min_stay_nights' => 'nullable|integer|min:1|max:30',
            'max_stay_nights' => 'nullable|integer|min:1|max:90',
        ]);

        // Create new room rate
        RoomRate::create([
            'name' => $this->name,
            'property_id' => $this->roomId,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'rate_type' => $this->rate_type,
            'description' => $this->description,
            'freebies' => $this->freebies,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'min_stay_nights' => $this->min_stay_nights,
            'max_stay_nights' => $this->max_stay_nights,
        ]);

        // Reset form fields (excluding `roomId`)
        $this->reset(['name', 'start_date', 'end_date', 'amount', 'description', 'rate_type', 'freebies', 'priority', 'is_active', 'min_stay_nights', 'max_stay_nights']);

        // Flash success message
        session()->flash('message', 'Room Rate successfully created!');

        // Redirect to the room view page
        return redirect()->route('admin.view-room', ['room' => $this->roomId]);
    }
}
