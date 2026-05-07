<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Property;
use App\Services\RoomAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

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

    // Room selection modes
    public $bulkMode = true; // true = block all rooms, false = block selected rooms
    public $selectedRooms = [];
    public $allRoomsSelected = false;

    // For individual room actions
    public $showRoomSelectionModal = false;
    public $selectedRoomId = null;
    public $roomBlockDates = []; // For multi-date selection

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

        // DON'T automatically select all rooms
        // Just initialize as empty array
        $this->selectedRooms = [];
        $this->updateAllRoomsSelected();
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

    // Add a new date field
    public function addDateField()
    {
        $this->roomBlockDates[] = '';
    }

    // Remove a date field
    public function removeDateField($index)
    {
        unset($this->roomBlockDates[$index]);
        $this->roomBlockDates = array_values($this->roomBlockDates); // Re-index array
    }

    public function updatedBulkMode($value)
    {
        // Clear selections when switching to bulk mode
        if ($value) {
            $this->selectedRooms = [];
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;

        // Reset selected rooms based on mode
        if ($this->bulkMode) {
            // For bulk mode, we don't need selected rooms
            $this->selectedRooms = [];
        } else {
            // For selective mode, start with empty selection
            $this->selectedRooms = [];
        }

        $this->showModal = true;
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
                $dateString = $date->format('Y-m-d');
                $this->calendarDays[] = [
                    'day' => $dayNumber,
                    'date' => $dateString,
                    'isCurrentMonth' => false,
                    'isToday' => $date->isToday(),
                    'isBlocked' => $this->isDateBlocked($dateString),
                    'blockedRooms' => $this->getBlockedRoomsList($dateString),
                    'reservationsCount' => $this->getReservedRoomsCount($dateString),
                    'totalRooms' => $this->rooms->count(),
                    'blockedRoomsCount' => $this->getBlockedRoomsCount($dateString),
                ];
            }
        }

        // Add current month days
        for ($i = 1; $i <= $startOfMonth->daysInMonth; $i++) {
            $date = $startOfMonth->copy()->day($i);
            $dateString = $date->format('Y-m-d');
            $this->calendarDays[] = [
                'day' => $i,
                'date' => $dateString,
                'isCurrentMonth' => true,
                'isToday' => $date->isToday(),
                'isBlocked' => $this->isDateBlocked($dateString),
                'blockedRooms' => $this->getBlockedRoomsList($dateString),
                'reservationsCount' => $this->getReservedRoomsCount($dateString),
                'totalRooms' => $this->rooms->count(),
                'blockedRoomsCount' => $this->getBlockedRoomsCount($dateString),
            ];
        }

        // Add next month days to complete the grid (42 cells for 6 weeks)
        $remainingCells = 42 - count($this->calendarDays);
        if ($remainingCells > 0) {
            $nextMonth = $endOfMonth->copy()->addMonth();
            for ($i = 1; $i <= $remainingCells; $i++) {
                $date = $nextMonth->copy()->day($i);
                $dateString = $date->format('Y-m-d');
                $this->calendarDays[] = [
                    'day' => $i,
                    'date' => $dateString,
                    'isCurrentMonth' => false,
                    'isToday' => $date->isToday(),
                    'isBlocked' => $this->isDateBlocked($dateString),
                    'blockedRooms' => $this->getBlockedRoomsList($dateString),
                    'reservationsCount' => $this->getReservedRoomsCount($dateString),
                    'totalRooms' => $this->rooms->count(),
                    'blockedRoomsCount' => $this->getBlockedRoomsCount($dateString),
                ];
            }
        }
    }

    private function buildDayData($date, $isCurrentMonth)
    {
        $dateString = $date->format('Y-m-d');

        return [
            'day' => $date->day,
            'date' => $dateString,
            'isCurrentMonth' => $isCurrentMonth,
            'isToday' => $date->isToday(),
            'isBlocked' => $this->isDateBlocked($dateString),
            'blockedRooms' => $this->getBlockedRoomsList($dateString),
            'reservationsCount' => $this->getReservedRoomsCount($dateString),
            'availableRoomsCount' => $this->getAvailableRoomsCount($dateString),
            'totalRooms' => $this->rooms->count(),
            'blockedRoomsCount' => $this->getBlockedRoomsCount($dateString),
        ];
    }

    private function getBlockedRoomsList($date)
    {
        $blockedRooms = [];
        foreach ($this->rooms as $room) {
            if ($room->isBlockedOn($date)) {
                $blockedRooms[] = [
                    'id' => $room->id,
                    'name' => $room->name_number,
                    'code' => $room->code ?? $room->name_number
                ];
            }
        }
        return $blockedRooms;
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
        return $this->rooms->count() - $this->getBlockedRoomsCount($date) - $this->getReservedRoomsCount($date);
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

    // New method: Select a specific room for blocking
    // Updated: Select a specific room for blocking
    public function selectRoomForBlocking($roomId, $date)
    {
        $this->selectedRoomId = $roomId;
        $this->selectedDate = $date;
        $this->roomBlockDates = []; // Start with empty array (no additional dates)
        $this->blockReason = '';
        $this->showRoomSelectionModal = true;
    }
    // New method: Toggle room selection
    public function toggleRoomSelection($roomId)
    {
        if (in_array($roomId, $this->selectedRooms)) {
            $this->selectedRooms = array_diff($this->selectedRooms, [$roomId]);
        } else {
            $this->selectedRooms[] = $roomId;
        }

        $this->updateAllRoomsSelected();
    }

    // New method: Select/deselect all rooms
    public function toggleAllRooms()
    {
        if ($this->allRoomsSelected) {
            $this->selectedRooms = [];
        } else {
            $this->selectedRooms = $this->rooms->pluck('id')->toArray();
        }

        $this->allRoomsSelected = !$this->allRoomsSelected;
    }

    // New method: Update all rooms selected flag
    private function updateAllRoomsSelected()
    {
        $this->allRoomsSelected = count($this->selectedRooms) === $this->rooms->count();
    }


    // Updated: Block rooms with selection support
    public function blockRooms()
    {
        $this->blockingInProgress = true;

        try {
            // Determine which rooms to block
            if ($this->bulkMode) {
                // Bulk mode: block ALL rooms
                $roomsToBlock = $this->rooms;
            } else {
                // Selective mode: block only selected rooms
                $roomsToBlock = $this->rooms->whereIn('id', $this->selectedRooms);
            }

            if ($roomsToBlock->isEmpty()) {
                session()->flash('error', 'No rooms selected to block.');
                $this->blockingInProgress = false;
                return;
            }

            $successCount = 0;
            $alreadyBlockedCount = 0;
            $reservedCount = 0;

            foreach ($roomsToBlock as $room) {
                // Check if room has reservation on this date
                if ($this->roomHasReservation($room, $this->selectedDate)) {
                    $reservedCount++;
                    continue;
                }

                // Check if already blocked
                if ($room->isBlockedOn($this->selectedDate)) {
                    $alreadyBlockedCount++;
                    continue;
                }

                $room->toggleBlock($this->selectedDate);
                $successCount++;
            }

            // Log the action
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date' => $this->selectedDate,
                    'rooms_count' => $successCount,
                    'already_blocked' => $alreadyBlockedCount,
                    'had_reservations' => $reservedCount,
                    'reason' => $this->blockReason,
                    'bulk_mode' => $this->bulkMode,
                    'selected_rooms' => $this->bulkMode ? 'all' : $this->selectedRooms
                ])
                ->log('Rooms blocked for date');

            $message = "Successfully blocked ";
            if ($this->bulkMode) {
                $message .= "all {$successCount} rooms";
            } else {
                $message .= "{$successCount} room(s)";
            }
            $message .= " for " . Carbon::parse($this->selectedDate)->format('M d, Y');

            if ($alreadyBlockedCount > 0) {
                $message .= ". {$alreadyBlockedCount} rooms were already blocked.";
            }

            if ($reservedCount > 0) {
                $message .= ". {$reservedCount} rooms have reservations and were not blocked.";
            }

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to block rooms: ' . $e->getMessage());
            Log::error('Room blocking failed: ' . $e->getMessage());
        }

        $this->blockingInProgress = false;
        $this->showModal = false;
        $this->showRoomSelectionModal = false;
        $this->blockReason = '';
        $this->selectedRoomId = null;
        $this->generateCalendar(); // Refresh calendar
    }

    // New method: Block a single room for multiple dates
    // Updated: Block a single room for multiple dates
    public function blockRoomForDates()
    {
        $this->blockingInProgress = true;

        try {
            $room = $this->rooms->find($this->selectedRoomId);

            if (!$room) {
                session()->flash('error', 'Room not found.');
                $this->blockingInProgress = false;
                return;
            }

            $successCount = 0;
            $skippedCount = 0;

            // Array to store all dates to block (including the original selected date)
            $datesToBlock = [];

            // Add the original selected date
            if (!empty($this->selectedDate)) {
                $datesToBlock[] = $this->selectedDate;
            }

            // Add additional dates from roomBlockDates
            foreach ($this->roomBlockDates as $date) {
                if (!empty($date) && !in_array($date, $datesToBlock)) {
                    $datesToBlock[] = $date;
                }
            }

            // Block each date
            foreach ($datesToBlock as $date) {
                // Skip if room has reservation on this date
                if ($this->roomHasReservation($room, $date)) {
                    $skippedCount++;
                    continue;
                }

                // Check if already blocked
                if ($room->isBlockedOn($date)) {
                    $skippedCount++;
                    continue;
                }

                $room->toggleBlock($date);
                $successCount++;
            }

            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'room_id' => $room->id,
                    'room_name' => $room->name_number,
                    'original_date' => $this->selectedDate,
                    'additional_dates' => array_filter($this->roomBlockDates),
                    'success_count' => $successCount,
                    'skipped_count' => $skippedCount,
                    'reason' => $this->blockReason
                ])
                ->log('Room blocked for multiple dates');

            $message = "Successfully blocked room {$room->name_number} for {$successCount} date(s)";
            if ($skippedCount > 0) {
                $message .= ". {$skippedCount} date(s) were skipped (already blocked or has reservation).";
            }

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to block room: ' . $e->getMessage());
        }

        $this->blockingInProgress = false;
        $this->showRoomSelectionModal = false;
        $this->blockReason = '';
        $this->roomBlockDates = [];
        $this->selectedRoomId = null;
        $this->selectedDate = null;
        $this->generateCalendar();
    }

    // Updated: Unblock rooms with selection support
    public function unblockRooms()
    {
        $this->blockingInProgress = true;

        try {
            // Determine which rooms to unblock
            if ($this->bulkMode) {
                // Bulk mode: unblock ALL rooms
                $roomsToUnblock = $this->rooms;
            } else {
                // Selective mode: unblock only selected rooms
                $roomsToUnblock = $this->rooms->whereIn('id', $this->selectedRooms);
            }

            if ($roomsToUnblock->isEmpty()) {
                session()->flash('error', 'No rooms selected to unblock.');
                $this->blockingInProgress = false;
                return;
            }

            $successCount = 0;
            $notBlockedCount = 0;

            foreach ($roomsToUnblock as $room) {
                $blockedDates = $room->blocked_dates ?? [];
                if (in_array($this->selectedDate, $blockedDates)) {
                    $blockedDates = array_values(array_diff($blockedDates, [$this->selectedDate]));
                    $room->blocked_dates = $blockedDates;
                    $room->save();
                    $successCount++;
                } else {
                    $notBlockedCount++;
                }
            }

            // Log the action
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'date' => $this->selectedDate,
                    'rooms_count' => $successCount,
                    'not_blocked' => $notBlockedCount,
                    'reason' => $this->blockReason,
                    'bulk_mode' => $this->bulkMode,
                    'selected_rooms' => $this->bulkMode ? 'all' : $this->selectedRooms
                ])
                ->log('Rooms unblocked for date');

            $message = "Successfully unblocked ";
            if ($this->bulkMode) {
                $message .= "all {$successCount} rooms";
            } else {
                $message .= "{$successCount} room(s)";
            }
            $message .= " for " . Carbon::parse($this->selectedDate)->format('M d, Y');

            if ($notBlockedCount > 0) {
                $message .= ". {$notBlockedCount} rooms were not blocked.";
            }

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unblock rooms: ' . $e->getMessage());
        }

        $this->blockingInProgress = false;
        $this->showModal = false;
        $this->blockReason = '';
        $this->generateCalendar(); // Refresh calendar
    }



    // New helper method: Check if room has reservation on specific date
    private function roomHasReservation($room, $date)
    {
        $dateCarbon = Carbon::parse($date);
        $nextDay = $dateCarbon->copy()->addDay();

        return $room->transactions->contains(function ($transaction) use ($dateCarbon, $nextDay) {
            $start = Carbon::parse($transaction->start_datetime);
            $end = Carbon::parse($transaction->end_datetime);
            return $start->lt($nextDay) && $end->gt($dateCarbon);
        });
    }

    // New method: Get room details for a date
    public function getRoomStatusForDate($roomId, $date)
    {
        $room = $this->rooms->find($roomId);
        if (!$room) {
            return null;
        }

        return [
            'isBlocked' => $room->isBlockedOn($date),
            'hasReservation' => $this->roomHasReservation($room, $date),
            'roomName' => $room->name_number,
            'roomCode' => $room->code ?? $room->name_number
        ];
    }

    public function clearSelection()
    {
        $this->selectedRooms = [];
    }


    public function render()
    {
        $monthName = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->format('F Y');

        return view('livewire.admin.rooms.room-block-calendar', [
            'monthName' => $monthName,
            'weekDays' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'totalRooms' => $this->rooms->count(),
            'availableRooms' => $this->rooms->count() // You might want to calculate this dynamically
        ]);
    }
}
