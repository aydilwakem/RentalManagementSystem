<?php

namespace App\Livewire\Admin\RoomRates;

use App\Models\Property;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RoomRate;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
class ViewRoomRate extends Component
{

    // Define public property for Room Rate
    public RoomRate $roomRate;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Find the model of the record
    public function deleteRoomRate()
    {
        //find if
        $roomRate = RoomRate::find($this->confirmItemDelete);
        if (!$roomRate) {
            session()->flash('error', 'Room Rate not found!');
            return;
        }

        try {
            if ($roomRate) {
                // Delete the room rate
                $roomRate->delete();

                // Flash success message
                session()->flash('message', 'Room Rate successfully deleted!');

                // Redirect to the roomr rates page
                return redirect()->route('admin.room-rates');
            }
        } catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.room-rates.view-room-rate');
    }
}
