<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Invoice;
use App\Models\Municipality;
use App\Models\Property;
use App\Models\Region;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Activity;
use App\Models\Service;
use App\Services\RoomAvailabilityService;


class CreateEvent extends Component
{
    //Public declaration for fields
    // ----------------------- Types ---------------------------- //
    public $reservation_type_id = 3; // This reservation is for Events
    public $transaction_number;
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'

    // ----------------------- Heard From, Status Defaults ---------------------------- //
    public $reservation_source = 'Website';
    public $transaction_status = '';

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
    public $otherCountry = '';
    public $dishes = '';


    // ----------------------- Halls (transaction_properties)---------------------------- //
    public $allHalls = [];
    public $halls;
    public $selected_hall;
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = [];
    public $hall_amount;
    // public $hall_total_amount;

    // ----------------------- Halls (trn_transactions)---------------------------- //
    public $total_amount; // Total amount for the reservation
    public $pax = 0; // Total number of guests (adults + kids)

    public $start_datetime; //Start Date Time of Event
    public $end_datetime; //End Date Time of Event
    public $total_adults;
    public $total_kids;
    //public $heard_from;

    // ------------------- INVOICE AND EVENT TYPE -------------------- //
    public $invoice_number;
    public $eventTypes;
    public $event_type_id;

    // ------------------- Address -------------------- //
    public $municipalities = [];


    // ------------------- Modal -------------------- //
    public $confirmCreateItem = false;


    // ----------------------- CART ITEMS ---------------------------- //
    public $selectedRooms = [];
    public $selectedActivities = [];
    public $selectedServices = [];

    // ----------------------- MODALS ------------------------ //
    public $roomModal = false, $activityModal = false, $servicesModal = false;

    // ----------------------- ROOMS ------------------------ //
    public $rooms = [];
    // public $adultsroom = [];
    // public $kidsroom = [];
    // public $roomTotalAdults = 1;
    // public $roomTotalKids = 0;

    // ----------------------- ACTIVITIES ------------------------ //
    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $selectedTimes = [];

    // ----------------------- SERVICES ------------------------ //
    public $services_charges = [];


    public function boot()
    {
        $this->loadStaticData();
    }

    protected function loadStaticData()
    {
        $this->activities = Activity::availableActivities()->get();
        $this->services_charges = Service::all();
    }

