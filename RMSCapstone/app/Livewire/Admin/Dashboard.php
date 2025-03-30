<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Room;
use App\Models\Maintenance;


class Dashboard extends Component
{
    public $newReservations;
    public $availableRooms;
    public $pendingMaintenances;

    public function mount()
    {
        $this->newReservations = Transaction::newReservations()->count();
        $this->availableRooms = Room::availableRooms()->count();
        $this->pendingMaintenances = Maintenance::pendingMaintenances()->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'newReservations' => $this->newReservations,
            'availableRooms' => $this->availableRooms,
            'pendingMaintenances' => $this->pendingMaintenances,
        ]);
    }
}
