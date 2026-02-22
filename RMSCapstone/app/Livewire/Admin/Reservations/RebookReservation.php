<?php

namespace App\Livewire\Admin\Reservations;

use App\Models\Transaction;
use App\Models\Property;
use App\Models\TransactionProperty;
use App\Models\Activity;
use App\Models\Service;
use App\Models\GuestDetail;
use App\Models\GuestPet;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceDiscount;
use App\Models\PromoCode;
use App\Services\InvoiceService;
use App\Services\RoomAvailabilityService;
use App\Services\RoomRateService;
use App\Services\TransactionLoader;
use App\Services\ServiceBag;
use App\Services\RoomCartService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RebookReservation extends Component
{
    public $transaction;
    public $transactionUser;
    public $invoice;
    public $guestDetails;
    public $activities;
    public $properties;
    public $payments;
    public $services;
    public $guestPets;
    public $transactionProperties;
    public $allItems = [];

    // Date change related properties
    public $new_check_in_date;
    public $new_check_out_date;
    public $stay_duration = 0;
    public $min_date;
    public $max_date;
    public $original_check_in;
    public $original_check_out;

    // Room change related properties (from ViewReservation)
    public $showChangeRoomModal = false;
    public $changingRoomPivotId;
    public $availableRooms = [];
    public $selectedNewRoomId;
    public $currentRoomDetails;

    // Rebooking summary
    public $showRebookSummary = false;
    public $tempRoomRates = []; // Stores recalculated rates for display and room changes

    // Services
    protected RoomAvailabilityService $roomAvailabilityService;
    protected RoomRateService $roomRateService;
    protected TransactionLoader $loader;
    protected RoomCartService $roomCartService;
    protected InvoiceService $invoiceService;


    public function boot(ServiceBag $services)
    {
        $this->loader = $services->loader;
        $this->roomCartService = $services->roomCartService;
        $this->roomAvailabilityService = $services->roomAvailabilityService;
        $this->roomRateService = $services->roomRateService;
        $this->invoiceService = $services->invoiceService; 

    }

    public function mount(Transaction $transaction)
    {
        // Load all transaction data using the same loader as ViewReservation
        $data = $this->loader->load($transaction);
        $this->transaction = $data['transaction'];
        $this->transactionUser = $data['transactionUser'];
        $this->invoice = $data['invoice'];
        $this->guestDetails = $data['guestDetails'];
        $this->activities = $data['activities'];
        $this->properties = $data['properties'];
        $this->services = $data['services'];
        $this->guestPets = $data['guestPets'];
        $this->payments = $data['payments'];

        $this->transactionProperties = TransactionProperty::with('property')
            ->where('transaction_id', $this->transaction->id)
            ->get();

        // Load all invoice items for display
        $this->loadAllInvoiceItems();

        // Set original dates
        $this->original_check_in = Carbon::parse($this->transaction->start_datetime)->format('Y-m-d');
        $this->original_check_out = Carbon::parse($this->transaction->end_datetime)->format('Y-m-d');
        
        // Set new dates (default to original)
        $this->new_check_in_date = $this->original_check_in;
        $this->new_check_out_date = $this->original_check_out;
        
        // Calculate stay duration
        $this->stay_duration = $this->calculateStayDuration();
        
        // Initialize date constraints
        $this->initializeDates();
    }


    /**
 * Recalculate invoice totals after changes
 */
public function recalculateInvoice()
{
    if (!$this->invoice) {
        return;
    }
    
    $this->invoiceService->updateDiscountTotal($this->invoice, $this->transaction);
    $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
    $this->invoiceService->updateBalanceDue($this->invoice);
    $this->invoiceService->updateStatus($this->invoice);
    
    $this->invoice = $this->invoice->fresh();
}



public function loadAllInvoiceItems()
{
    $items = [];
    $roomRateService = app(RoomRateService::class);

    // Safely handle properties
    if ($this->transaction && $this->transaction->properties) {
        foreach ($this->transaction->properties as $property) {
            $rateSummary = $roomRateService->getRateSummary(
                $property,
                $this->transaction->start_datetime,
                $this->transaction->end_datetime
            );
            
            $dynamicTotalRate = $rateSummary['total_amount'] ?? 0;
            $nights = $rateSummary['nights'] ?? ($property->pivot->days ?? 1);

            $items[] = [
                'type' => 'property',
                'name' => 'Room – ' . ($property->name_number ?? 'Unknown'),
                'quantity' => 1,
                'days' => $nights,
                'extra_guest' => $property->pivot->extra_guest ?? 0,
                'extra_charge' => $property->extra_person_charge ?? 0,
                'extra_charge_total' => $property->pivot->extra_charge ?? 0,
                'amount' => $nights > 0 ? $dynamicTotalRate / $nights : ($property->amount ?? 0),
                'total' => $property->pivot->total_amount ?? 0,
                'created_at' => $property->pivot->created_at ?? now(),
                'payment_status' => $property->pivot->payment_status ?? 'unpaid',
                'id' => $property->id,
                'pivot_id' => $property->pivot->id ?? null,
            ];
        }
    }

    // Safely handle activities
    if ($this->transaction && $this->transaction->activities) {
        foreach ($this->transaction->activities as $activity) {
            $items[] = [
                'type' => 'activity',
                'name' => $activity->name ?? 'Unknown Activity',
                'quantity' => $activity->pivot->quantity ?? 0,
                'days' => null,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $activity->amount ?? 0,
                'total' => $activity->pivot->amount ?? 0,
                'created_at' => $activity->pivot->created_at ?? now(),
                'payment_status' => $activity->pivot->payment_status ?? 'unpaid',
                'id' => $activity->id,
                'pivot_id' => $activity->pivot->id ?? null,
            ];
        }
    }

    // Safely handle services
    if ($this->transaction && $this->transaction->services) {
        foreach ($this->transaction->services as $service) {
            $propertyName = null;
            $propertyExtraHourCharge = null;

            if (strtolower($service->name ?? '') === 'extra hour' && ($service->pivot->property_id ?? null)) {
                $property = \App\Models\Property::find($service->pivot->property_id);
                $propertyName = $property?->name_number;
                $propertyExtraHourCharge = $property?->extra_charge_per_hour;
            }

            $items[] = [
                'type' => 'service',
                'service_id' => $service->pivot->service_id ?? null,
                'service_name' => $service->name ?? '',
                'property_id' => $service->pivot->property_id ?? null,
                'name' => $service->name ?? 'Unknown Service',
                'property_name' => $propertyName,
                'property_extra_hour_charge' => $propertyExtraHourCharge,
                'quantity' => $service->pivot->quantity ?? 0,
                'days' => $service->pivot->days ?? null,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $service->amount ?? 0,
                'total' => $service->pivot->amount ?? 0,
                'created_at' => $service->pivot->created_at ?? now(),
                'payment_status' => $service->pivot->payment_status ?? 'unpaid',
                'unit' => $service->unit ?? '',
                'pivot_id' => $service->pivot->id ?? null,
            ];
        }
    }

    // Sort by created_at safely
    usort($items, function ($a, $b) {
        $aTime = isset($a['created_at']) && $a['created_at'] ? $a['created_at']->timestamp ?? 0 : 0;
        $bTime = isset($b['created_at']) && $b['created_at'] ? $b['created_at']->timestamp ?? 0 : 0;
        return $aTime <=> $bTime;
    });

    $this->allItems = $items;
}

    public function initializeDates()
    {
        $this->min_date = Carbon::today()->format('Y-m-d');
        $this->max_date = Carbon::today()->addMonths(6)->format('Y-m-d');
    }

    public function calculateStayDuration()
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
        if ($this->new_check_in_date && $this->stay_duration > 0) {
            $check_in = Carbon::parse($this->new_check_in_date);
            return $check_in->copy()->addDays($this->stay_duration)->format('Y-m-d');
        }
        return null;
    }

