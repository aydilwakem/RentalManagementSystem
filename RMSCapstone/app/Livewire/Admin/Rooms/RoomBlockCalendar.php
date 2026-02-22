<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Property;
use App\Services\RoomAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RoomBlockCalendar extends Component
{
    public $selectedDate = null;
    public $showModal = false;
    public $blockReason = '';
    public $blockingInProgress = false;
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];
    public $rooms;
    public $bulkMode = true; // true = block all rooms, false = block selected rooms
    public $selectedRooms = [];
    public $allRoomsSelected = true;
    
    // Current month display
    public $monthName;

    protected $roomAvailabilityService;

        private function setMonthName()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $this->monthName = $date->format('F Y');
    }
    
    public function boot(RoomAvailabilityService $roomAvailabilityService)
    {
        $this->roomAvailabilityService = $roomAvailabilityService;
    }
    
    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadRooms();
        $this->setMonthName();
        $this->generateCalendar();
    }
    
    public function loadRooms()
    {
        $this->rooms = Property::ofType('Room')
            ->with(['transactions' => function ($query) {
                $query->whereIn('transaction_status', [
                    'pending',
                    'reserved',
                    'receipt_verified',
                    'confirmed',
                    'ongoing'
                ]);
            }])
            ->get();
            

    }
    
    public function generateCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Get the first day of the month (0 = Sunday, 1 = Monday, etc.)
        $firstDayOfWeek = $startOfMonth->dayOfWeek;
        
        // Calculate days from previous month
        $daysFromPrevMonth = $firstDayOfWeek;
        
        $this->calendarDays = [];
        
        // Add days from previous month
        if ($daysFromPrevMonth > 0) {
            $prevMonth = $startOfMonth->copy()->subMonth();
            $daysInPrevMonth = $prevMonth->daysInMonth;
            
            for ($i = 0; $i < $daysFromPrevMonth; $i++) {
                $dayNumber = $daysInPrevMonth - $daysFromPrevMonth + $i + 1;
                $date = $prevMonth->copy()->day($dayNumber);
                $this->calendarDays[] = [
                    'day' => $dayNumber,
                    'date' => $date->format('Y-m-d'),
                    'isCurrentMonth' => false,
                    'isToday' => $date->isToday(),
                    'isBlocked' => $this->isDateBlocked($date->format('Y-m-d')),
                    'reservationsCount' => $this->getReservedRoomsCount($date->format('Y-m-d'))                ];
            }
        }
        
        // Add current month days
        for ($i = 1; $i <= $startOfMonth->daysInMonth; $i++) {
            $date = $startOfMonth->copy()->day($i);
            $this->calendarDays[] = [
                'day' => $i,
                'date' => $date->format('Y-m-d'),
                'isCurrentMonth' => true,
                'isToday' => $date->isToday(),
                'isBlocked' => $this->isDateBlocked($date->format('Y-m-d')),
                'reservationsCount' => $this->getReservedRoomsCount($date->format('Y-m-d'))
            ];
        }
        
        // Add next month days to complete the grid (42 cells for 6 weeks)
        $remainingCells = 42 - count($this->calendarDays);
        if ($remainingCells > 0) {
            $nextMonth = $endOfMonth->copy()->addMonth();
            for ($i = 1; $i <= $remainingCells; $i++) {
                $date = $nextMonth->copy()->day($i);
                $this->calendarDays[] = [
                    'day' => $i,
                    'date' => $date->format('Y-m-d'),
                    'isCurrentMonth' => false,
                    'isToday' => $date->isToday(),
                    'isBlocked' => $this->isDateBlocked($date->format('Y-m-d')),
                    'reservationsCount' => $this->getReservedRoomsCount($date->format('Y-m-d'))
                ];
            }
        }
    }
    
    private function getBlockedRoomsCount($date)
    {
        $count = 0;
        foreach ($this->rooms as $room) {
            if ($room->isBlockedOn($date)) {
                $count++;
            }
        }
        return $count;
    }
    
    private function getReservedRoomsCount($date)
    {
        $count = 0;
        $dateCarbon = Carbon::parse($date);
        $nextDay = $dateCarbon->copy()->addDay();
        
        foreach ($this->rooms as $room) {
            // Check if room has an active reservation on this date
            $hasReservation = $room->transactions->contains(function ($transaction) use ($dateCarbon, $nextDay) {
                $start = Carbon::parse($transaction->start_datetime);
                $end = Carbon::parse($transaction->end_datetime);
                
                // Check if date falls within reservation period
                return $start->lt($nextDay) && $end->gt($dateCarbon);
            });
            
            if ($hasReservation) {
                $count++;
            }
        }
        return $count;
    }

        private function isDateBlocked($date)
    {
    foreach ($this->rooms as $room) {
        if ($room->isBlockedOn($date)) {
            return true;
        }
    }
    return false;

    }
    
    private function getAvailableRoomsCount($date)
    {
        return $this->totalRooms - $this->getBlockedRoomsCount($date) - $this->getReservedRoomsCount($date);
    }
    
    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $date->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->setMonthName();
        $this->generateCalendar();
    }
    
    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $date->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->setMonthName();
        $this->generateCalendar();
    }

    
    public function goToToday()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->setMonthName();
        $this->generateCalendar();
    }
    
    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->showModal = true;
    }
    

    
    
    public function blockRooms()
    {
        $this->blockingInProgress = true;
        
        try {
            $reservationsCount = $this->getReservedRoomsCount($this->selectedDate);
            $successCount = 0;
            
            foreach ($this->rooms as $room) {
                $room->toggleBlock($this->selectedDate);
                $successCount++;
            }
            
            // Log the action
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date' => $this->selectedDate,
                    'rooms_count' => $successCount,
                    'reason' => $this->blockReason,
                    'had_reservations' => $reservationsCount > 0
                ])
                ->log('Rooms blocked for date');
            
            $message = "Successfully blocked all rooms for " . Carbon::parse($this->selectedDate)->format('M d, Y');
            
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to block rooms: ' . $e->getMessage());
        }
        
        $this->blockingInProgress = false;
        $this->showModal = false;
        $this->blockReason = '';
        $this->generateCalendar(); // Refresh calendar
    }
    
    public function unblockRooms()
    {
        $this->blockingInProgress = true;
        
        try {
            $successCount = 0;
            foreach ($this->rooms as $room) {
                $blockedDates = $room->blocked_dates ?? [];
                if (in_array($this->selectedDate, $blockedDates)) {
                    $blockedDates = array_values(array_diff($blockedDates, [$this->selectedDate]));
                    $room->blocked_dates = $blockedDates;
                    $room->save();
                    $successCount++;
                }
            }
            
            // Log the action
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date' => $this->selectedDate,
                    'rooms_count' => $successCount,
                    'reason' => $this->blockReason
                ])
                ->log('Rooms unblocked for date');
            
            session()->flash('message', "Successfully unblocked all rooms for " . Carbon::parse($this->selectedDate)->format('M d, Y'));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unblock rooms: ' . $e->getMessage());
        }
        
        $this->blockingInProgress = false;
        $this->showModal = false;
        $this->blockReason = '';
        $this->generateCalendar(); // Refresh calendar
    }
    
    public function render()
    {
        $monthName = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->format('F Y');
        
        return view('livewire.admin.rooms.room-block-calendar', [
            'monthName' => $monthName,
            'weekDays' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        ]);
    }
}