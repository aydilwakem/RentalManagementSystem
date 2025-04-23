<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;


class Dashboard extends Component
{
    public $newReservations;
    public $availableRooms;
    public $pendingMaintenances;
    public $reservations;
    public $events = [];
    public $first_name;
    public $last_name;


    public function mount()
    {

        // Name of the user logged in
        $this->first_name = Auth::user()->name;
        $this->last_name = Auth::user()->last_name;

        // Checks if the user has a role
        if (!Auth::user()->AnyRoles()->exists()) {
            return redirect()->route('no-access'); // Redirect to 'no-access' page
        }

        $this->newReservations = Transaction::newReservations()->count();
        $this->pendingMaintenances = Maintenance::pendingMaintenances()->count();
        $this->reservations = Transaction::all();

        foreach ($this->reservations as $reservation) {
            $this->events[] = [
                'title' => $reservation->first_name,
                'start' => $reservation->check_in_date,
            ];
        }
    }



    public function render()
    {
        return view('livewire.admin.dashboard', [
            'newReservations' => $this->newReservations,
            'pendingMaintenances' => $this->pendingMaintenances,
        ]);
    }
}
