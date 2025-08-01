<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use App\Models\Property;
use App\Models\RoomRate;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class EditIndividualRate extends Component
{
    public RoomRate $roomRate;
    public $room_id;
    public $name;
    public $start_date;
    public $end_date;
    public $amount;
    public $description;
    public $rate_type;
    public $rooms;
    public $freebies;
    public $priority;
    public $is_active = true;
    public $min_stay_nights;
    public $max_stay_nights;

    public function render()
    {
        return view('livewire.admin.room-rates.edit-individual-rate', [
            'room' => Property::find($this->room_id), // Fixed undefined variable issue
        ]);
    }

    public function mount(RoomRate $roomRate)
    {
        $this->rooms = Property::all();
        $this->roomRate = $roomRate;
        $this->room_id = $roomRate->property_id;
        $this->name = $roomRate->name;
        $this->start_date = $roomRate->start_date;
        $this->end_date = $roomRate->end_date;
        $this->amount = $roomRate->amount;
        $this->description = $roomRate->description;
        $this->rate_type = $roomRate->rate_type;
        $this->freebies = $roomRate->freebies;
        $this->priority = $roomRate->priority ?: null;
        $this->is_active = $roomRate->is_active;
        $this->min_stay_nights = $roomRate->min_stay_nights ?: null;
        $this->max_stay_nights = $roomRate->max_stay_nights ?: null;
    }

    public function updateIndividualRoomRate()
    {
        Log::info('Update individual room rate called');

        $this->validate([
            'room_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'rate_type' => 'required|string|max:50',
            'priority' => 'nullable|integer|min:1|max:10',
            'is_active' => 'required|boolean',
            'min_stay_nights' => 'nullable|integer|min:1|max:30',
            'max_stay_nights' => 'nullable|integer|min:1|max:90',
        ]);

        Log::info('Validation is successful');

        // Sanitize fields that can be null
        foreach (['priority', 'min_stay_nights', 'max_stay_nights'] as $field) {
            $this->$field = $this->$field === '' ? null : $this->$field;
        }

        // Update the room rate
        Log::info('Updating room rate with data: ', [
            'property_id' => $this->room_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'description' => $this->description,
            'rate_type' => $this->rate_type,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'min_stay_nights' => $this->min_stay_nights,
            'max_stay_nights' => $this->max_stay_nights,
        ]);

        $this->roomRate->update([
            'property_id' => $this->room_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $this->amount,
            'description' => $this->description,
            'rate_type' => $this->rate_type,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'min_stay_nights' => $this->min_stay_nights,
            'max_stay_nights' => $this->max_stay_nights,
        ]);

        session()->flash('message', 'Room Rate successfully updated!');
        return redirect()->route('admin.view-room', ['room' => $this->room_id]);
    }


    public function increment()
    {
        if ($this->priority < 10) {
            $this->priority++;
        }
    }

    public function decrement()
    {
        if ($this->priority > 1) {
            $this->priority--;
        }
    }
}