    // Fetch available rooms based on selected date range
    public function getAvailableRooms()
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            $this->rooms = collect();
            return;
        }

        $roomService = app(RoomAvailabilityService::class);

        // Use the service directly like your working code
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
            'ideal_guest' => $room->ideal_guest, // Just store ideal guest count
        ];

        $this->roomModal = false;
    }

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



    public function mount()
    {
        $this->eventTypes = EventType::all();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();

        //Set default values
        $now = Carbon::now('Asia/Manila');

        // Current date, 8AM - 12PM (minimum 4hrs)
        $this->start_datetime = $now->copy()->setTime(8, 0)->format('Y-m-d H:i');
        $this->end_datetime = $now->copy()->setTime(12, 0)->format('Y-m-d H:i');

        $this->getAvailableHalls();

        //Addresses
        $this->municipalities = Municipality::orderBy('PSGC_MUNC_DESC')->get();
    }

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    // ----------------------- SAVE EVENT -----------------------------
    public function saveEvent()
    {
        try {
            $this->validate([
                // Transaction User Fields
                'first_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'middle_name' => 'nullable|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'last_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'email' => 'required|email|max:100',
                'contact_number' => 'required|string|max:11|regex:/^[0-9]{11}$/',
                'city_municipality' => 'nullable|string|max:100',
                'company_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-\/]+$/',
                'country' => 'required|string|max:100',
                'dishes' => 'nullable|string|max:1000',


                // Transaction Fields
                'event_type_id' => 'required|integer|exists:event_types,id',
                'start_datetime' => 'required|date|after_or_equal:today|before_or_equal:end_datetime',
                'end_datetime' => 'required|date|after_or_equal:start_datetime',
                'total_adults' => 'required|integer|min:5|max:200',
                'total_kids' => 'nullable|integer|min:0|max:50',
                'pax' => 'required|integer|min:5|max:200',
                'total_amount' => 'required|numeric|min:10000|max:5000000.00',
                'reservation_source' => 'required|string|max:100',

                // Dynamic hall validation
                'selected_halls' => 'required|array|min:1',
                'selected_halls.*' => 'exists:properties,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        $finalCountry = $this->country === 'Other' ? $this->otherCountry : $this->country;

        DB::transaction(function () use ($finalCountry) {
            // Step 1: Create transaction user
            $transactionUser = TransactionUser::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'city_municipality' => $this->city_municipality,
                'company_name' => $this->company_name,
                'country' => $finalCountry, // Use the computed final country
                'trn_user_type' => $this->trn_user_type,
            ]);

            $depositPercentage = DB::table('st_settings')->value('deposit_percentage');

            // Step 2: Create transaction (ONLY ONCE)
            $transaction = Transaction::create([
                'transaction_number' => 'EVT-' . strtoupper(Str::random(8)),
                'reservation_type_id' => $this->reservation_type_id,
                'created_by' => $transactionUser->id,
                'event_type_id' => $this->event_type_id,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'total_adults' => $this->total_adults,
                'total_kids' => $this->total_kids,
                'pax' => $this->pax,
                'total_amount' => $this->total_amount,
                'deposit_amount' => $this->total_amount * ($depositPercentage / 100),
                'reservation_source' => $this->reservation_source,
                'transaction_status' => $this->transaction_status ?: 'pending', // Default to 'pending' if empty
                'dishes' => $this->dishes,
            ]);

            // Step 3: Generate invoice number
            $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

            // Step 4: Create invoice
            $invoice = Invoice::create([
                'transaction_id' => $transaction->id,
                'invoice_number' => $invoiceNumber,
                'invoice_type' => 'event_hall',
                'sub_total' => $this->total_amount,
                'deposit_paid' => 0,
                'amount_paid' => 0,
                'balance_due' => $this->total_amount,
                'due_date' => $this->end_datetime,
                'invoice_status' => 'pending',
            ]);

            // Combine all property attachments (halls + rooms)
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

            // Add rooms if any
            if (!empty($this->selectedRooms)) {
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
            }

            // Attach properties to transaction
            if (!empty($allProperties)) {
                $transaction->properties()->attach($allProperties);
            }

            // Attach selected activities
            if (!empty($this->selectedActivities)) {
                foreach ($this->selectedActivities as $activity) {
                    $transaction->activities()->attach($activity['activity_id'], [
                        'quantity' => $activity['quantity'],
                        'amount' => 0, // No amount since it's included in agreed cost
                        'payment_status' => 'unpaid',
                        'activity_datetime' => $activity['activity_datetime'] ?? null,
                    ]);
                }
            }

            // Attach selected services
            if (!empty($this->selectedServices)) {
                foreach ($this->selectedServices as $service) {
                    $transaction->services()->attach($service['service_id'], [
                        'quantity' => $service['quantity'],
                        'amount' => 0, // No amount since it's included in agreed cost
                        'days' => $this->stayDuration ?? 1,
                        'payment_status' => 'unpaid',
                    ]);
                }
            }
        });

        session()->flash('success', 'Event reservation successfully saved.');
        return redirect()->route('admin.events');
    }

    public function render()
    {
        return view('livewire.admin.events.create-event', [
            'halls' => $this->halls
        ]);
    }

    // ----------------------- DYNAMIC HALL AVAILABILITY -----------------------------
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
                $q->where('end_datetime', '>', $startDate)   // existing ends after new starts
                    ->where('start_datetime', '<', $endDate); // existing starts before new ends
            });
        }]);

        // Step 3: Flag each hall as booked if it has overlapping transactions
        $this->halls = $allHalls->map(function ($hall) {
            $hall->isBooked = $hall->transactions->isNotEmpty();
            return $hall;
        });
    }

    // To handle multiple hall selection
    public $multipleHalls = false;
    public $selected_halls = [];


    public function updatedStartDatetime($value)
    {
        $this->getAvailableHalls();
        $this->getAvailableRooms();
        if ($value) {
            $start = \Carbon\Carbon::parse($value);
            $this->end_datetime = $start->copy()->addHours(4)->format('Y-m-d\TH:i');
        }
    }

    // This function is triggered when the end datetime is updated
    public function updatedEndDatetime()
    {
        $this->getAvailableHalls();
        $this->getAvailableRooms();
    }



    // This allows you to access the method as a property
    //Calculates day between start date and end date
    public function getStayDurationProperty()
    {
        if ($this->start_datetime && $this->end_datetime) {
            $in = Carbon::parse($this->start_datetime);
            $out = Carbon::parse($this->end_datetime);
            return $in->diffInDays($out);
        }
        return 0;
    }


    /**
     * Fetch available halls for the selected check-in and check-out dates.
     *
     * Filters out halls already booked during the specified range by checking
     * overlapping transactions. Only available halls of type 'Event Hall' are returned.
     *
     * @return void
     */




    //  Method that computes the total pax based on inputed adults + kids
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

    // Accepts input like "50,000";
    // Removes the comma and space for integer values
    public function updatedTotalAmount($value)
    {
        $this->total_amount = str_replace(',', '', $value);
    }
}
