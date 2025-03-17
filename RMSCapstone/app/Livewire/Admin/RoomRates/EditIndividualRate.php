<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use App\Models\Room;
use App\Models\RoomRate;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EditIndividualRate extends Component
{
    public RoomRate $roomRate;
    public $room_id;
    public $name;
    public $start_date;
    public $end_date;
    public $amount;
    public $extra_person_charge;
    public $extended_stay_charge_per_hr;
    public $description;
    public $rate_type;
    public $rooms;

    public function mount(RoomRate $roomRate)
    {
        $this->roomRate = $roomRate;
        $this->room_id = $roomRate->room_id;
        $this->name = $roomRate->name;
        $this->start_date = $roomRate->start_date;
        $this->end_date = $roomRate->end_date;
        $this->amount = $roomRate->amount;
        $this->extra_person_charge = $roomRate->extra_person_charge;
        $this->extended_stay_charge_per_hr = $roomRate->extended_stay_charge_per_hr;
        $this->description = $roomRate->description;
        $this->rate_type = $roomRate->rate_type;
        $this->rooms = Room::all();
    }

    public function updateIndividualRoomRate()
    {
        $this->validate([
            'room_id' => 'required|exists:prd_rooms,id',  // Ensure 'rooms' is the correct table
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'extra_person_charge' => 'nullable|numeric|min:0',
            'extended_stay_charge_per_hr' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'rate_type' => 'required|string|max:50',
        ]);

        $this->roomRate->update([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'extra_person_charge' => $this->extra_person_charge,
            'extended_stay_charge_per_hr' => $this->extended_stay_charge_per_hr,
            'description' => $this->description,
            'rate_type' => $this->rate_type,
        ]);

        session()->flash('message', 'Room Rate successfully updated!');

        return redirect()->route('admin.view-room', ['room' => $this->room_id]); // Fixed redirect
    }

    public function render()
    {
        return view('livewire.admin.room-rates.edit-individual-rate', [
            'room' => Room::find($this->room_id), // Fixed undefined variable issue
        ]);
    }
}