public function updatedNewCheckInDate()
{
    $this->validate([
        'new_check_in_date' => 'required|date|after_or_equal:today',
    ]);

    // Recalculate stay duration based on new dates
    if ($this->new_check_in_date && $this->new_check_out_date) {
        $check_in = Carbon::parse($this->new_check_in_date);
        $check_out = Carbon::parse($this->new_check_out_date);
        $this->stay_duration = max(1, $check_out->diffInDays($check_in));
    }

    // Auto-calculate new check-out date (keeping same duration)
    if ($this->new_check_in_date && $this->stay_duration > 0) {
        $check_in = Carbon::parse($this->new_check_in_date);
        $this->new_check_out_date = $check_in->copy()->addDays($this->stay_duration)->format('Y-m-d');
    }

    // Check availability for all rooms
    $this->checkAllRoomsAvailability();
    
    // Recalculate rates for all rooms based on new dates
    if (!$this->getErrorBag()->has('availability')) {
        $this->recalculateAllRoomRates();
    }
}

/**
 * Recalculate rates for all rooms based on new dates
 */
public function recalculateAllRoomRates()
{
    if (!$this->new_check_in_date || !$this->new_check_out_date) {
        return;
    }

    $checkIn = Carbon::parse($this->new_check_in_date)->setTime(15, 0, 0);
    $checkOut = Carbon::parse($this->new_check_out_date)->setTime(12, 0, 0);

    // Clear previous temp rates
    $this->tempRoomRates = [];

    foreach ($this->transactionProperties as $transactionProperty) {
        if (!$transactionProperty->property) {
            continue;
        }

        // Calculate new rates
        $rateSummary = $this->roomRateService->getRateSummary(
            $transactionProperty->property,
            $checkIn->format('Y-m-d H:i:s'),
            $checkOut->format('Y-m-d H:i:s')
        );
        
        $totalRateForStay = $rateSummary['total_amount'] ?? 0;

        // Keep existing guest counts
        $kids = $transactionProperty->kids;
        $adults = $transactionProperty->adults;

        $chargeableGuests = $kids + $adults;
        $allowedGuests = $transactionProperty->property->ideal_guest;
        $extraGuests = max(0, $chargeableGuests - $allowedGuests);

        $days = $transactionProperty->days ?? 1;
        $extraChargePerPerson = $transactionProperty->property->extra_person_charge;

        $baseAmount = $totalRateForStay;
        $extraCharge = $extraGuests * $days * $extraChargePerPerson;
        $totalAmount = $baseAmount + $extraCharge;

        // Store in temp property for display and calculations
        $this->tempRoomRates[$transactionProperty->id] = [
            'amount' => $baseAmount,
            'extra_charge' => $extraCharge,
            'total_amount' => $totalAmount,
            'days' => $days,
            'extra_guests' => $extraGuests,
        ];

        // Also update the model instance for display (but don't save to DB)
        $transactionProperty->amount = $baseAmount;
        $transactionProperty->extra_charge = $extraCharge;
        $transactionProperty->total_amount = $totalAmount;
    }

    // Refresh the properties collection with updated pivot data for display
    $this->refreshProperties();
}

    public function checkAllRoomsAvailability()
    {
        if (!$this->new_check_in_date || !$this->new_check_out_date) {
            return;
        }

        $checkIn = Carbon::parse($this->new_check_in_date)->setTime(15, 0, 0);
        $checkOut = Carbon::parse($this->new_check_out_date)->setTime(12, 0, 0);

        $unavailableRooms = [];

        foreach ($this->transaction->properties as $room) {
            $isAvailable = $this->roomAvailabilityService->isRoomAvailable(
                $room->id,
                $checkIn,
                $checkOut,
                $this->transaction->id
            );

            if (!$isAvailable) {
                $unavailableRooms[] = $room->name_number;
            }
        }

        if (!empty($unavailableRooms)) {
            $this->addError('availability', 'The following rooms are not available for the selected dates: ' . implode(', ', $unavailableRooms));
        } else {
            $this->resetErrorBag('availability');
        }
    }

    /**
     * ------------------------- ROOM CHANGE MANAGEMENT ---------------------------
     * Same as in ViewReservation
     * ----------------------------------------------------------------------------
     */

    public function resetChangeRoomModal()
    {
        $this->reset([
            'changingRoomPivotId',
            'currentRoomDetails',
            'availableRooms',
            'selectedNewRoomId',
            'showChangeRoomModal'
        ]);
    }

