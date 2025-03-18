<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use App\Models\Room;
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

    public function mount($roomId)
    {
        $this->roomId = (int) $roomId;
        $this->room = Room::findOrFail($this->roomId);
    }

    public function saveIndividualRoomRate()
    {
        // Validate the form input
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'extra_person_charge' => 'required|numeric|min:100|max:50000.00',
            'extended_stay_charge_per_hr' => 'required|numeric|min:100|max:50000.00',
            'description' => 'nullable|string',
            'rate_type' => 'required|in:Weekdays,Weekend,Holiday,Peak',
        ]);

        // Create new room rate
        RoomRate::create([
            'name' => $this->name,
            'room_id' => $this->roomId,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'extra_person_charge' => $this->extra_person_charge,
            'extended_stay_charge_per_hr' => $this->extended_stay_charge_per_hr,
            'rate_type' => $this->rate_type,
            'description' => $this->description,
        ]);

        // Reset form fields (excluding `roomId`)
        $this->reset(['name', 'start_date', 'end_date', 'amount', 'extra_person_charge', 'extended_stay_charge_per_hr', 'description', 'rate_type']);

        // Flash success message
        session()->flash('message', 'Room Rate successfully created!');

        // Redirect to the room view page
        return redirect()->route('admin.view-room', ['room' => $this->roomId]);
    }

    public function render()
    {
        return view('livewire.admin.room-rates.create-individual-rate', [
            'room' => $this->room,
        ]);
    }
}
