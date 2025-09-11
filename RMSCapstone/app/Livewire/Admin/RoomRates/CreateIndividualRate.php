<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use App\Models\Property;
use App\Models\RoomRate;
use Carbon\Carbon;
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
    public int $priority = 1;
    public $is_active = true;
    public $min_stay_nights;
    public $max_stay_nights;
    public $rate_percentage = null;


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
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format('Y-m-d');
        $this->end_date = $now->copy()->endOfMonth()->format('Y-m-d');
    }

    public function getAdjustedRateProperty()
    {
        if (is_numeric($this->rate_percentage)) {
            return $this->room->amount + ($this->room->amount * ($this->rate_percentage / 100));
        }
        return null;
    }

    public function saveIndividualRoomRate()
    {
        // Validate the form input
        $this->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rate_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'rate_type' => 'required|in:Weekdays,Weekend,Holiday,Peak',
            'freebies' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:1|max:10',
            'is_active' => 'required|boolean',
            'min_stay_nights' => 'nullable|integer|min:1|max:30',
            'max_stay_nights' => 'nullable|integer|min:1|max:90',
        ]);

        $adjustedAmount = $this->room->amount + ($this->room->amount * ($this->rate_percentage / 100));

        RoomRate::create([
            'name' => $this->name,
            'property_id' => $this->roomId,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount' => $adjustedAmount,
            'rate_type' => $this->rate_type,
            'description' => $this->description,
            'freebies' => (bool) $this->freebies,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'rate_percentage' => $this->rate_percentage,
            'min_stay_nights' => $this->min_stay_nights === '' ? null : $this->min_stay_nights,
            'max_stay_nights' => $this->max_stay_nights === '' ? null : $this->max_stay_nights,
        ]);


        // Reset form fields (excluding `roomId`)
        $this->reset(['name', 'start_date', 'end_date', 'amount', 'description', 'rate_type', 'freebies', 'priority', 'is_active', 'rate_percentage', 'min_stay_nights', 'max_stay_nights']);

        // Flash success message
        session()->flash('message', 'Room Rate successfully created!');

        // Redirect to the room view page
        return redirect()->route('admin.view-room', ['room' => $this->roomId]);
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
