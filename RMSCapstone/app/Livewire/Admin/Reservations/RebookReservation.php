<?php

namespace App\Livewire\Admin\Reservations;

use App\Models\Transaction;
use App\Models\Property;
use App\Models\TransactionProperty;
use App\Services\RoomAvailabilityService;
use App\Services\RoomRateService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RebookReservation extends Component
{
    public $transactionId;
    public $transaction;
    
    // Date properties
    public $check_in_date;
    public $original_check_out_date;
    public $stay_duration = 0;
    
    // Room properties
    public $selectedRoom;
    public $transactionProperty;
    
    // Validation and UI
    public $min_date;
    public $max_date;
    
    // Services
    protected RoomAvailabilityService $roomAvailabilityService;
    protected RoomRateService $roomRateService;

    public function boot(RoomAvailabilityService $roomAvailabilityService, RoomRateService $roomRateService)
    {
        $this->roomAvailabilityService = $roomAvailabilityService;
        $this->roomRateService = $roomRateService;
    }

    public function mount($transaction)
    {
        $this->transactionId = $transaction;
        $this->loadTransaction();
    }

    protected function loadTransaction()
    {
        $this->transaction = Transaction::with([
            'properties',
            'transactionProperties'
        ])->findOrFail($this->transactionId);

        // Set the room from the transaction
        $this->selectedRoom = $this->transaction->properties->first();
        $this->transactionProperty = $this->transaction->transactionProperties->first();
        
        if (!$this->selectedRoom || !$this->transactionProperty) {
            session()->flash('error', 'No room or transaction property found in the reservation.');
            return redirect()->route('admin.reservations-list');
        }

        // Set current dates from transaction
        $this->check_in_date = Carbon::parse($this->transaction->start_datetime)->format('Y-m-d');
        $this->original_check_out_date = Carbon::parse($this->transaction->end_datetime)->format('Y-m-d');
        
        // Get stay duration from transaction_properties table
        $this->stay_duration = $this->transactionProperty->days ?? $this->calculateOriginalStayDuration();
        
        $this->initializeDates();
    }

    public function initializeDates()
    {
        $this->min_date = Carbon::today()->addDay()->format('Y-m-d');
        $this->max_date = Carbon::today()->addMonths(6)->format('Y-m-d');
    }

    public function calculateOriginalStayDuration()
    {
        if ($this->transaction->start_datetime && $this->transaction->end_datetime) {
            $check_in = Carbon::parse($this->transaction->start_datetime);
            $check_out = Carbon::parse($this->transaction->end_datetime);
            return max(1, $check_out->diffInDays($check_in));
        }
        return 0;
    }

    public function calculateNewCheckOutDate()
    {
        if ($this->check_in_date && $this->stay_duration > 0) {
            $check_in = Carbon::parse($this->check_in_date);
            return $check_in->addDays($this->stay_duration)->format('Y-m-d');
        }
        return null;
    }

    public function updated($property)
    {
        if ($property === 'check_in_date') {
            $this->validateDates();
            $this->checkRoomAvailability();
        }
    }

    protected function validateDates()
    {
        $this->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
        ], [
            'check_in_date.after_or_equal' => 'Check-in date must be today or a future date.',
        ]);
    }

    public function checkRoomAvailability()
    {
        if (!$this->check_in_date) {
            return;
        }

        try {
            // Calculate new check-out date based on original stay duration
            $new_check_out_date = $this->calculateNewCheckOutDate();
            
            if (!$new_check_out_date) {
                return;
            }

            // Check if the room is available for the new dates (excluding current transaction)
            $isAvailable = $this->isRoomAvailableExcludingCurrent($new_check_out_date);
            
            if (!$isAvailable) {
                $this->addError('check_in_date', 'The selected room is not available for the chosen dates.');
                return;
            }

            $this->resetErrorBag('check_in_date');
        } catch (\Exception $e) {
            $this->addError('check_in_date', 'Error checking room availability: ' . $e->getMessage());
        }
    }

    protected function isRoomAvailableExcludingCurrent($new_check_out_date)
    {
        $checkIn = Carbon::parse($this->check_in_date);
        $checkOut = Carbon::parse($new_check_out_date);

        // Check for overlapping transactions excluding the current one
        $overlapping = Transaction::whereHas('properties', function ($query) {
                $query->where('properties.id', $this->selectedRoom->id);
            })
            ->where('id', '!=', $this->transactionId)
            ->whereIn('transaction_status', [
                'pending',
                'reserved',
                'receipt_verified',
                'confirmed',
                'ongoing'
            ])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('start_datetime', '<', $checkOut)
                  ->where('end_datetime', '>', $checkIn);
            })
            ->exists();

        return !$overlapping;
    }

    public function rebook()
    {
        $this->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
        ]);

        // Calculate new check-out date
        $new_check_out_date = $this->calculateNewCheckOutDate();
        if (!$new_check_out_date) {
            session()->flash('error', 'Invalid stay duration.');
            return;
        }

        // Final availability check
        $this->checkRoomAvailability();
        if ($this->getErrorBag()->hasAny(['check_in_date'])) {
            return;
        }

        try {
            DB::transaction(function () use ($new_check_out_date) {
                // Get fresh transaction data
                $transaction = Transaction::with(['properties', 'transactionProperties'])
                    ->findOrFail($this->transactionId);

                // Update transaction dates
                $transaction->update([
                    'start_datetime' => $this->check_in_date . ' 14:00:00', // Default check-in time
                    'end_datetime' => $new_check_out_date . ' 12:00:00', // Default check-out time
                    'updated_at' => now(),
                ]);

                // Update transaction_properties record
                if ($this->transactionProperty) {
                    $this->transactionProperty->update([
                        'days' => $this->stay_duration,
                        'updated_at' => now(),
                    ]);
                }

                session()->flash('success', 'Reservation successfully rebooked for new dates!');
                
                return redirect()->route('admin.view-reservation', ['transaction' => $transaction->id]);
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Error rebooking reservation: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        // Ensure selectedRoom is loaded
        if (!$this->selectedRoom && $this->transaction && $this->transaction->properties->isNotEmpty()) {
            $this->selectedRoom = $this->transaction->properties->first();
        }

        // Calculate new check-out date for display
        $new_check_out_date = $this->calculateNewCheckOutDate();

        return view('livewire.admin.reservations.rebook-reservation', [
            'room' => $this->selectedRoom,
            'new_check_out_date' => $new_check_out_date,
            'transactionProperty' => $this->transactionProperty,
        ]);
    }
}