public function openChangeRoomModal($pivotId)
{
    Log::info('Open Change Room modal called for pivot ID: ' . $pivotId);

    $this->resetChangeRoomModal();
    $this->changingRoomPivotId = $pivotId;

    // Get current room details
    $currentPivot = TransactionProperty::with('property')->find($pivotId);
    
    if (!$currentPivot) {
        session()->flash('error', 'Room pivot not found.');
        return;
    }
    
    $this->currentRoomDetails = $currentPivot;
    $currentRoom = $currentPivot->property;

    // Get available rooms for the NEW dates (not the original ones)
    $checkIn = $this->new_check_in_date ? 
        Carbon::parse($this->new_check_in_date)->setTime(15, 0, 0) : 
        Carbon::parse($this->transaction->start_datetime);
        
    $checkOut = $this->new_check_out_date ? 
        Carbon::parse($this->new_check_out_date)->setTime(12, 0, 0) : 
        Carbon::parse($this->transaction->end_datetime);

    // Get available rooms
    $availableRooms = $this->roomAvailabilityService->getAvailableRooms(
        $checkIn->format('Y-m-d H:i:s'),
        $checkOut->format('Y-m-d H:i:s')
    );

    // Calculate rates for available rooms using the new dates
    $roomsWithRates = [];
    foreach ($availableRooms as $room) {
        $rateSummary = $this->roomRateService->getRateSummary(
            $room,
            $checkIn->format('Y-m-d H:i:s'),
            $checkOut->format('Y-m-d H:i:s')
        );
        
        // Clone the room and add the dynamic rate
        $roomWithRate = clone $room;
        $roomWithRate->dynamic_total_rate = $rateSummary['total_amount'] ?? 0;
        $roomWithRate->dynamic_nights = $rateSummary['nights'] ?? 1;
        $roomsWithRates[] = $roomWithRate;
    }

    // Create a collection
    $roomsCollection = collect($roomsWithRates);

    // Add current room with its recalculated rate if not already in the list
    if ($currentRoom) {
        $roomExists = $roomsCollection->contains(function ($room) use ($currentRoom) {
            return $room->id === $currentRoom->id;
        });

        if (!$roomExists) {
            // Calculate rate for current room with new dates
            $rateSummary = $this->roomRateService->getRateSummary(
                $currentRoom,
                $checkIn->format('Y-m-d H:i:s'),
                $checkOut->format('Y-m-d H:i:s')
            );
            
            $currentRoomWithRate = clone $currentRoom;
            $currentRoomWithRate->dynamic_total_rate = $rateSummary['total_amount'] ?? 0;
            $currentRoomWithRate->dynamic_nights = $rateSummary['nights'] ?? 1;
            $currentRoomWithRate->is_booked = false;
            $roomsCollection->push($currentRoomWithRate);
        }
    }

    $this->availableRooms = $roomsCollection;
    $this->selectedNewRoomId = $currentRoom->id ?? null;
    $this->showChangeRoomModal = true;
}

