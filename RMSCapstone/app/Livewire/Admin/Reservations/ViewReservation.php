<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\GuestType;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\GuestPet;
use App\Models\TransactionProperty;
use App\Models\Activity;
use App\Models\Service;
use App\Models\GuestDetail;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfficialReceiptMail;
use App\Mail\RequestRemainingBalanceMail;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PayMongoService;
use App\Services\ServiceBag;
use App\Services\TransactionLoader;
use App\Services\ActivityCartService;
use App\Services\RoomCartService;
use App\Services\ActivityTransactionService;
use App\Services\ServiceTransactionService;
use App\Services\PropertyTransactionService;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\PaymentService;
use App\Services\CartService;
use App\Services\GuestDetailService;




#[Layout('layouts.app')]
class ViewReservation extends Component
{

    // ---------------- RELATIONSHIPS ------------------ //

    public $transaction;
    public $transactionUser;
    public $invoice;
    public $guestDetails;
    public $activities;
    public $properties;
    public $payments;
    public $services;
    public $guestTypes;
    public $guestPets;
    public $transactionProperties;


    // ---------------- COMPUTATIONS ------------------ //
    public $totalAddons;
    public $totalRooms;
    public $total_pax;

    // --------- PAYMENT RELATED PROPERTIES ----------- //
    public $invoice_id;
    public $amount_paid;
    public $mode_of_payment;
    public $payment_type;
    public $payment_date;
    public $payment_status;
    public $notes;
    public $currency;
    public $verified_at;

    // ---------------- MODALS ------------------ //
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $createPaymentModal = false;
    public $addActivityModal = false;
    public $expandedActivity = null;

