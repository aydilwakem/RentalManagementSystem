<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RoomRate;
use App\Models\Room;

#[Layout('layouts.app')]
class EditRoomRate extends Component
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
    public $roomRateId;
    public $freebies;
    public $priority;
    public $is_active = true;
    public $min_stay_nights;
    public $max_stay_nights;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(RoomRate $roomRate)
    {
        $this->rooms = Room::all();
        $this->roomRate = $roomRate;
        $this->roomRateId = $roomRate->id;
        $this->room_id = $roomRate->room_id;
        $this->name = $roomRate->name;
        $this->start_date = $roomRate->start_date;
        $this->end_date = $roomRate->end_date;
        $this->amount = $roomRate->amount;
        $this->description = $roomRate->description;
        $this->rate_type = $roomRate->rate_type;
        $this->freebies = $roomRate->freebies;
        $this->priority = $roomRate->priority;
        $this->is_active = $roomRate->is_active;
        $this->min_stay_nights = $roomRate->min_stay_nights;
        $this->max_stay_nights = $roomRate->max_stay_nights;
    }

    public function updateRoomRate()
    {
        try {
            $this->validate([
                'room_id' => 'required|exists:prd_rooms,id',
                'name' => "required|string|max:255|unique:prd_room_rates,name,{$this->roomRateId},id",
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'rate_type' => 'required|string|max:50',
                'freebies' => 'nullable|string|max:1000',
                'priority' => 'nullable|integer|min:1|max:10',
                'is_active' => 'required|boolean',
                'min_stay_nights' => 'nullable|integer|min:1|max:30',
                'max_stay_nights' => 'nullable|integer|min:1|max:90',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        $this->roomRate->update([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'description' => $this->description,
            'rate_type' => $this->rate_type,
            'freebies' => $this->freebies,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'min_stay_nights' => $this->min_stay_nights,
            'max_stay_nights' => $this->max_stay_nights,
        ]);

        session()->flash('message', 'Room Rate successfully updated!');
        return redirect()->route('admin.room-rates');
    }

    public function render()
    {
        return view('livewire.admin.room-rates.edit-room-rate');
    }
}