public function changeRoom()
{
    $this->validate([
        'selectedNewRoomId' => 'required|exists:properties,id',
    ]);

    try {
        $currentPivot = TransactionProperty::find($this->changingRoomPivotId);
        $newRoom = Property::find($this->selectedNewRoomId);

        if (!$currentPivot || !$newRoom) {
            throw new \Exception('Room not found.');
        }

        // Calculate new rates for the NEW dates
        $checkIn = Carbon::parse($this->new_check_in_date ?: $this->transaction->start_datetime)->setTime(15, 0, 0);
        $checkOut = Carbon::parse($this->new_check_out_date ?: $this->transaction->end_datetime)->setTime(12, 0, 0);

        $rateSummary = $this->roomRateService->getRateSummary(
            $newRoom,
            $checkIn->format('Y-m-d H:i:s'),
            $checkOut->format('Y-m-d H:i:s')
        );
        
        $totalRateForStay = $rateSummary['total_amount'] ?? 0;

        // Calculate extra guest charges if any
        $days = $currentPivot->days ?? 1;
        $extraGuests = $currentPivot->extra_guest ?? 0;
        $extraChargePerPerson = $newRoom->extra_person_charge ?? 0;
        $extraCharge = $extraGuests * $days * $extraChargePerPerson;
        
        $totalAmount = $totalRateForStay + $extraCharge;

        // Update the transaction property in the database
        $currentPivot->update([
            'property_id' => $this->selectedNewRoomId,
            'amount' => $totalRateForStay,
            'extra_charge' => $extraCharge,
            'total_amount' => $totalAmount,
            'updated_at' => now(),
        ]);

        // Update temp rates for this room
        $this->tempRoomRates[$currentPivot->id] = [
            'amount' => $totalRateForStay,
            'extra_charge' => $extraCharge,
            'total_amount' => $totalAmount,
            'days' => $days,
            'extra_guests' => $extraGuests,
        ];

        // Refresh properties to show updated rates
        $this->refreshProperties();

        $this->resetChangeRoomModal();
        session()->flash('success', 'Room changed successfully!');
    } catch (\Exception $e) {
        Log::error('Room change failed: ' . $e->getMessage());
        session()->flash('error', 'Failed to change room: ' . $e->getMessage());
    }
}

    public function confirmRebooking()
    {
        $this->validate([
            'new_check_in_date' => 'required|date|after_or_equal:today',
        ]);

        if ($this->getErrorBag()->has('availability')) {
            return;
        }

        $this->showRebookSummary = true;
    }

    public function cancelRebooking()
    {
        $this->showRebookSummary = false;
    }

