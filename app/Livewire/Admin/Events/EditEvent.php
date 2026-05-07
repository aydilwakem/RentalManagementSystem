<?php

namespace App\Livewire\Admin\Events;

use App\Mail\EventConfirmedMail;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Invoice;
use App\Models\Municipality;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\Activity;
use App\Models\Service;
use App\Models\Setting;
use App\Services\RoomAvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class EditEvent extends Component
{
    public Transaction $event;

    // ----------------------- Types ---------------------------- //
    public $reservation_type_id = 3; // This reservation is for Events
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'

    // ----------------------- Heard From, Status Defaults ---------------------------- //
    public $reservation_source = 'Website';
    public $transaction_status = 'confirmed';

    // ------------------- Address -------------------- //
    public $municipalities = [];
    public $otherCountry;

    // ----------------------- EVENT DETAILS ---------------------------- //

    // ----------------------- Guest ---------------------------- //
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $city_municipality;
    public $country;

    // ----------------------- Halls (transaction_properties)---------------------------- //
    public $halls;
    public $selected_halls = [];
    public $multipleHalls = false;
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = [];
    public $hall_amount;

    // ----------------------- Halls (trn_transactions)---------------------------- //
    public $total_amount; // Total amount for the reservation
    public $pax = 0; // Total number of guests (adults + kids)

    public $start_datetime; //Start Date Time of Event
    public $end_datetime; //End Date Time of Event
    public $total_adults;
    public $total_kids;
    public $actual_start_datetime; // Actual start datetime when the event is ongoing

    // ------------------- INVOICE AND EVENT TYPE -------------------- //
    public $invoice_number;
    public $eventTypes;
    public $event_type_id;
    public $guests;

    // ----------------------- CART ITEMS ---------------------------- //
    public $selectedRooms = [];
    public $selectedActivities = [];
    public $selectedServices = [];

    // ----------------------- MODALS ------------------------ //
    public $roomModal = false, $activityModal = false, $servicesModal = false;

    // ----------------------- ROOMS ------------------------ //
    public $rooms = [];

    // ----------------------- ACTIVITIES ------------------------ //
    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $selectedTimes = [];

    // ----------------------- SERVICES ------------------------ //
    public $services_charges = [];

    public $confirmEditItem = false;

    // Dishes
    public $dishes = '';



    public function boot()
    {
        $this->loadStaticData();
    }

    protected function loadStaticData()
    {
        $this->activities = Activity::availableActivities()->get();
        $this->services_charges = Service::all();
    }


    // ----------------------- DYNAMIC ROOM AVAILABILITY -----------------------------
    public function getAvailableRooms()
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            $this->rooms = collect();
            return;
        }

        $roomService = app(RoomAvailabilityService::class);

        // Use the service directly like in CreateEvent
        $this->rooms = $roomService->getAvailableRooms(
            \Carbon\Carbon::parse($this->start_datetime)->format('Y-m-d'),
            \Carbon\Carbon::parse($this->end_datetime)->format('Y-m-d')
        );
    }
    // ----------------------------- MODALS -----------------------------
    public function openModal(string $type): void
    {
        if ($type === 'room') {
            $this->getAvailableRooms();
            $this->roomModal = true;
        } elseif ($type === 'activity') {
            $this->activityModal = true;
        } elseif ($type === 'services') {
            $this->servicesModal = true;
        }
    }

    // ----------------------------- ROOM CART LOGIC -----------------------------
    public function SelectedRooms($roomId)
    {
        $room = \App\Models\Property::findOrFail($roomId);

        // Check if already in cart
        if ($this->isItemAlreadyInCart('room', $roomId)) {
            return;
        }

        $this->selectedRooms[] = [
            'type' => 'room',
            'room_id' => $room->id,
            'room_name' => $room->name_number,
            'ideal_guest' => $room->ideal_guest,
        ];

        $this->roomModal = false;
    }

    // ----------------------------- REMOVE ITEM FROM CART -----------------------------
    public function RemoveRoom($roomId)
    {
        $this->selectedRooms = array_filter($this->selectedRooms, function($item) use ($roomId) {
            return !($item['type'] === 'room' && $item['room_id'] == $roomId);
        });
        $this->selectedRooms = array_values($this->selectedRooms);
    }

    // ----------------------------- ACTIVITY CART LOGIC -----------------------------
    public function SelectedActivities($activityId)
    {
        $activity = Activity::findOrFail($activityId);

        // Check if already in cart
        if ($this->isItemAlreadyInCart('activity', $activityId)) {
            return;
        }

        $activityItem = [
            'type' => 'activity',
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'quantity' => $this->quantity[$activityId] ?? 1,
        ];

        // Add schedule if needed
        if ($activity->schedule_type !== 'no_schedule') {
            $date = \Carbon\Carbon::parse($this->start_datetime)->format('Y-m-d');
            $time = $this->selectedTimes[$activityId] ?? null;
            if ($time) {
                $activityItem['activity_datetime'] = \Carbon\Carbon::parse("$date $time")->format('Y-m-d H:i:s');
            }
        }

        $this->selectedActivities[] = $activityItem;
        $this->activityModal = false;
    }

    // ----------------------------- REMOVE ITEM FROM CART -----------------------------
    public function RemoveActivity($activityId)
    {
        $this->selectedActivities = array_filter($this->selectedActivities, function($item) use ($activityId) {
            return !($item['type'] === 'activity' && $item['activity_id'] == $activityId);
        });
        $this->selectedActivities = array_values($this->selectedActivities);
    }

    // ----------------------------- SERVICE CART LOGIC -----------------------------
    public function SelectedServices($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        // Check if already in cart
        if ($this->isItemAlreadyInCart('service', $serviceId)) {
            return;
        }

        $this->selectedServices[] = [
            'type' => 'service',
            'service_id' => $service->id,
            'service_name' => $service->name,
            'quantity' => $this->quantity[$serviceId] ?? 1,
            'service_unit' => $service->unit,
        ];

        $this->servicesModal = false;
    }

    // ----------------------------- REMOVE ITEM FROM CART -----------------------------
    public function RemoveService($serviceId)
    {
        $this->selectedServices = array_filter($this->selectedServices, function($item) use ($serviceId) {
            return !($item['type'] === 'service' && $item['service_id'] == $serviceId);
        });
        $this->selectedServices = array_values($this->selectedServices);
    }

    // ----------------------- HELPERS -----------------------------
    protected function isItemAlreadyInCart(string $type, int $itemId): bool
    {
        $cart = [];
        if ($type === 'room') $cart = $this->selectedRooms;
        if ($type === 'activity') $cart = $this->selectedActivities;
        if ($type === 'service') $cart = $this->selectedServices;

        foreach ($cart as $item) {
            if ($item['type'] === $type && $item["{$type}_id"] == $itemId) {
                return true;
            }
        }
        return false;
    }

    // ----------------------- CONFIRMATION MODAL -----------------------------
    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    //To fetch data for display
    public function mount(Transaction $event)
    {
        $this->eventTypes = EventType::all();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();

        // Load existing selected halls
        $this->selected_halls = $event->properties()->whereHas('type', function($q) {
            $q->where('name', 'Event Hall');
        })->pluck('properties.id')->toArray();

        // Load existing rooms, activities, and services
        $this->loadExistingItems($event);

        $this->event_type_id = $event->event_type_id;
        $this->invoice_number = $event->invoice_number;
        $this->total_adults = $event->total_adults;
        $this->total_kids = $event->total_kids;
        $this->pax = $event->pax;

        $this->start_datetime = $event->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $event->end_datetime->format('Y-m-d\TH:i');

        $this->total_amount = $event->total_amount;
        $this->transaction_status = $event->transaction_status;

        $this->first_name = $event->transactionUser->first_name ?? '';
        $this->middle_name = $event->transactionUser->middle_name ?? '';
        $this->last_name = $event->transactionUser->last_name ?? '';
        $this->email = $event->transactionUser->email ?? '';
        $this->contact_number = $event->transactionUser->contact_number ?? '';
        $this->company_name = $event->transactionUser->company_name ?? '';
        $this->city_municipality = $event->transactionUser->city_municipality ?? '';

        // ------------------ Country Handling ------------------ //
        $presetCountries = ['Philippines', 'Other'];
        if (!in_array($event->transactionUser->country, $presetCountries)) {
            // Custom country value
            $this->country = 'Other';
            $this->otherCountry = $event->transactionUser->country;
        } else {
            $this->country = $event->transactionUser->country;
            $this->otherCountry = '';
        }

            // Load existing dishes
        if ($event->dishes && is_array($event->dishes)) {
            $this->dishes = implode(', ', $event->dishes);
        } else {
            $this->dishes = $event->dishes ?? '';
        }

        // ----------------------- Address -------------------- //
        $this->municipalities = Municipality::orderBy('PSGC_MUNC_DESC')->get();

        // Load hall availability
        $this->getAvailableHalls();
    }

    // This allows you to access the method as a property
    protected function loadExistingItems(Transaction $event)
    {
        // Load existing rooms
        $existingRooms = $event->properties()->whereHas('type', function($q) {
            $q->where('name', 'Room');
        })->get();

        foreach ($existingRooms as $room) {
            $this->selectedRooms[] = [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'ideal_guest' => $room->ideal_guest,
            ];
        }

        // Load existing activities
        $existingActivities = $event->activities()->get();
        foreach ($existingActivities as $activity) {
            $this->selectedActivities[] = [
                'type' => 'activity',
                'activity_id' => $activity->id,
                'activity_name' => $activity->name,
                'quantity' => $activity->pivot->quantity,
                'activity_datetime' => $activity->pivot->activity_datetime,
            ];
        }

        // Load existing services
        $existingServices = $event->services()->get();
        foreach ($existingServices as $service) {
            $this->selectedServices[] = [
                'type' => 'service',
                'service_id' => $service->id,
                'service_name' => $service->name,
                'quantity' => $service->pivot->quantity,
                'service_unit' => $service->unit,
            ];
        }
    }

    // --------------------- UPDATED FIELDS METHODS --------------------- //
    public function computeTotalPax()
    {
        $this->pax = (int) $this->total_adults + (int) $this->total_kids;
    }

    public function updatedTotalAdults()
    {
        $this->computeTotalPax();
    }

    public function updatedTotalKids()
    {
        $this->computeTotalPax();
    }

    // This function is triggered when the start datetime is updated
    public function updatedStartDatetime($value)
    {
        $this->getAvailableHalls();
        $this->getAvailableRooms();

        if ($value) {
            $start = \Carbon\Carbon::parse($value);
            if (!$this->end_datetime || $start->greaterThan(\Carbon\Carbon::parse($this->end_datetime))) {
                $this->end_datetime = $start->copy()->addHours(4)->format('Y-m-d\TH:i');
            }
        }
    }

    // This function is triggered when the end datetime is updated
    public function updatedEndDatetime()
    {
        $this->getAvailableHalls();
        $this->getAvailableRooms();
    }


    // ----------------------- UPDATE EVENT -----------------------------
    public function updateEvent()
    {
        //Cannot change status to receipt_verified, reserved, or confirmed if amount paid is zero
        if(
            in_array($this->transaction_status, ['receipt_verified', 'reserved', 'confirmed', 'ongoing', 'done']) &&
            $this->event->invoice &&
            $this->event->invoice->amount_paid == 0
        ){
        $this->addError('transaction_status', 'Cannot change status to "' . str_replace('_', ' ', $this->transaction_status) . '". No payments found.');
        $this->confirmEditItem = false;
            return;
        }

        //If status is changed to done, when transaction has balance,
        //add error message, transaction has balance
        if ($this->transaction_status === 'done' && $this->event->invoice) {
            $balance_due = $this->event->invoice->sub_total - $this->event->invoice->amount_paid;

            if ($balance_due > 0) {
                $this->addError(
                    'transaction_status',
                    'Cannot change status to "Completed". Transaction still has a balance of ₱' . number_format($balance_due, 2)
                );
                $this->confirmEditItem = false;
                return;
            }
        }

    try{
         $this->validate([
                // Transaction User Fields
                'first_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'middle_name' => 'nullable|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'last_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'email' => 'required|email|max:100',
                'contact_number' => 'required|string|max:20|regex:/^[0-9]{11}$/',
                'city_municipality' => 'nullable|string|max:100',
                'company_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'country' => 'required|string|max:100',

                // Transaction Fields
                'event_type_id' => 'required|integer|exists:event_types,id',
                'start_datetime' => 'required|date|before_or_equal:end_datetime',
                'end_datetime' => 'required|date|after_or_equal:start_datetime',
                'total_adults' => 'required|integer|min:5|max:200',
                'total_kids' => 'nullable|integer|min:0|max:50',
                'pax' => 'required|integer|min:5|max:200',
                'total_amount' => 'required|numeric|min:10000|max:5000000.00',
                'reservation_source' => 'required|string|max:100',

                // Dynamic hall validation
                'selected_halls' => 'required|array|min:1',
                'selected_halls.*' => 'exists:properties,id',

                // Dishes
                'dishes' => 'nullable|string|max:1000',


                //Status
                'transaction_status' => 'required|in:pending,confirmed,reserved,receipt_verified,ongoing,done,terminated',
    ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        $this->confirmEditItem = false;
        throw $e;
    }

    $finalCountry = $this->country === 'Other' ? $this->otherCountry : $this->country;

    DB::transaction(function() use ($finalCountry){

        //old transaction status before updating to confirmed or ongoing
        $oldStatus = $this->event->transaction_status;

        //Update transaction details
        $this->event->update([
            'event_type_id' => $this->event_type_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'total_adults' => $this->total_adults,
            'total_kids' => $this->total_kids ?? 0,
            'pax' => $this->pax,
            'total_amount' => $this->total_amount,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            'dishes' => $this->dishes, // Add this

        ]);

        Log::info('Event status updated to confirmed, preparing email');
        //If status is changed to confirmed
        //Prepare data for email body
        if($oldStatus !== 'confirmed' && $this->transaction_status === 'confirmed'){
            $event = $this->event->fresh([
                'invoice.payments',
                'properties',
                'activities',
                'services',
            ]);

            $branding = Setting::first();

            Mail::to($event->transactionUser->email)->send(new EventConfirmedMail($event, $branding));
            Log::info('Email Success');
        }

        //If status is changed to ongoing
        if (
            $oldStatus !== 'ongoing' &&
            $this->transaction_status === 'ongoing' &&
            $this->event->actual_start_datetime === null
        ){
            $this->event->update([
                'actual_start_datetime' => now(),
            ]);
        }

        // Sync all properties (halls + rooms)
        $allProperties = [];

        // Add halls
        foreach ($this->selected_halls as $hallId) {
            $allProperties[$hallId] = [
                'adults' => $this->total_adults,
                'kids' => $this->total_kids ?? 0,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $this->total_amount,
                'total_amount' => $this->total_amount,
                'days' => $this->stayDuration ?? 1,
            ];
        }

        // Add rooms
        foreach ($this->selectedRooms as $room) {
            $allProperties[$room['room_id']] = [
                'adults' => 0,
                'kids' => 0,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => 0,
                'total_amount' => 0,
                'days' => $this->stayDuration ?? 1,
            ];
        }

        // Sync all properties at once
        $this->event->properties()->sync($allProperties);

        // Sync activities
        $activityData = [];
        foreach ($this->selectedActivities as $activity) {
            $activityData[$activity['activity_id']] = [
                'quantity' => $activity['quantity'],
                'amount' => 0,
                'payment_status' => 'unpaid',
                'activity_datetime' => $activity['activity_datetime'] ?? null,
            ];
        }
        $this->event->activities()->sync($activityData);

        // Sync services
        $serviceData = [];
        foreach ($this->selectedServices as $service) {
            $serviceData[$service['service_id']] = [
                'quantity' => $service['quantity'],
                'amount' => 0,
                'days' => $this->stayDuration ?? 1,
                'payment_status' => 'unpaid',
            ];
        }
        $this->event->services()->sync($serviceData);

        if($this->event->invoice){
            $this->event->invoice->update([
                'sub_total' => $this->total_amount,
                'balance_due' => $this->total_amount - $this->event->invoice->amount_paid,
                'due_date' => $this->end_datetime,
            ]);
        }
    });

        $this->confirmEditItem = false;
        session()->flash('success', 'Event updated successfully!');
        return redirect()->route('admin.events');
    }


    // ----------------------------- RETRIEVE HALLS ----------------------------- //
    // This function retrieves available halls based on the selected date range
    public function getAvailableHalls()
    {
       if (!$this->start_datetime || !$this->end_datetime) {
            return;
        }

        // Parse the start and end datetime to Carbon instances
        $startDate = \Carbon\Carbon::parse($this->start_datetime)->setSeconds(0);
        $endDate = \Carbon\Carbon::parse($this->end_datetime)->setSeconds(0);

        // Step 1: Get all available event halls (unfiltered)
        $allHalls = Property::ofType('Event Hall')
            ->where('property_status', 'available')
            ->get();

        // Step 2: Load only transactions that truly overlap
        $allHalls->load(['transactions' => function ($query) use ($startDate, $endDate) {
        $query->where(function ($q) use ($startDate, $endDate) {
            $q->where('end_datetime', '>', $startDate)
              ->where('start_datetime', '<', $endDate);
        })
        ->whereNotIn('transaction_status', ['expired', 'terminated', 'cancelled', 'done']); 
        // Only consider active/confirmed transactions, else, 
        //hall is still available in expired transactions
        }]);

            //Step 3: Filter booked halls, but show available halls with pencil booking 
            $this->halls = $allHalls->map(function ($hall) {
                $statuses = $hall->transactions->pluck('transaction_status')->unique();

                //Count the pending statuses
                $pencilCount = $hall->transactions
                ->where('transaction_status', 'pending')
                ->count();


                if ($statuses->contains(fn($s) => !in_array($s, ['pending', 'expired', 'terminated', 'cancelled']))) { 
                $hall->availability_status = 'Booked'; //Show halls that are not pending or expired
            } elseif ($pencilCount > 0) {
                $hall->availability_status = 'Available - ' . $pencilCount . ' Pencil Booked'; //Hall is still available that are only tagged as pending
            } else {
                $hall->availability_status = 'Available';
            }

            return $hall;
        });
    }

    // This allows you to access the method as a property
    public function getStayDurationProperty()
    {
        if ($this->start_datetime && $this->end_datetime) {
            $in = Carbon::parse($this->start_datetime);
            $out = Carbon::parse($this->end_datetime);
            return $in->diffInDays($out);
        }
        return 0;
    }

    // Accepts input like "50,000";
    public function updatedTotalAmount($value)
    {
        $this->total_amount = str_replace(',', '', $value);
    }

    public function render()
    {
        return view('livewire.admin.events.edit-event', [
            'halls' => $this->halls
        ]);
    }
}