    // ---------------- BRANDING ------------------ //
    public string $companyName = 'Company'; //Default
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    // ---------------- ACTIVITIES ------------------ //
    public $availableActivities;
    public $cart = []; // Store newly added activities
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];

    public $paymentStatus = [];

    // -------------- SERVICES ------------------- // 



    public $availableServices;
    // ---------------- INVOICE ------------------ //
    public $sub_total;
    public $balance_due;
    public $convenienceFeeTotal;

    // ---------------- RECEIPT ------------------ //
    public $receipt;
    public $receiptNumber;

    public $activeModal;

    // ---------------- EDITING ------------------ //

    public $editingActivityId;
    public $editingServiceId;
    public $editingRoomId;
    public $editingPetId;
    public $showEditActivityModal = false;
    public $showEditGuestModal = false;
    public $showEditRoomModal = false;
    public $showEditServiceModal = false;
    public $showEditPetModal = false;
    public $serviceQuantity;
    public $activityQuantity;
    public $roomTotalAdults;
    public $roomTotalKids;
    public $editingBreed;




    // ---------------- GUEST EDITING FIELDS ------------------ //
    public $editingGuestId, $editingFirstName, $editingMiddleName, $editingLastName, $editingSuffix, $editingGender, $editingBirthDate, $editingResidency, $editingCountryOfOrigin, $editingGuestTypeId, $editingTransactionPropertyId;
    public $filteredGuestTypes = [];

    public $guest = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'gender' => null,
        'birthdate' => null,
        'residency' => null,
        'country_of_origin' => null,
        'transaction_property_id' => null,
        'guest_type_id' => null,
    ];

    public $guestInfo = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'gender' => null,
        'birthdate' => null,
        'residency' => null,
        'country_of_origin' => null,
        'transaction_property_id' => null,
        'guest_type_id' => null,
    ];

    public $isFull = false;
    public $breed;



    protected ServiceBag $service;
    protected TransactionLoader $loader;
    protected ActivityCartService $activityCartService;
    protected RoomCartService $roomCartService;
    protected ActivityTransactionService $activityTransactionService;
    protected ServiceTransactionService $serviceTransactionService;
    protected PropertyTransactionService $propertyTransactionService;
    protected InvoiceService $invoiceService;
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected NotificationService $notificationService;
    protected PaymongoService $payMongoService;
    protected ReceiptService $receiptService;
    protected PaymentService $paymentService;
    protected GuestDetailService $guestDetailService;
    protected CartService $cartService;

    public $allItems = [];


    public function render()
    {
        $this->activities = $this->transaction->activities()->withPivot('id', 'quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('id', 'adults', 'kids', 'non_chargeable_guests', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days', 'room_rate_id')->get();
        $this->services = $this->transaction->services()->withPivot('id', 'quantity', 'days', 'amount', 'service_datetime', 'status')->get();


        return view('livewire.admin.reservations.view-reservation', [
            'activities' => $this->activities,  // Pass activities to the view properly
            'properties' => $this->properties,  // Pass activities to the view properly
            'services' => $this->services,
            'transaction_properties' => $this->transactionProperties,
        ]);
    }

    public function boot(ServiceBag $services)
    {
        $this->loader = $services->loader;
        $this->activityCartService = $services->activityCartService;
        $this->roomCartService = $services->roomCartService;
        $this->activityTransactionService = $services->activityTransactionService;
        $this->serviceTransactionService = $services->serviceTransactionService;
        $this->propertyTransactionService = $services->propertyTransactionService;
        $this->invoiceService = $services->invoiceService;
        $this->emailService = $services->emailService;
        $this->brandingService = $services->brandingService;
        $this->payMongoService = $services->payMongoService;
        $this->notificationService = $services->notificationService;
        $this->receiptService = $services->receiptService;
        $this->paymentService = $services->paymentService;
        $this->cartService = $services->cartService;
        $this->guestDetailService = $services->guestDetailService;
    }




    public function mount(Transaction $transaction)
    {
        // Loads all transaction-related relationships
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
        $this->totalRooms = $data['totalRooms'];
        $this->totalAddons = $data['totalAddons'];
        $this->totalAddons = $data['totalAddons'];

        $this->transactionProperties = TransactionProperty::with('property')
            ->where('transaction_id', $this->transaction->id)
            ->get();

        // Sets default dates for payment
        $now = Carbon::now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');

        $this->getIsFullProperty();

        // Retrieves all activities (Code Suggestion: Return a model accessor for available activities)
        $this->availableActivities = Activity::all();
        $this->availableServices = Service::all();
        $this->guestTypes = GuestType::all();

        $this->loadAllInvoiceItems();
    }

    public function loadAllInvoiceItems()
    {
        $items = [];

        // Display also the extra guest
        foreach ($this->transaction->properties as $property) {
            $items[] = [
                'type' => 'property',
                'name' => 'Room – ' . $property->name_number,
                'quantity' => 1,
                'days' => $property->pivot->days,
                'extra_guest' => $property->pivot->extra_guest,
                'extra_charge' => $property->extra_person_charge,
                'extra_charge_total' => $property->extra_person_charge * $property->pivot->days * $property->pivot->extra_guest,
                'amount' => $property->amount,
                'total' => $property->pivot->amount,
                'created_at' => $property->pivot->created_at,
                'payment_status' => $property->pivot->payment_status,
                'id' => $property->id,
                'pivot_id' => $property->pivot->id,
            ];
        }

        foreach ($this->transaction->activities as $activity) {
            $items[] = [
                'type' => 'activity',
                'name' => $activity->name,
                'quantity' => $activity->pivot->quantity,
                'days' => null,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $activity->amount,
                'total' => $activity->amount * $activity->pivot->quantity,
                'created_at' => $activity->pivot->created_at,
                'payment_status' => $activity->pivot->payment_status,
                'id' => $activity->id,
                'pivot_id' => $activity->pivot->id,
            ];
        }

        foreach ($this->transaction->services as $service) {
            $items[] = [
                'type' => 'service',
                'name' => $service->name,
                'quantity' => $service->pivot->quantity,
                'days' => $service->pivot->days ?? null,
                'extra_guest' => 0,
                'extra_charge' => 0,
                'amount' => $service->amount,
                'total' => $service->amount * $service->pivot->quantity,
                'created_at' => $service->pivot->created_at,
                'payment_status' => $service->pivot->payment_status,
                'unit' => $service->unit,
                'id' => $service->id,
                'pivot_id' => $service->pivot->id,
            ];
        }

        // Sort by created_at
        usort($items, function ($a, $b) {
            return $a['created_at']->timestamp <=> $b['created_at']->timestamp;
        });

        $this->allItems = $items;
    }





    /**
     * ----------------------------- ITEM CART MANAGEMENT ------------------------------
     *
     * Manages the cart functionality for different item types (e.g., services, activities).
     *
     * Core responsibilities:
     * - `addItemToCart`: Adds an item (e.g., activity or service) to the cart using CartService.
     * - `removeItemFromCart`: Removes a specific item from the cart based on its type and ID.
     * - `incrementItemQuantity`: Increases the quantity of a given item in the cart.
     * - `decrementItemQuantity`: Decreases the quantity (with a minimum limit of 1).
     *
     * State Management:
     * - Cart contents are stored in `$this->cart` and synced with the UI.
     * - Item quantities are managed through `$this->quantity[itemId]`.
     * - Status and payment status are also handled and passed to the CartService.
     *
     * ---------------------------------------------------------------------------------
     */
    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
    }

    public function addItemToCart($type, $itemId)
    {
        $newCart = $this->cartService->addItem(
            $type,
            $itemId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->paymentStatus
        );

        if ($newCart === false) {
            $this->addError('cart', 'This item is already in the cart.');
            return;
        }

        $this->cart = $newCart;
    }

    public function removeItemFromCart($type, $itemId)
    {
        $this->cart = $this->cartService->removeItem($type, $itemId, $this->cart);
    }

    public function incrementItemQuantity($type, $itemId)
    {
        if (!isset($this->quantity[$itemId])) {
            $this->quantity[$itemId] = 1;
        }

        $newQuantity = $this->quantity[$itemId] + 1;
        $this->quantity[$itemId] = $newQuantity;

        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $newQuantity);
    }

    public function decrementItemQuantity($type, $itemId)
    {
        if (!isset($this->quantity[$itemId])) {
            $this->quantity[$itemId] = 1;
        }

        $newQuantity = max(1, $this->quantity[$itemId] - 1);
        $this->quantity[$itemId] = $newQuantity;

        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $newQuantity);
    }


    /**
     * ------------------------- PROPERTY MANAGEMENT ---------------------------
     *
     * Handles database operations for rooms tied to a reservation.
     *
     * -------------------------------------------------------------------------------------
     */

    public function editRoom($pivotId)
    {
        Log::info('Edit Room modal called.');

        $pivot = DB::table('transaction_properties')->where('id', $pivotId)->first();

        if ($pivot) {
            $this->editingRoomId = $pivotId;
            $this->roomTotalAdults = $pivot->adults;
            $this->roomTotalKids = $pivot->kids;
            $this->showEditRoomModal = true;
        }
    }

    public function updateRoom()
    {
        Log::info('Update Room modal called.');

        $this->validate([
            'roomTotalAdults' => 'required|integer|min:1',
            'roomTotalKids' => 'required|integer|min:1',
        ]);

        try {

            $this->propertyTransactionService->updateRoomQuantity(
                $this->editingRoomId,
                $this->roomTotalAdults,
                $this->roomTotalKids,
                $this->transaction
            );

            // Recalculate all amounts in invoice
            $this->recalculateTransactionProperty($this->editingRoomId);
            $this->recalculateInvoice();
            $this->loadAllInvoiceItems();

            // Closes the modal
            $this->showEditRoomModal = false;
            $this->dispatch('room-updated');
        } catch (\Exception $e) {
            Log::error('Room update failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function incrementAdults()
    {
        $this->roomTotalAdults++;
    }

    public function decrementAdults()
    {
        if ($this->roomTotalAdults > 0) {
            $this->roomTotalAdults--;
        }
    }

    public function incrementKids()
    {
        $this->roomTotalKids++;
    }

    public function decrementKids()
    {
        if ($this->roomTotalKids > 0) {
            $this->roomTotalKids--;
        }
    }



    /**
     * ------------------------- ACTIVITY TRANSACTION MANAGEMENT ---------------------------
     *
     * Handles database operations for activity transactions tied to a reservation.
     * -------------------------------------------------------------------------------------
     */
    public function saveActivity()
    {
        $this->resetErrorBag();


        // Basic validation
        if (empty($this->cart)) {
            $this->addError('cart', 'Please select at least one activity.');
            return;
        }

        $this->activityTransactionService->saveActivities($this->cart, $this->transaction, $this->invoice);

        $this->recalculateInvoice();
        $this->loadAllInvoiceItems();
        $this->quantity = [];
        $this->cart = [];
        $this->activeModal = false;
    }

    public function deleteActivity($pivotId)
    {
        $this->activityTransactionService->deleteActivity($pivotId, $this->transaction, $this->invoice);
        $this->recalculateInvoice();
        $this->loadAllInvoiceItems();
        session()->flash('message', 'Activity deleted successfully.');
    }

    public function updateActivity()
    {
        $this->validate([
            'activityQuantity' => 'required|integer|min:1',
        ]);

        // Passes the value from editActivity() to update the quantity
        try {

            $this->activityTransactionService->updateActivityQuantity(
                $this->editingActivityId,
                $this->activityQuantity,
                $this->transaction
            );

            // Recalculate all amounts in invoice
            $this->recalculateInvoice();
            $this->loadAllInvoiceItems();

            // Closes the modal
            $this->showEditActivityModal = false;
            $this->dispatch('activity-updated');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function editActivity($pivotId)
    {

        Log::info("Edit Activity method called.");

        $pivot = DB::table('transaction_activities')->where('id', $pivotId)->first();

        if ($pivot) {
            $this->editingActivityId = $pivotId;
            $this->activityQuantity = $pivot->quantity;
            $this->showEditActivityModal = true;
        }
    }


    /**
     * ------------------------- GUEST MANAGEMENT ---------------------------
     *
     * Handles database operations for guests tied to a reservation.
     *
     * -------------------------------------------------------------------------------------
     */

    public function saveGuest()
    {
        Log::info('Save Guest method called.');

        $this->resetErrorBag();

        $this->validate([
            'guest.first_name' => 'required|string|max:255',
            'guest.last_name' => 'required|string|max:255',
            'guest.birthdate' => 'required|date|before:today',
            'guest.gender' => 'nullable|in:male,female',
            'guest.transaction_property_id' => 'required|exists:transaction_properties,id',
            'guest.guest_type_id' => 'required|exists:trn_guest_type,id',
            'guest.middle_name' => 'nullable|string|max:255',
            'guest.suffix' => 'nullable|string|max:10',
            'guest.residency' => 'nullable|string|max:255',
            'guest.country_of_origin' => 'nullable|string|max:255',
        ]);

        $transactionPropertyId = $this->guest['transaction_property_id'];

        $data = [
            'transaction_id' => $this->transaction->id,
            'first_name' => $this->guest['first_name'],
            'middle_name' => $this->guest['middle_name'] ?? null,
            'last_name' => $this->guest['last_name'],
            'suffix' => $this->guest['suffix'] ?? null,
            'gender' => $this->guest['gender'] ?? null,
            'birthdate' => $this->guest['birthdate'] ?? null,
            'residency' => $this->guest['residency'] ?? null,
            'country_of_origin' => $this->guest['country_of_origin'] ?? null,
            'guest_type_id' => !empty($this->guest['guest_type_id']) ? $this->guest['guest_type_id'] : null,
            'transaction_property_id' => $transactionPropertyId,
        ];

        $this->guestDetailService->saveGuest($data);

        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        // Reset guest input fields
        $this->reset('guest');

        $this->activeModal = false;
    }

    public function updateGuest()
    {
        $this->validate([
            'editingFirstName' => 'required|string|max:255',
            'editingMiddleName' => 'nullable|string|max:255',
            'editingLastName' => 'required|string|max:255',
            'editingSuffix' => 'nullable|string|max:255',
            'editingGender' => 'required|in:male,female,other',
            'editingBirthDate' => 'required|date|before:today',
            'editingResidency' => 'required|in:local,foreigner',
            'editingCountryOfOrigin' => 'nullable|string|max:255',
            'editingGuestTypeId' => 'required|exists:trn_guest_type,id',
            'editingTransactionPropertyId' => 'required|exists:transaction_properties,id',
        ]);


        DB::table('trn_guest_details')
            ->where('id', $this->editingGuestId)
            ->update([
                'first_name' => $this->editingFirstName,
                'middle_name' => $this->editingMiddleName,
                'last_name' => $this->editingLastName,
                'suffix' => $this->editingSuffix,
                'gender' => $this->editingGender,
                'birthdate' => $this->editingBirthDate,
                'residency' => $this->editingResidency,
                'country_of_origin' => $this->editingCountryOfOrigin,
                'guest_type_id' => $this->editingGuestTypeId,
                'transaction_property_id' => $this->editingTransactionPropertyId,
                'updated_at' => now(),
            ]);

        // $this->recalculateTransactionProperty($this->editingTransactionPropertyId);

        $this->reset([
            'editingGuestId',
            'editingFirstName',
            'editingMiddleName',
            'editingLastName',
            'editingSuffix',
            'editingGender',
            'editingBirthDate',
            'editingResidency',
            'editingCountryOfOrigin',
            'editingGuestTypeId',
            'editingTransactionPropertyId',
            'showEditGuestModal',
        ]);

        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        session()->flash('message', 'Guest updated successfully.');
    }

    public function deleteGuest($guestId)
    {
        $guestDetail = GuestDetail::findOrFail($guestId);
        $transactionProperty = $guestDetail->transactionProperty()->first();

        $this->guestDetailService->deleteGuest($guestId, $this->transaction);
        $this->recalculateTransactionProperty($transactionProperty->id);
        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        session()->flash('message', 'Guest deleted successfully.');
    }


    public function editGuest($guestId)
    {
        Log::info("Edit Guest method called.");

        // Use Eloquent instead of DB::table
        $guest = GuestDetail::find($guestId);

        if ($guest) {
            $this->editingGuestId = $guest->id;
            $this->editingTransactionPropertyId = $guest->transaction_property_id;
            $this->editingGuestTypeId = $guest->guest_type_id;
            $this->editingFirstName = $guest->first_name;
            $this->editingMiddleName = $guest->middle_name;
            $this->editingLastName = $guest->last_name;
            $this->editingSuffix = $guest->suffix;
            $this->editingGender = $guest->gender;
            $this->editingBirthDate = $guest->birthdate?->format('Y-m-d');
            $this->editingResidency = $guest->residency;
            $this->editingCountryOfOrigin = $guest->country_of_origin;

            $this->getGuestAge();

            $this->showEditGuestModal = true;
        } else {
            Log::warning("Guest not found for ID: $guestId");
        }
    }

    public function updatedEditingBirthdate()
    {
        $this->getGuestAge();
        $this->filterGuestTypesByAge();
    }

    public function updatedGuestBirthDate()
    {
        $this->getGuestAge();
        $this->filterGuestTypesByAge();
    }


    /**
     * ------------------------- SERVICES MANAGEMENT ---------------------------
     *
     * Handles database operations for services tied to a reservation.
     *
     * -------------------------------------------------------------------------------------
     */

    public function saveService()
    {
        Log::info('Save Service method called.');

        $this->resetErrorBag();

        $this->serviceTransactionService->saveServices($this->cart, $this->transaction, $this->invoice);
        $this->recalculateInvoice();
        $this->loadAllInvoiceItems();
        $this->quantity = [];
        $this->cart = [];
        $this->activeModal = false;
    }

    public function deleteService($pivotId)
    {

        Log::info('Delete Service method called.');

        $this->serviceTransactionService->deleteService($pivotId, $this->transaction, $this->invoice);
        $this->recalculateInvoice();
        $this->loadAllInvoiceItems();
    }


    public function editService($pivotId)
    {

        Log::info("Edit Service method called.");

        $pivot = DB::table('transaction_services')->where('id', $pivotId)->first();

        if ($pivot) {
            $this->editingServiceId = $pivotId;
            $this->serviceQuantity = $pivot->quantity;
            $this->showEditServiceModal = true;
        }
    }

    public function updateService()
    {
        $this->validate([
            'serviceQuantity' => 'required|integer|min:1',
        ]);

        // Passes the value from editService() to update the quantity
        try {

            $this->serviceTransactionService->updateServiceQuantity(
                $this->editingServiceId,
                $this->serviceQuantity,
                $this->transaction
            );

            // Recalculate all amounts in invoice
            $this->recalculateInvoice();
            $this->loadAllInvoiceItems();

            // Closes the modal
            $this->showEditServiceModal = false;
            $this->dispatch('service-updated');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }


    /**
     * ------------------------- GUEST MANAGEMENT ---------------------------
     *
     * Handles database operations for rooms tied to a reservation.
     *
     * -------------------------------------------------------------------------------------
     */

    public function savePet()
    {
        $this->validate([
            'breed' => 'required|string|max:255',
        ]);

        GuestPet::create([
            'transaction_id' => $this->transaction->id,
            'breed' => $this->breed,
        ]);

        session()->flash('message', 'Pet added successfully.');
        $this->reset(['breed']);
        $this->activeModal = false;
        $this->loadGuestPets();
    }

    public function deletePet($petId)
    {
        $pet = GuestPet::find($petId);

        if ($pet) {
            $pet->delete();
            session()->flash('message', 'Pet deleted successfully.');
            $this->loadGuestPets();
        } else {
            session()->flash('error', 'Pet not found.');
        }
    }

    public function editPet($petId)
    {
        $pet = GuestPet::find($petId);

        if ($pet) {
            $this->editingPetId = $petId;
            $this->editingBreed = $pet->breed;
            $this->showEditPetModal = true;
        }
    }


    public function updatePet()
    {
        $this->validate([
            'editingBreed' => 'required|string|max:255',
        ]);

        $pet = GuestPet::find($this->editingPetId);

        if ($pet) {
            $pet->breed = $this->editingBreed;
            $pet->save();


            session()->flash('message', 'Pet breed updated successfully.');
            $this->reset(['editingPetId', 'editingBreed']);
            $this->showEditPetModal = false;
            $this->loadGuestPets();
        } else {
            session()->flash('error', 'Pet not found.');
        }
    }


    public function loadGuestPets()
    {
        $this->guestPets = GuestPet::where('transaction_id', $this->transaction->id)->get();
    }


    /**
     * --------------------------------- INVOICE RECALCULATION ---------------------------------
     *
     * Recalculates the invoice totals and updates the component's state accordingly.
     *
     * This method performs the following actions:
     * - Updates the invoice grand total based on the latest cart or charges.
     * - Recomputes the balance due after payments or adjustments.
     * - Updates the invoice status (e.g., Paid, Unpaid, Partial) based on the balance.
     * - Refreshes the Livewire component's state by pulling fresh values from the database.
     *
     * Ensures that both the backend (database) and frontend (Livewire view) reflect
     * the most current invoice data after any changes (e.g., adding/removing items).
     * ------------------------------------------------------------------------------------------
     */
    public function recalculateInvoice()
    {
        $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
        $this->invoiceService->updateBalanceDue($this->invoice);
        $this->invoiceService->updateStatus($this->invoice);

        $this->refreshInvoice();
    }

    /**
     * Reloads the invoice and updates related UI-bound properties.
     */
    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }

    public function recalculateTransactionProperty($transactionPropertyId)
    {

        Log::info('Transaction ID ' . $transactionPropertyId);
        $transactionProperty = TransactionProperty::with('property')->find($transactionPropertyId);

        // Exit early if transaction or property is missing
        if (!$transactionProperty || !$transactionProperty->property) {
            return;
        }

        // Use already-saved guest counts
        $kids = $transactionProperty->kids;
        $adults = $transactionProperty->adults;
        $nonChargeableGuests = $transactionProperty->non_chargeable_guests;

        // Calculate how many guests exceed the allowed limit
        $chargeableGuests = $kids + $adults;
        $allowedGuests = $transactionProperty->property->ideal_guest;
        $extraGuests = max(0, $chargeableGuests - $allowedGuests);

        // Calculate charges
        $days = $transactionProperty->days ?? 1;
        $ratePerDay = $transactionProperty->property->amount;
        $extraChargePerPerson = $transactionProperty->property->extra_person_charge;

        $baseAmount = $days * $ratePerDay;
        $extraCharge = $extraGuests * $days * $extraChargePerPerson;
        $totalAmount = $baseAmount + $extraCharge;

        // Update transaction property amounts
        $transactionProperty->extra_guest = $extraGuests;
        $transactionProperty->extra_charge = $extraCharge;
        $transactionProperty->amount = $baseAmount;
        $transactionProperty->total_amount = $totalAmount;
        $transactionProperty->non_chargeable_guests = $nonChargeableGuests;

        $transactionProperty->save();
    }





    // ------------------------ COMPUTATIONS -------------------------- //

    // Computes rooms total
    public function computeRoomsTotal(): float
    {
        return $this->sumTransactionItems('properties', fn($item) => $item->pivot->total_amount);
    }

    // Computes activities total
    public function computeActivitiesTotal(): float
    {
        return $this->sumTransactionItems('activities', fn($item) => $item->pivot->quantity * $item->amount);
    }

    // Computes services total
    public function computeServicesTotal(): float
    {
        return $this->sumTransactionItems('services', fn($item) => $item->pivot->quantity * $item->amount);
    }

    // Computes each item types (Room, Activities, Services, Pet)
    protected function sumTransactionItems(string $relation, callable $calculator): float
    {
        $items = $this->transaction->$relation ?? collect();
        return $items->sum($calculator);
    }

    // Computes Base Subtotal (Room, Activities, Services, Pet) with no convenience fee.
    public function computeBaseSubtotal(): float
    {
        return $this->computeRoomsTotal()
            + $this->computeActivitiesTotal()
            + $this->computeServicesTotal();
    }

    // Computes Convenience Fee Total from completed payments
    public function computeConvenienceFeeTotal()
    {
        // Collects all payments related to this transaction
        $payments = $this->payments ?? collect();

        // Fetches all the convenience fee of the completed payments
        $total = $payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');

        // Fallback if no payment was made yet
        if ($total == 0 && $this->transaction->convenience_fee > 0) {
            return $this->transaction->convenience_fee;
        }

        return $total;
    }


    // Fetches pet fee amount from Service table
    protected function getPetFeeAmount(): float
    {
        $service = Service::where('name', 'Pet Fee')->first();
        return $service?->amount ?? 0;
    }



    /**
     * -------------------------- SEND OFFICIAL RECEIPT TO EMAIL ----------------------------------
     *
     * Sends the official receipt to the guest's email address.
     * Uses the EmailService and BrandingService to generate the PDF and send it.
     *
     * ---------------------------------------------------------------------------------------------
     */
    public function sendReceiptToEmail(EmailService $emailService, BrandingService $brandingService)
    {
        Log::info('Send Receipt To Email Method called.');

        // Ensure all required data is available before proceeding
        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            Log::error('Missing data for sending official receipt.');
            abort(404, 'Missing data for generating the official receipt.');
        }

        // Fetch related activities tied to the transaction, including pivot data
        $activities = $this->transaction->activities()->withPivot(
            'quantity',
            'amount',
            'activity_datetime',
            'status'
        )->get();

        //Fetch data related to services tied to the transaction, including pivot data
        $services = $this->transaction->services()->withPivot(
            'quantity',
            'amount',
            'service_datetime',
            'status'
        )->get();

        // Fetch related properties tied to the transaction, including pivot data
        $properties = $this->transaction->properties()->withPivot(
            'adults',
            'kids',
            'extra_guest',
            'extra_charge',
            'amount',
            'total_amount',
            'days'
        )->get();

        // Merge all necessary data for the PDF and email
        $data = array_merge(
            [
                'receipt' => $this->receipt,
                'invoice' => $this->invoice,
                'transaction' => $this->transaction,
                'transactionUser' => $this->transactionUser,
                'properties' => $properties,
                'activities' => $activities,
                'services' => $services,
            ],
            $brandingService->getBrandingData()
        );

        // Generate the receipt PDF using a Blade view
        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);
        $pdfContent = $pdf->output(); // Get the raw PDF content

        // Send the PDF to the user's email using the EmailService
        $emailService->sendOfficialReceipt(
            $this->transactionUser->email,
            $pdfContent,
            $this->receipt->receipt_number,
            $this->transactionUser,
            $data
        );

        // Notify user and log the success
        session()->flash('message', 'Official receipt has been sent to guest\'s email!');
        Log::info('Official receipt sent to email: ' . $this->transactionUser->email);
    }


    /**
     * ------------------------- REQUEST REMAINING BALANCE RECEIPT ----------------------------------
     *
     * Sends a payment link to the guest for paying the remaining balance of their reservation.
     * Uses PayMongoService to generate a checkout session and NotificationService to email the guest.
     *
     * ---------------------------------------------------------------------------------------------
     */
    public function requestRemainingBalance(PayMongoService $payMongo, NotificationService $notifier)
    {
        Log::info('Request Remaining Balance method called.');

        $transaction = $this->transaction;
        $invoice = $this->invoice;
        $user = $this->transactionUser;

        // Build the payload required by PayMongo for the checkout session
        $payload = [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ['card', 'gcash', 'qrph', 'paymaya'],
                    'success_url' => route('guest.thank-you-page'), // Redirect after successful payment
                    'cancel_url' => url('/payment-failed'), // Redirect if payment is cancelled
                    'line_items' => [[
                        'currency' => 'PHP',
                        'amount' => intval($invoice->balance_due * 100), // PayMongo expects amount in centavos
                        'description' => 'Reservation ' . $transaction->transaction_number,
                        'name' => 'Canopy Farm PH',
                        'quantity' => 1,
                    ]],
                    'description' => 'Reservation for ' . $user->first_name . ' ' . $user->last_name,
                    'metadata' => [
                        'invoice_id' => (string) $invoice->id,
                        'payment_type' => 'Remaining Balance',
                        'notes' => 'Payment for Remaining Balance',
                    ],
                ],
            ],
        ];

        // Create the checkout session through PayMongo
        $response = $payMongo->createCheckoutSession($payload);

        // Extract the payment link from the response
        $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

        // Save the payment link in the transaction record if it exists
        if ($paymentLink) {
            $transaction->update(['payment_link' => $paymentLink]);
        }

        // Mark that the invoice has requested for remaining balance
        $invoice->requested_remaining_balance = true;
        $invoice->save();

        // Attempt to send email notification to guest with the payment link
        try {
            $notifier->sendRemainingBalanceEmail($user, $transaction, $invoice, $paymentLink);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage()); // Show error message in UI
        }

        // Redirect back to the reservation view page
        return redirect()->route('admin.view-reservation', ['transaction' => $transaction->id]);
    }


    /**
     * --------------------------------- OFFICIAL RECEIPT GENERATION ---------------------------------
     *
     * Triggers the generation of an official receipt for a completed invoice
     * -----------------------------------------------------------------------------------------------
     */
    public function GenerateReceipt()
    {
        Log::info('Show Generate Official Receipt Modal method triggered.');

        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        $receipt = $this->receiptService->generateReceipt($this->invoice);

        if (!$receipt) {
            $this->cannotGenerateReceiptModal = true;
            return;
        }

        $this->receipt = $receipt;
    }

    /**
     * ---------------------------------- OFFICIAL RECEIPT PRINTING ----------------------------------
     *
     * Generates and streams a PDF version of the official receipt.
     *
     * -----------------------------------------------------------------------------------------------
     */
    public function printOfficialReceipt()
    {
        Log::info('Print Official Receipt method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for generating the official receipt.');
        }

        $this->activities = $this->transaction->activities()
            ->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();

        $this->services = $this->transaction->services()
        ->withPivot('quantity', 'amount', 'service_datetime', 'status')->get();

        $this->properties = $this->transaction->properties()
            ->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'properties' => $this->properties,
            'activities' => $this->activities,
            'services' => $this->services,
        ];

        $pdfOutput = $this->receiptService->generatePdf($data, $this->receipt->receipt_number);

        return response()->streamDownload(function () use ($pdfOutput) {
            echo $pdfOutput;
        }, 'official_receipt_' . $this->receipt->receipt_number . '.pdf');
    }


    /**
     * -------------------- EXPORT RESERVATION DETAILS REPORT --------------------
     *
     * Generates a detailed PDF report of a reservation, including:
     * - Guest details
     * - Invoice and payment records
     * - Booked properties
     * - Added activities and their respective pivot data
     * - Totals for rooms and add-ons
     * 
     * ----------------------------------------------------------------------------
     */
    public function exportReservationDetails()
    {
        $transaction = Transaction::with([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
            'properties',
            'guestPets', 
            'promoCode', 
            'services' => function($query){
                $query->withPivot('quantity', 'amount', 'service_datetime', 'status', 'payment_status');
            }, 
            'activities' => function ($query) {
                $query->withPivot('quantity', 'amount', 'activity_datetime', 'status');
            },
        ])->findOrFail($this->transaction->id);

        //Compute Total Service Charge Acquired
        $totalServiceCharges = $transaction->services->sum(function ($service) {
        return ($service->pivot->quantity ?? 0) * ($service->pivot->amount ?? 0);

    });

        $payments = $transaction->invoice->payments ?? collect();
        $convenienceFeeTotal = $payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');

        $pdf = Pdf::loadView('livewire.admin.reservations.reservation-details', [
            'transaction' => $transaction,  // Pass the actual transaction
            //Pass the relationships
            'guestDetails' => $transaction->guestDetails,
            'invoice' => $transaction->invoice,
            'guestPets' => $transaction->guestPets,
            'promoCode' => $transaction->promoCode, 
            'activities' => $transaction->activities,
            'properties' => $transaction->properties,
            'payments' => $transaction->invoice->payments,
            'totalRooms' => $transaction->totalRooms,
            'totalAddons' => $transaction->totalAddons,
            'totalServiceCharges' => $totalServiceCharges,
            'convenienceFeeTotal' => $convenienceFeeTotal,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reservation-details-' . $this->transaction->start_datetime . '.pdf');
    }


    /**
     * ---------------------------- MODALS ----------------------------------------
     *
     * Handles the opening and closing of modals related to:
     * - Receipt viewing
     * - Payment creation
     * - Activity addition
     *
     * ----------------------------------------------------------------------------
     */
    public function ShowReceipt()
    {
        Log::info('Show Receipt method called.');

        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        if ($this->invoice->balance_due > 0) {
            abort(400, 'Receipt cannot be shown. Invoice still has balance due.');
        }

        $existing = Receipt::where('invoice_id', $this->invoice->id)->first();

        if (!$existing) {
            abort(404, 'No receipt found for this invoice.');
        }

        $this->receipt = $existing;
        $this->showReceiptModal = true;
    }

    public function OpenCreatePaymentModal()
    {

        Log::info('Open Create Payment method called.');
        $this->createPaymentModal = true;
    }

    public function CloseCreatePaymentModal()
    {

        Log::info('Close Create Payment method called.');
        $this->createPaymentModal = false;
    }

    public function openModal(string $modalType)
    {
        Log::info("Open modal: $modalType");
        $this->activeModal = $modalType;
    }

    public function closeModal()
    {
        $this->activeModal = '';

        // Resets the input quantity
        $this->quantity = [];
        $this->cart = [];
    }




    // --------------------- DATABASE INSERTION --------------------------- // 


    public function CreatePayment(PaymentService $paymentService)
    {
        Log::info('Create Payment method called.');

        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!$this->invoice) {
            abort(404, 'No invoice found.');
        }

        $paymentService->create([
            'invoice'       => $this->invoice,
            'transaction'   => $this->transaction,
            'amount_paid'   => $this->amount_paid,
            'payment_type'    => $this->payment_type,
            'mode_of_payment'  => 'cash',
            'payment_date'  => $this->payment_date,
            'notes'         => $this->notes,
            'payment_status' => 'completed',
            'currency'         => 'PHP',
            'verified_at'      => now(),
        ]);

        $this->updatePaymentStatus($paymentService, $this->amount_paid);
        $this->recalculateInvoice();
        $this->reset([
            'amount_paid',
            'mode_of_payment',
            'payment_type',
            'payment_date',
            'payment_status',
            'notes',
            'currency',
            'verified_at'
        ]);

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction->id])
            ->with('success', 'Payment created successfully.');
    }


    // --------------------- HELPER METHODS --------------------------- // 

    // After a payment, the payment status of the items in the cart will be tracked and updated.
    public function updatePaymentStatus(PaymentService $paymentService, float $amountPaid)
    {
        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);

        $this->transaction->load('activities', 'properties', 'services', 'guestPets');
    }



    public function getGuestAge()
    {
        $birthdate = $this->guest['birthdate'] ?? $this->editingBirthDate ?? null;

        if ($birthdate) {
            $age = \Carbon\Carbon::parse($birthdate)->age;
            $this->guest['age'] = $age;

            // Determine category
            if ($age <= 2) {
                $category = 'Infant';
            } elseif ($age >= 3 && $age <= 17) {
                $category = 'Kid';
            } elseif ($age >= 18 && $age <= 59) {
                $category = 'Adult';
            } else {
                $category = 'Senior';
            }

            $this->guest['category'] = $category;
            $this->filterGuestTypesByAge();
        } else {
            $this->guest['age'] = null;
            $this->guest['category'] = null;
            $this->filteredGuestTypes = $this->guestTypes;
        }
    }

    public function getIsFullProperty()
    {


        // Total allowed guests (sum adults + kids across all properties)
        $totalAllowedGuests = $this->transactionProperties->sum('adults') + $this->transactionProperties->sum('kids');

        // Total guests recorded in transaction's guestDetails (assuming it's a collection)
        $totalGuests = $this->transaction->guestDetails->count();

        Log::info("getIsFullProperty: Total allowed guests = {$totalAllowedGuests}, Total guests recorded = {$totalGuests}");

        // Return true if all guest slots are full or exceeded
        return $totalGuests >= $totalAllowedGuests;
    }


    public function filterGuestTypesByAge()
    {
        $age = $this->guest['age'] ?? null;

        if (is_null($age)) {
            $birthdate = $this->editingBirthDate ?? $this->guest['birthdate'] ?? null;
            $age = $birthdate ? \Carbon\Carbon::parse($birthdate)->age : null;
        }

        if (is_null($age)) {
            $this->filteredGuestTypes = $this->guestTypes;
            return;
        }

        $this->filteredGuestTypes = collect($this->guestTypes)->filter(function ($type) use ($age) {
            return match ($type['name']) {
                'Infant' => $age <= 2,
                'Kid'    => $age >= 3 && $age <= 17,
                'Adult'  => $age >= 18 && $age <= 59,
                'Senior' => $age >= 60,
                'PWD'    => true,
                default  => false,
            };
        })->values()->all();
    }

    public function loadGuestDetails()
    {
        $this->guestDetails = GuestDetail::where('transaction_id', $this->transaction->id)->get();
        $this->getIsFullProperty();
    }
}