public function executeRebooking()
{
    $this->validate([
        'new_check_in_date' => 'required|date|after_or_equal:today',
    ]);

    if ($this->getErrorBag()->has('availability')) {
        return;
    }

    try {
        DB::transaction(function () {
            // Parse only the date part (Y-m-d) for day calculation
            $checkInDate = Carbon::parse($this->new_check_in_date)->startOfDay();
            $checkOutDate = Carbon::parse($this->new_check_out_date)->startOfDay();
            
            // Calculate days correctly using date only (not including time)
            $days = $checkInDate->diffInDays($checkOutDate);
            $days = max(1, $days); // Ensure at least 1 day

            // Set check-in time to 3:00 PM and check-out time to 12:00 PM for the actual datetime fields
            $checkInDateTime = Carbon::parse($this->new_check_in_date)->setTime(15, 0, 0);
            $checkOutDateTime = Carbon::parse($this->new_check_out_date)->setTime(12, 0, 0);

            // Update transaction dates
            $this->transaction->update([
                'start_datetime' => $checkInDateTime->format('Y-m-d H:i:s'),
                'end_datetime' => $checkOutDateTime->format('Y-m-d H:i:s'),
                'is_rebooked' => true,
                'updated_at' => now(),
            ]);

            // Get fresh transaction properties - these will include any room changes made
            $transactionProperties = TransactionProperty::with('property')
                ->where('transaction_id', $this->transaction->id)
                ->get();

            // Update all transaction properties with new days count and recalculated rates
            foreach ($transactionProperties as $property) {
                // Recalculate with new dates
                $rateSummary = $this->roomRateService->getRateSummary(
                    $property->property,
                    $checkInDateTime->format('Y-m-d H:i:s'),
                    $checkOutDateTime->format('Y-m-d H:i:s')
                );
                
                $totalRateForStay = $rateSummary['total_amount'] ?? 0;

                $kids = $property->kids;
                $adults = $property->adults;

                $chargeableGuests = $kids + $adults;
                $allowedGuests = $property->property->ideal_guest;
                $extraGuests = max(0, $chargeableGuests - $allowedGuests);

                $extraChargePerPerson = $property->property->extra_person_charge;

                $baseAmount = $totalRateForStay;
                $extraCharge = $extraGuests * $days * $extraChargePerPerson;
                $totalAmount = $baseAmount + $extraCharge;

                $property->update([
                    'days' => $days,
                    'amount' => $baseAmount,
                    'extra_guest' => $extraGuests,
                    'extra_charge' => $extraCharge,
                    'total_amount' => $totalAmount,
                    'updated_at' => now(),
                ]);
            }

            // Recalculate invoice
            $this->recalculateInvoice();
            $this->tempRoomRates = [];

            session()->flash('success', 'Reservation successfully rebooked for new dates!');
            
            return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction->id]);
        });
    } catch (\Exception $e) {
        Log::error('Rebooking failed: ' . $e->getMessage());
        session()->flash('error', 'Failed to rebook reservation: ' . $e->getMessage());
    }
}

