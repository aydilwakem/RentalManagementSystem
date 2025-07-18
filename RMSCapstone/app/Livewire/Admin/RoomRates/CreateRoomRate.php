<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomRate;
use Carbon\Carbon;

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

    public $confirmCreateItem = false;

    public $rooms;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->rooms = Room::all();
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->format('Y-m-d');
        $this->end_date = $now->copy()->addDay()->format('Y-m-d');
    }

    public function saveRoomRate()
    {
        try{
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
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }


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
