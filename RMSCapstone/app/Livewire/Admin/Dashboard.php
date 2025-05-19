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
    public $hasDashboardAccess = false;

    public function mount()
    {
        $user = Auth::user();
        $this->first_name = $user->name;
        $this->last_name = $user->last_name;

        // If user has no role
        if (!$user->AnyRoles()->exists()) {
            return redirect()->route('no-access');
        }

        if ($user->can('dashboard-view')) {
            $this->hasDashboardAccess = true;

            // Only fetch dashboard data if permitted
            $this->newReservations = Transaction::newReservations()->count();
            $this->pendingMaintenances = Maintenance::pendingMaintenances()->count();
            $this->reservations = Transaction::all();
            $allTransactions = Transaction::with('reservationType', 'transactionUser')->get();

            foreach ($allTransactions as $transaction) {
                $this->events[] = [
                    'title' => $transaction->transactionUser->first_name . ' ' . $transaction->transactionUser->last_name,
                    'start' => $transaction->start_datetime,
                    'end' => $transaction->end_datetime,
                    'type' => $transaction->reservationType?->name ?? 'N/A',
                    'category' => 'transaction',
                    'id' => $transaction->id,
                    'transaction_status' => $transaction->transaction_status,
                ];
            }
        }
    }


    public function render()
    {
        if ($this->hasDashboardAccess) {
            return view('livewire.admin.dashboard', [
                'newReservations' => $this->newReservations,
                'pendingMaintenances' => $this->pendingMaintenances,
                'events' => $this->events,
            ]);
        }

        return view('livewire.admin.welcome-page', [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ]);
    }
}