public function recalculateTransactionProperty($transactionPropertyId)
{
    Log::info('Transaction ID ' . $transactionPropertyId);
    $transactionProperty = TransactionProperty::with('property')->find($transactionPropertyId);

    // Exit early if transaction or property is missing
    if (!$transactionProperty || !$transactionProperty->property) {
        return;
    }

    // Calculate days using date part only (not time)
    $startDate = Carbon::parse($this->transaction->start_datetime)->startOfDay();
    $endDate = Carbon::parse($this->transaction->end_datetime)->startOfDay();
    $days = $startDate->diffInDays($endDate);
    $days = max(1, $days); // Ensure at least 1 day

    // USE ROOM RATE SERVICE TO GET TOTAL FOR STAY
    $roomRateService = app(RoomRateService::class);
    $rateSummary = $roomRateService->getRateSummary(
        $transactionProperty->property,
        $this->transaction->start_datetime,
        $this->transaction->end_datetime
    );
    
    $totalRateForStay = $rateSummary['total_amount'] ?? 0;

    // Use already-saved guest counts
    $kids = $transactionProperty->kids;
    $adults = $transactionProperty->adults;
    $nonChargeableGuests = $transactionProperty->non_chargeable_guests;

    // Calculate how many guests exceed the allowed limit
    $chargeableGuests = $kids + $adults;
    $allowedGuests = $transactionProperty->property->ideal_guest;
    $extraGuests = max(0, $chargeableGuests - $allowedGuests);

    // Calculate charges
    $extraChargePerPerson = $transactionProperty->property->extra_person_charge;

    $baseAmount = $totalRateForStay; // Use the dynamic total rate for stay
    $extraCharge = $extraGuests * $days * $extraChargePerPerson;
    $totalAmount = $baseAmount + $extraCharge;

    // Update transaction property amounts
    $transactionProperty->extra_guest = $extraGuests;
    $transactionProperty->extra_charge = $extraCharge;
    $transactionProperty->amount = $baseAmount;
    $transactionProperty->total_amount = $totalAmount;
    $transactionProperty->non_chargeable_guests = $nonChargeableGuests;
    $transactionProperty->days = $days; // Explicitly set days

    $transactionProperty->save();
}

    public function render()
    {
        $new_check_out_date = $this->calculateNewCheckOutDate();

        return view('livewire.admin.reservations.rebook-reservation', [
        'activities' => $this->activities ?? collect(),
        'properties' => $this->properties ?? collect(),
        'services' => $this->services ?? collect(),
        'guestTypes' => \App\Models\GuestType::all(),
        'transaction_properties' => $this->transactionProperties ?? collect(),
        'allItems' => $this->allItems ?? [],
        'new_check_out_date' => $new_check_out_date,
        ]);
    }

public function refreshProperties()
{
    $this->transaction->refresh();
    
    // Reload properties with fresh pivot data from database
    $this->properties = $this->transaction->properties()->withPivot(
        'id', 'adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days'
    )->get();
    
    // Override with temp rates if they exist (for display only)
    foreach ($this->properties as $property) {
        $pivotId = $property->pivot->id;
        if (isset($this->tempRoomRates[$pivotId])) {
            $property->pivot->amount = $this->tempRoomRates[$pivotId]['amount'];
            $property->pivot->extra_charge = $this->tempRoomRates[$pivotId]['extra_charge'];
            $property->pivot->total_amount = $this->tempRoomRates[$pivotId]['total_amount'];
        }
    }
    
    // Reload all items for the invoice table with updated rates
    $this->loadAllInvoiceItems();
}
}