<?php

namespace App\Livewire\Admin\RoomRates;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RoomRate;

#[Layout('layouts.app')]
class ViewRoomRate extends Component
{

    // Define public property for Room Rate
    public RoomRate $roomRate;

    // Find the model of the record
    public function deleteRoomRate(RoomRate $roomRate)
    {
        if (!$roomRate) {
            session()->flash('error', 'Room Rate not found!');
            return;
        }

        if ($roomRate) {
            // Delete the room rate
            $roomRate->delete();

            // Flash success message
            session()->flash('message', 'Room Rate successfully deleted!');

            // Redirect to the roomr rates page
            return redirect()->route('admin.room-rates');
        }
    }


    public function render()
    {
        return view('livewire.admin.room-rates.view-room-rate');
    }
}
