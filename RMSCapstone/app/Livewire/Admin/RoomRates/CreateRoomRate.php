<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomRate;

class CreateRoomRate extends Component
{
    use WithFileUploads;

    public $name;
    public $room_id;
    public $start_date;
    public $end_date;
    public $amount;
    public $extra_person_charge;
    public $extended_stay_charge_per_hr;
    public $description;
    public $rate_type = 'Weekdays';

    public $rooms;

    public function mount()
    {
        $this->rooms = Room::all();
    }

    public function saveRoomRate()
    {
        // Validate the form input
        $this->validate([
            'name' => 'required|string|max:255',
            'room_id' => 'required|exists:prd_rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|integer|min:1000',
            'extra_person_charge' => 'required|numeric|min:100|max:50000.00',
            'extended_stay_charge_per_hr' => 'required|numeric|min:100|max:50000.00',
            'description' => 'nullable|string',
            'rate_type' => 'nullable|in:Weekdays,Weekend,Holiday,Peak',
        ]);


        // Create new room
        RoomRate::create([
            'name' => $this->name,
            'room_id' => $this->room_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'extra_person_charge' => $this->extra_person_charge,
            'extended_stay_charge_per_hr' => $this->extended_stay_charge_per_hr,
            'rate_type' => $this->rate_type,
        ]);

        // Reset form fields
        $this->reset(['name', 'room_id', 'start_date', 'end_date', 'amount', 'extra_person_charge', 'extended_stay_charge_per_hr', 'rate_type']);

        // Flash success message
        session()->flash('message', 'Room Rate successfully created!');

        // Redirect back to rooms list
        return redirect()->route('admin.room-rates');
    }


    public function render()
    {
        return view('livewire.admin.room-rates.create-room-rate');
    }
}
