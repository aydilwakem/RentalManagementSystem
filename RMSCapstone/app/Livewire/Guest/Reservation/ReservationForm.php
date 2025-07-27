<?php

namespace App\Livewire\Guest\Reservation;

use App\Mail\NewReservationMail;
use Livewire\Component;
use App\Models\Service;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\TransactionUser;
use App\Models\PropertyCategory;
use App\Models\GuestDetail;
use App\Models\Invoice;
use App\Models\GuestPet;
use App\Models\RoomRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSubmittedMail;
use App\Models\PaymentMethod;
use App\Models\GuestType;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Services\CartService;
use App\Services\ServiceBag;
use App\Services\RoomRateService;
use App\Services\RoomAvailabilityService;
use App\Services\PromoCodeService;
use App\Services\BrandingService;
use App\Services\PayMongoService;
use App\Services\EmailService;
use App\Traits\HasFormattedDates;
use App\Traits\ReservationHelpers;



class ReservationForm extends Component
{

    // ----------------------- GENERAL ---------------------------- //


    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';

    public $check_in_date;
    public $check_out_date;
    public $cart = []; // Keeps all the selected rooms and activities
    public $total_amount; // Total amount for the reservation
    public $total_pax = 2; // Total number of guests (adults + kids)
    public $sub_total;

    // ----------------------- ROOMS ---------------------------- //
    public $rooms = [];
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = [];
    public $roomAmount = [];
    public $roomsTotalAmount = [];
    public $selectedFeatures = [];
    public $roomCategories;

    // --------------------- ACTIVITIES ------------------------- //

    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];

    // ------------------- GUEST DETAIL ------------------------ //
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $country;
    public $heard_from;

    // ------------------- INVOICE -------------------- //
    public $invoice_number;
    public $transaction_number;


    // ------------------- GUESTS -------------------- //
    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin;
    public $guest_types = [];
    public $guests = [];


    // --------------- EDITING GUEST DETAIL ------------------- //
    public $editingGuestIndex = null;
    public $editingGuest = [
        'guest_first_name' => '',
        'guest_middle_name' => '',
        'guest_last_name' => '',
        'guest_suffix' => '',
        'guest_type_id' => '',
        'guest_gender' => '',
        'guest_residency' => '',
        'guest_country_of_origin' => '',
    ];


    // ------------------- OTHERS -------------------- //
    public $terms = 0;
    public $terms_and_conditions;
    public $expirationHours;
    public $roomCategoryFilter = '';


    // --------------- PAYMENT ------------------- //
    public $enable_deposit_percentage = true;
    public $depositPercentage;
    public $convenienceFeeInCentavos;
    public $convenience_fee;
    public $paymentMethod;
    public $paymentStatus = [];
    public $paymentLink;


    // ---------------------- MODALS -------------------------- //
    public $confirmReservationModal = false;
    public $showEditModal = false;
    public $showGuestModal = false;

    //----------------------- BRANDING ------------------------ //
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    //----------------------- PROMO CODE ------------------------ //
    public $promo;
    public $promoCode;
    public $discountMessage;
    public $errorMessage;
    public $promoDiscount;
    public $promo_discount_amount;

    //----------------------- SERVICES ------------------------ //
    protected RoomRateService $roomRateService;
    protected RoomAvailabilityService $roomAvailabilityService;
    protected PromoCodeService $promoCodeService;
    protected CartService $cartService;
    protected BrandingService $brandingService;
    protected PayMongoService $payMongo;
    protected EmailService $emailService;

    //----------------------- TRAITS -------------------------- // 

    use HasFormattedDates;

    use ReservationHelpers;

    // ------------------- NAVIGATION STEPS -------------------- //

    public $currentStep = 1;
    public $totalSteps = 4;
    protected $listeners = ['refreshComponent' => '$refresh'];
    protected $queryString  = ['currentStep'];


    public bool $bringingPets = false;
    public int $pet_count;
    public $pets = [];
    public string $breed = '';
    public $dynamicKidOptions = [];



    public function boot(ServiceBag $services)
    {
        $this->cartService = $services->cartService;
        $this->roomRateService = $services->roomRateService;
        $this->roomAvailabilityService = $services->roomAvailabilityService;
        $this->promoCodeService = $services->promoCodeService;
        $this->brandingService = $services->brandingService;
        $this->payMongo = $services->payMongoService;
        $this->emailService = $services->emailService;
    }



    public function confirmCreate()
    {
        $this->confirmReservationModal = true;
    }

    public function openGuestModal()
    {
        $this->showGuestModal = true;
    }

    public function closeGuestModal()
    {
        $this->showGuestModal = false;
    }



    public function mount()
    {
        $this->initializeDates();
        $this->prepareOccupancyRules();
        $this->loadStaticData();
        $this->loadRooms();
        $this->loadBranding();
        $this->getAvailableRooms();
    }




    /**
     * ------------------------ LIVEWIRE HOOK: UPDATED -----------------------
     *
     * Responds to changes in component properties such as:
     * - Adults/Kids: Recalculates valid kid options and pricing.
     * - Dates: Reloads available rooms.
     * ----------------------------------------------------------------------
     */
    public function updated($property)
    {
        // ---------------------------- ADULTS AND KIDS -------------------------- //
        if (Str::startsWith($property, 'adults.') || Str::startsWith($property, 'kids.')) {

            $roomId = explode('.', $property)[1];

            $room = Property::find($roomId);
            if (!$room) {
                $this->addError('cart', 'Room not found.');
                return;
            }

            $this->updateKidOptions($roomId);
            $this->updateSelectedRoomDetails($roomId, $room);
            $this->computeTotalPax();
            $this->getAvailableRooms();
        }

        // --------------- CHECK-IN AND CHECK-OUT DATES ------------------- //
        if (in_array($property, ['check_in_date', 'check_out_date'])) {

            // Clear guest inputs
            $this->cart = [];
            $this->currentStep = 1;
            $this->pet_count = 0;
            $this->pets = [];

            $this->getAvailableRooms();
            $this->removePromoCode();
            $this->computeSubtotalAmount();
            $this->computeTotalAmount();
        }
    }


    public function render()
    {
        $this->getAvailableRooms();
        return view('livewire.guest.reservation.reservation-form');
    }




    // --------------------------------------------- NAVIGATION STEPS ------------------------------------- //

    public function increaseStep()
    {
        $this->resetErrorBag();
        $this->validateData();

        $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
    }

    public function decreaseStep()
    {
        $this->resetErrorBag();
        $this->currentStep = max($this->currentStep - 1, 1);
    }








    public function updatedPetCount($value)
    {
        $value = (int) $value;

        // Expands or shrink the pets array
        if ($value > count($this->pets)) {
            for ($i = count($this->pets); $i < $value; $i++) {
                $this->pets[] = '';
            }
        } else {
            $this->pets = array_slice($this->pet_breed, 0, $value);
        }
    }

    /**
     * ---------------------------- RESERVATION COMPUTATIONS ----------------------------
     *
     * Handles core computed data for reservations based on selected check-in/out dates.
     *
     * Core responsibilities:
     * - `getAvailableRooms`: Retrieves room availability using the service layer.
     * - `getStayDurationProperty`: Calculates the number of nights between check-in and check-out.
     * - `getDepositProperty`: Computes the deposit amount based on total and system settings.
     *
     * Dependencies:
     * - Relies on `RoomAvailabilityService` and trait methods like `getStayDuration` and `getDeposit`.
     *
     * ----------------------------------------------------------------------------------
     */
    public function getAvailableRooms()
    {
        $this->rooms = $this->roomAvailabilityService
            ->getAvailableRooms($this->check_in_date, $this->check_out_date);
        $this->prepareOccupancyRules();
    }

    public function getStayDurationProperty()
    {
        return $this->getStayDuration($this->check_in_date, $this->check_out_date);
    }

    public function getDepositProperty()
    {
        return $this->getDeposit($this->computeTotalAmount());
    }



    /**
     * ----------------------------- CART COMPUTATION LOGIC -----------------------------
     *
     * Handles the computation of cart-related totals including:
     * - Pax count (adults + kids) from room items.
     * - Total amounts for rooms and activities.
     * - Subtotal with promo discount applied.
     * - Final total amount including a 3% convenience fee.
     *
     * Key Methods:
     * - `computeTotalPax`: Sums all guests across room items.
     * - `computeTotalAmountOfAllRooms/Activities`: Calculates respective totals.
     * - `computeSubtotalAmount`: Applies discount to base subtotal.
     * - `computeTotalAmount`: Final computation with convenience fee and promo deduction.
     * - `computeConvenienceFee`: Recomputes fee for accuracy.
     *
     * Internal Helper:
     * - `getItemsByType`: Filters cart items by their type (room, activity, etc).
     *
     * -----------------------------------------------------------------------------------
     */

    protected function getItemsByType(string $type): array
    {
        return array_filter($this->cart, fn($item) => $item['type'] === $type);
    }

    public function computeTotalPax(): void
    {
        $this->total_pax = collect($this->getItemsByType('room'))
            ->reduce(function ($carry, $item) {
                $adults = (int) ($item['adults'] ?? 0);
                $kids = (int) ($item['kids'] ?? 0);
                return $carry + $adults + $kids;
            }, 0);
    }

    public function computeTotalAmountOfAllRooms(): float
    {
        return collect($this->getItemsByType('room'))
            ->sum('total_amount');
    }
    public function computeTotalAmountOfAllActivities(): float
    {
        return collect($this->getItemsByType('activity'))
            ->sum('amount');
    }

    public function computeSubtotalAmount()
    {
        $baseSubtotal = $this->computeTotalAmountOfAllRooms() + $this->computeTotalAmountOfAllActivities() + $this->computePetTotal();
        $total = $baseSubtotal - $this->promoDiscount;
        $this->sub_total = max(0, $total);

        return $this->sub_total;
    }

    public function computeTotalAmount()
    {
        // Step 1: Calculate base subtotal (rooms + activities)
        $baseSubtotal = $this->computeTotalAmountOfAllRooms() + $this->computeTotalAmountOfAllActivities() + $this->computePetTotal();

        // Step 2: Compute 3% convenience fee based on base subtotal
        $this->convenience_fee = $this->sub_total * 0.03;

        // Step 3: Add convenience fee to subtotal
        $subtotalWithFee = $baseSubtotal + $this->convenience_fee;

        // Step 4: Apply any promo discount
        $total = $subtotalWithFee - $this->promoDiscount;

        // Step 5: Ensure total amount is not negative
        $this->total_amount = max(0, $total);

        return $this->total_amount;
    }

    public function computeConvenienceFee()
    {
        // Recompute to ensure the most current values
        $this->computeTotalAmount();

        return $this->convenience_fee;
    }

    public function computePetTotal(): float
    {
        $petCount = $this->pet_count ?? 0;
        $stayDuration = $this->getStayDurationProperty() ?? 0;
        $feePerPetPerDay = $this->getPetFeeAmount();

        return $petCount * $feePerPetPerDay * $stayDuration;
    }

    protected function getPetFeeAmount(): float
    {
        $service = Service::where('name', 'Pet Fee')->first();
        return $service?->amount ?? 0;
    }




    /**
     * ----------------------------- PROMO CODE LOGIC -----------------------------
     *
     * Manages the application and removal of promotional codes affecting
     * the subtotal and total amount in the cart.
     * 
     * Internal Helper:
     * - `failPromo`: Fallback handler for invalid promo codes, resets discount state.
     *
     * -----------------------------------------------------------------------------
     */
    public function applyPromoCode()
    {

        Log::info('Apply Promo Code method called with promoCode: ' . $this->promoCode);

        $this->reset(['discountMessage', 'errorMessage']);

        $this->sub_total = $this->computeSubtotalAmount();

        $response = $this->promoCodeService->validateAndApply(
            $this->promoCode,
            $this->total_amount,
            $this->sub_total
        );

        if (!$response['success']) {
            return $this->failPromo($response['message']);
        }

        $this->promoDiscount = $response['discount'];
        $this->promo_discount_amount = $this->promoDiscount;
        $this->total_amount = $this->sub_total - $this->promoDiscount;
        $this->discountMessage = $response['message'];
        $this->errorMessage = null;

        $this->getAvailableRooms();
    }

    public function removePromoCode()
    {
        Log::info('removePromoCode called');
        $this->promoCode = '';
        $this->promoDiscount = 0;
        $this->discountMessage = null;
        $this->total_amount = $this->computeTotalAmount();
        $this->errorMessage = null;
    }

    private function failPromo(string $message)
    {
        $this->promoDiscount = 0;
        $this->total_amount = $this->computeTotalAmount();
        $this->discountMessage = null;
        $this->promoCode = '';
        $this->errorMessage = $message;
    }





    /**
     * ----------------------------- ROOM CART LOGIC -----------------------------
     *
     * Handles the process of adding a room to the cart, including validation,
     * duplicate checking, rate computation, and cart state updates.
     * -----------------------------------------------------------------------------
     */
    public function addRoomToCart($roomId)
    {

        $this->resetErrorBag();

        if (!$this->checkInOutDatesAreValid()) {
            $this->addError('cart', 'Please select check-in and check-out dates before adding a room.');
            return;
        }

        $room = Property::findOrFail($roomId);

        if ($this->isItemAlreadyInCart('room', $roomId)) {
            $this->addError('cart', 'This room is already in the cart.');
            return;
        }

        $context = $this->prepareRoomCartContext($room, $roomId);

        $added = $this->cartService->addItem(
            'room',
            $roomId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->paymentStatus,
            $context
        );



        if (!$added) {
            $this->addError('cart', 'Failed to add room to cart.');
            return;
        }

        $this->computeTotalPax();
        $this->getAvailableRooms();
    }


    /**
     * ----------------------------- ACTIVITY CART LOGIC -----------------------------
     *
     * Handles adding and managing quantities of activities (or other non-room items)
     * in the reservation cart. 
     *
     * ------------------------------------------------------------------------------
     */

    public function addActivityToCart($type, $itemId)
    {
        $newCart = $this->cartService->addItem(
            $type,
            $itemId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->paymentStatus
        );

        if ($this->isItemAlreadyInCart($type, $itemId)) {
            $this->addError('cart', 'This item is already in the cart.');
            return;
        }

        $this->cart = $newCart;
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
     * ----------------------------- GUEST MANAGEMENT LOGIC -----------------------------
     *
     * Handles the addition, editing, and deletion of multiple guest entries dynamically
     * within a reservation or booking form. This allows users to input and manage multiple
     * guests before final submission.
     * -----------------------------------------------------------------------------------
     */


    public function addMultipleGuests()
    {
        $this->validateGuestData();
        $this->guests[] = $this->makeGuestArray();
        $this->showGuestModal = false;
        $this->resetGuestInputFields();
    }

    public $showPetsModal = false;



    public function addMultiplePets()
    {
        $this->validate([
            'breed' => 'required|string|max:255',
        ]);

        $this->pets[] = $this->makePetsArray();
        $this->pet_count = count($this->pets);

        $this->reset('breed'); // clear input
    }



    public function editGuest($index)
    {
        $this->editingGuestIndex = $index;
        $this->editingGuest = $this->guests[$index];
        $this->showEditModal = true;
    }
    public function updateGuest()
    {
        if (!is_null($this->editingGuestIndex)) {
            $this->guests[$this->editingGuestIndex] = $this->editingGuest;
        }

        $this->showEditModal = false;
        $this->reset('editingGuestIndex', 'editingGuest');
    }

    public function deleteGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
    }






    /**
     * -------------------------------- REMOVE ITEM FROM CART --------------------------------
     *
     * Handles the removal of a specific item (room or activity) from the reservation cart.
     * ----------------------------------------------------------------------------------------
     */

    public function removeFromCart($type, $itemId)
    {
        Log::info('removeFromCart method called');

        // Delegate to the CartService, which internally calls the appropriate service
        $this->cart = $this->cartService->removeItem($type, $itemId, $this->cart);

        // Recalculate necessary values
        $this->computeTotalPax();
        $this->computeTotalAmount();
        $this->getAvailableRooms();

        // Check if there are no "room" items left in the cart
        $hasRoomItems = collect($this->cart)->contains(function ($item) {
            return $item['type'] === 'room';
        });

        // If there are no rooms in the cart, reset to the first step
        if (!$hasRoomItems) {
            $this->cart = [];
            $this->currentStep = 1;
            $this->promoCode = '';
            $this->promoDiscount = 0;
            $this->discountMessage = null;
            $this->errorMessage = null;
        }
    }



    /**
     * ----------------------------- RESERVATION WORKFLOW LOGIC -----------------------------
     *
     * Handles the complete process of registering a guest reservation, from user creation
     * to payment session generation and confirmation email dispatch.
     *
     * ---------------------------------------------------------------------------------------
     */


    public function register(PayMongoService $payMongo, EmailService $emailService)
    {
        // Reset validation error messages
        $this->resetErrorBag();

        // Will hold data needed for email notifications
        $reservationData = [];

        // Begin database transaction to ensure atomic operations
        DB::transaction(function () use (&$reservationData, $payMongo, $emailService) {

            // Retrieve settings from the database
            $setting = Setting::first();

            // Get deposit percentage if enabled; otherwise, set to 0
            $this->depositPercentage = $setting && $setting->enable_deposit_percentage
                ? $setting->deposit_percentage
                : 0;

            // Set the expiration time (in hours) for submitting payment proof
            $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24;

            // Check if a valid promo code was entered
            $promo = PromoCode::where('code', $this->promoCode)->first();


            $transactionUser = $this->createTransactionUser();
            $transaction = $this->createTransaction($transactionUser, $promo);
            $invoice = $this->createInvoice($transaction);
            $this->attachCartItemsToTransaction($transaction);
            $this->insertGuestDetails($transaction);
            $this->insertGuestPetDetails($transaction);

            // Compute the base amount to charge based on deposit percentage or full amount
            $baseAmount = $this->depositPercentage > 0
                ? $this->computeTotalAmount() * ($this->depositPercentage / 100)
                : $this->computeTotalAmount();

            // Convert the amount to centavos for PayMongo (e.g., 1500 -> 150000)
            $amountInCentavos = intval($baseAmount * 100);

            // Prepare payload for PayMongo checkout session
            $payload = $this->preparePayMongoPayload($amountInCentavos, $transaction, $invoice);

            try {
                // Create the checkout session with PayMongo API
                $response = $payMongo->createCheckoutSession($payload);

                // Get the generated payment link from the response
                $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

                // Save the payment link to the transaction for later reference
                if ($paymentLink) {
                    $transaction->update(['payment_link' => $paymentLink]);
                }

                // Increment promo code usage count if used
                if ($promo) {
                    $promo->increment('uses_count');
                }
            } catch (\Exception $e) {
                // Log any PayMongo API errors
                Log::error('PayMongo link creation failed: ' . $e->getMessage());
                $paymentLink = null;
            }

            // Calculate total and deposit amount again for email
            $total = $this->computeTotalAmount();
            $deposit = $total * ($this->depositPercentage / 100);



            // Prepare data for confirmation email
            $reservationData = $this->prepareReservationData($transaction, $invoice, $total, $deposit);



            $reservationData['payment_link'] = $paymentLink;
        });

        // // Attempt to send confirmation emails to guest and admin
        // try {
        //     $emailService->sendReservationEmails($reservationData);
        // } catch (\Exception $e) {
        //     // If email sending fails, flash error but still continue
        //     session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        // }

        // Show success flash message
        session()->flash('success', 'Reservation successfully submitted!');

        // Redirect user to payment page if available
        if (!empty($reservationData['payment_link'])) {
            session()->flash('success', 'Reservation submitted. You are being redirected to the payment page.');
            return redirect()->away($reservationData['payment_link']);
        }

        // Fallback if no payment link — direct to proof of payment submission page
        return redirect()->route('guest.proof-of-payment-page');
    }





    /**
     * -------------------------- RESERVATION CREATION METHODS ---------------------------
     *
     * These methods are executed *within* a single database transaction to ensure
     * the reservation process is atomic and consistent. If any of these steps fail,
     * the entire reservation is rolled back to prevent partial data persistence.
     *
     * ------------------------------------------------------------------------------------
     */
    protected function createTransactionUser(): TransactionUser
    {
        return TransactionUser::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'company_name' => $this->company_name,
            'country' => $this->country,
            'trn_user_type' => $this->trn_user_type,
        ]);
    }

    protected function createTransaction(TransactionUser $transactionUser, ?PromoCode $promo): Transaction
    {
        $totalAmount = $this->computeTotalAmount();
        $depositAmount = $totalAmount * ($this->depositPercentage / 100);

        return Transaction::create([
            'transaction_number' => 'TXN-' . strtoupper(Str::random(8)),
            'reservation_type_id' => $this->reservation_type_id,
            'created_by' => $transactionUser->id,
            'promo_id' => $promo?->id,
            'start_datetime' => $this->check_in_date,
            'end_datetime' => $this->check_out_date,
            'total_adults' => collect($this->cart)->sum('adults'),
            'total_kids' => collect($this->cart)->sum('kids'),
            'pax' => $this->total_pax,
            'sub_total' => $this->sub_total ?? 0,
            'convenience_fee' => $this->convenience_fee ?? 0,
            'promo_discount_amount' => $this->promo_discount_amount ?? 0,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount,
            'heard_from' => $this->heard_from,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            'terms' => $this->terms,
        ]);
    }

    protected function createInvoice(Transaction $transaction): Invoice
    {
        $totalAmount = $this->computeTotalAmount();

        return Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_type' => 'Room',
            'sub_total' => $totalAmount,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $totalAmount,
            'due_date' => $this->check_out_date,
            'invoice_status' => 'pending',
        ]);
    }

    protected function attachCartItemsToTransaction(Transaction $transaction): void
    {
        foreach ($this->cart as $item) {
            if ($item['type'] === 'room') {
                $this->attachRoomToTransaction($transaction, $item);
            }

            if ($item['type'] === 'activity') {
                $this->attachActivityToTransaction($transaction, $item);
            }
        }
    }

    protected function attachActivityToTransaction(Transaction $transaction, array $item): void
    {
        $transaction->activities()->attach($item['activity_id'], [
            'quantity' => $item['quantity'],
            'amount' => $item['amount'],
            'payment_status' => $item['payment_status'],
        ]);
    }

    protected function attachRoomToTransaction(Transaction $transaction, array $item): void
    {
        $transaction->properties()->attach($item['room_id'], [
            'adults' => $item['adults'],
            'kids' => $item['kids'],
            'days' => $item['days'],
            'extra_guest' => $item['extra_guest'],
            'extra_charge' => $item['extra_charge'],
            'amount' => $item['roomAmount'],
            'total_amount' => $item['total_amount'],
            'room_rate_id' => $item['rate_id'],
            'payment_status' => $item['payment_status'],
        ]);

        Log::info('Attaching room to transaction with data:', $item);
    }

    protected function insertGuestDetails(Transaction $transaction): void
    {
        foreach ($this->guests as $guest) {
            GuestDetail::create([
                'transaction_id' => $transaction->id,
                'first_name' => $guest['guest_first_name'],
                'middle_name' => $guest['guest_middle_name'],
                'last_name' => $guest['guest_last_name'],
                'suffix' => $guest['guest_suffix'],
                'gender' => $guest['guest_gender'],
                'residency' => $guest['guest_residency'],
                'country_of_origin' => $guest['guest_country_of_origin'],
                'guest_type_id' => $guest['guest_type_id'],
            ]);
        }
    }

    protected function attachServiceToTransaction(Transaction $transaction, array $item): void
    {
        $transaction->services()->attach($item['service_id'], [
            'quantity' => $item['quantity'],
            'amount' => $item['amount'],
            'payment_status' => $item['payment_status'],
            'days' => $item['days']
        ]);

        Log::info('Attaching room to transaction with data:', $item);
    }

    protected function insertGuestPetDetails(Transaction $transaction): void
    {
        if (!$this->bringingPets || empty($this->pets)) {
            return;
        }

        $nights_stayed = $this->getStayDurationProperty();
        $feePerPetPerDay = $this->getPetFeeAmount();

        foreach ($this->pets as $pet) {
            GuestPet::create([
                'transaction_id' => $transaction->id,
                'breed'          => $pet['breed'],
                'pet_count'      => 1,
                'nights_stayed'  => $nights_stayed,
                'total_fee'      => $feePerPetPerDay * $nights_stayed,
            ]);
        }

        $totalPetCount = count($this->pets);
        $totalAmount = $totalPetCount * $feePerPetPerDay * $nights_stayed;

        $this->attachServiceToTransaction($transaction, [
            'service_id' => 1, // "Pet Fee"
            'days' =>  $nights_stayed,
            'quantity' => $totalPetCount,
            'amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ]);
    }










    /** 
     * ----------------------------- VALIDATION LOGIC -----------------------------
     * This section contains all methods and rules used for validating user input,
     * such as checking required fields, date logic, and cart duplication.
     * ----------------------------------------------------------------------------
     */
    public function validateData()
    {
        if (in_array($this->currentStep, [1, 2, 3])) {
            $this->validate([
                'cart' => 'required|array|min:1',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
            ]);
        }

        if ($this->currentStep == 3) {
            $this->validate([
                'first_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'contact_number' => 'required|string',
                'country' => 'required|string',
                'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
            ]);

            // if (count($this->guests) !== $this->total_pax) {
            //     throw \Illuminate\Validation\ValidationException::withMessages([
            //         'guests' => 'Please input all the guests before proceeding.',
            //     ]);
            // }
        }

        if ($this->currentStep == 4) {
            $this->validate([
                'terms' => 'accepted',
            ]);
        }
    }

    protected function validateGuestData()
    {
        return $this->validate([
            'guest_first_name' => 'required|string',
            'guest_middle_name' => 'nullable|string',
            'guest_last_name' => 'required|string',
            'guest_suffix' => 'nullable|string|max:10',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'nullable|in:local,foreigner',
            'guest_country_of_origin' => 'nullable|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);
    }

    protected function checkInOutDatesAreValid(): bool
    {
        return $this->check_in_date && $this->check_out_date;
    }

    protected function isItemAlreadyInCart(string $type, int $itemId): bool
    {
        foreach ($this->cart as $item) {
            if ($item['type'] === $type && $item["{$type}_id"] == $itemId) {
                return true;
            }
        }
        return false;
    }



    /**
     * --------------------------- RESERVATION HELPER METHODS ---------------------------
     *
     * Contains helper methods that support reservation-related functionality such as:
     * - Preparing room cart data for pricing and summary
     * - Managing guest form data (creation, reset, formatting)
     *
     * These methods are modular and intended to streamline the reservation process by
     * centralizing logic for rate calculation, guest input handling, and contextual data
     * preparation. This section is open for expansion as more reservation-related utilities
     * are needed.
     *
     * Examples:
     * - `prepareRoomCartContext`: Calculates pricing, extra guest charges, and totals.
     * - `makeGuestArray`: Formats current guest inputs into an array.
     * - `resetGuestInputFields`: Clears all guest input fields after use.
     * - `generateInvoiceNumber`: Creates invoice number combination.
     * - `prepareReservationData`: Prepares reservation data for email.
     * - `preparePaymongoPayload`: Prepares paymongo payload (metadata).
     *
     * -------------------------------------------------------------------------------
     */

    protected function prepareRoomCartContext($room, $roomId): array
    {
        $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);
        $adults = (int) ($this->adults[$roomId] ?? 1);
        $kids = (int) ($this->kids[$roomId] ?? 0);
        $stayDuration = $this->getStayDurationProperty();
        $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
        $roomAmount = $rate['amount'] * $stayDuration;
        $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;

        return [
            'room_name'     => $room->name_number,
            'extra_guest'   => $extraGuests,
            'days'          => $stayDuration,
            'adults'        => $adults,
            'kids'          => $kids,
            'roomAmount'    => $roomAmount,
            'roomRateName'  => $rate['name'],
            'rate_id'       => $rate['rate_id'],
            'extra_charge'  => $extraCharge,
            'total_amount'  => $roomAmount + $extraCharge,
        ];
    }
    protected function prepareReservationData(Transaction $transaction, Invoice $invoice, float $total, float $deposit): array
    {
        return [
            'name' => $this->first_name . ' ' . $this->last_name,
            'transaction_number' => $transaction->transaction_number,
            'email' => $this->email,
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $this->check_in_date,
            'check_out' => $this->check_out_date,
            'total_amount' => $total,
            'deposit' => $deposit,
            'expirationHours' => $this->expirationHours,
            'payment_link' => $this->paymentLink,
            'branding_company_name' => $this->companyName,
            'logo_path' => $this->logoPath,
            'branding_company_email' => $this->companyEmail,
            'branding_company_contact' => $this->companyContact,
            'company_address' => $this->companyAddress,
            'facebook_link' => $this->facebookLink,
            'instagram_link' => $this->instagramLink,
        ];
    }
    protected function preparePaymongoPayload(int $amountInCentavos, $transaction, $invoice): array
    {
        return [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ['card', 'gcash', 'paymaya'],
                    'success_url' => route('guest.thank-you-page'),
                    'cancel_url' => 'http://127.0.0.1:8000/payment-failed',
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => $amountInCentavos,
                            'description' => 'Reservation ' . $transaction->transaction_number,
                            'name' => 'Reservation Fee',
                            'quantity' => 1,
                        ],
                    ],
                    'description' => 'Reservation for ' . $this->first_name . ' ' . $this->last_name,
                    'metadata' => [
                        'invoice_id' => (string) $invoice->id,
                        'payment_type' => 'Security Deposit',
                        'notes' => 'Deposit for Reservation',
                        'convenience_fee' => $this->convenience_fee ?? 0,
                    ],
                ],
            ],
        ];
    }
    protected function makeGuestArray()
    {
        return [
            'guest_first_name' => $this->guest_first_name,
            'guest_middle_name' => $this->guest_middle_name,
            'guest_last_name' => $this->guest_last_name,
            'guest_suffix' => $this->guest_suffix,
            'guest_type_id' => $this->guest_type_id,
            'guest_gender' => $this->guest_gender,
            'guest_residency' => $this->guest_residency,
            'guest_country_of_origin' => $this->guest_country_of_origin,
        ];
    }

    protected function makePetsArray()
    {
        return [
            'breed' => $this->breed,
        ];
    }


    protected function resetGuestInputFields(): void
    {
        $this->reset([
            'guest_first_name',
            'guest_middle_name',
            'guest_last_name',
            'guest_suffix',
            'guest_type_id',
            'guest_gender',
            'guest_residency',
            'guest_country_of_origin',
        ]);
    }
    protected function generateInvoiceNumber(): string
    {
        return 'INV-' . strtoupper(Str::random(8));
    }

    /**
     * Prepare available adult and kid options for each room based on their occupancy type
     */
    public function prepareOccupancyRules()
    {
        foreach ($this->rooms as $room) {

            switch ($room->occupancy_type) {
                case 'combinations':
                    // Extract unique, sorted adult values from occupancy rules (excluding zero)
                    $room->availableAdultOptions = collect($room->occupancy_rules ?? [])
                        ->pluck('adults')
                        ->filter(fn($value) => $value > 0) // remove 0 if ever present
                        ->unique()
                        ->sort()
                        ->values()
                        ->all();

                    // Extract unique, sorted kid values from occupancy rules
                    $room->availableKidOptions = collect($room->occupancy_rules ?? [])
                        ->pluck('kids')
                        ->unique()
                        ->sort()
                        ->values();
                    break;

                case 'whole_number':
                    $range = range(0, $room->max_guests);

                    // Adults: only values >= 1
                    $room->availableAdultOptions = collect($range)->filter(fn($v) => $v > 0)->values();

                    // Kids: allow from 0
                    $room->availableKidOptions = collect($range);
                    break;

                case 'ideal_guest':
                    // Adults and kids range up to ideal_guest
                    $room->availableAdultOptions = collect(range(0, $room->ideal_guest));
                    $room->availableKidOptions = collect(range(0, $room->ideal_guest));
                    break;

                default:
                    // If occupancy type is unknown, use empty options
                    $room->availableAdultOptions = collect();
                    $room->availableKidOptions = collect();
            }
        }
    }


    /**
     * Update the list of valid kid options based on the selected number of adults and room type
     */
    public function updateKidOptions($roomId): void
    {
        $room = collect($this->rooms)->firstWhere('id', $roomId);
        if (!$room) return;

        $selectedAdults = $this->adults[$roomId] ?? 0;

        $this->dynamicKidOptions[$roomId] = match ($room->occupancy_type) {
            'whole_number', 'ideal_guest' => $this->generateWholeOrIdealKidOptions($room, $selectedAdults, $roomId),
            'combinations'                => $this->generateCombinationKidOptions($room, $selectedAdults, $roomId),
            default                       => [],
        };
    }

    /**
     * Generate kid options for 'whole_number' or 'ideal_guest' rooms.
     */
    protected function generateWholeOrIdealKidOptions($room, int $adults, $roomId): array
    {
        $maxGuests = $room->max_guests ?? $room->ideal_guest ?? 0;
        $remaining = max(0, $maxGuests - $adults);
        $this->resetIfExceedsLimit($roomId, $remaining);
        return range(0, $remaining);
    }

    /**
     * Generate kid options for 'combinations' rooms.
     */
    protected function generateCombinationKidOptions($room, int $adults, $roomId): array
    {
        $validCombos = collect($room->occupancy_rules ?? [])->where('adults', $adults);
        $options = $validCombos->pluck('kids')->unique()->sort()->values()->all();

        if (!in_array($this->kids[$roomId] ?? 0, $options)) {
            $this->kids[$roomId] = 0;
        }

        return $options;
    }

    /**
     * Reset kids value to 0 if it exceeds allowable options.
     */
    protected function resetIfExceedsLimit($roomId, int $limit): void
    {
        if (($this->kids[$roomId] ?? 0) > $limit) {
            $this->kids[$roomId] = 0;
        }
    }

    /**
     * Update the selected room's details including guest count, extra charges,
     * and total amount based on dynamic room rates and stay duration.
     */
    public function updateSelectedRoomDetails($roomId, $room)
    {
        foreach ($this->cart as $index => $item) {
            if ($item['type'] === 'room' && $item['room_id'] == $roomId) {
                $adults = (int) ($this->adults[$roomId] ?? 1);
                $kids = (int) ($this->kids[$roomId] ?? 0);

                $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
                $stayDuration = $this->getStayDurationProperty();
                $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);

                $roomAmount = $rate['amount'] * $stayDuration;
                $rate_id = $rate['rate_id'];
                $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;

                $this->cart[$index]['adults'] = $adults;
                $this->cart[$index]['kids'] = $kids;
                $this->cart[$index]['extra_guest'] = $extraGuests;
                $this->cart[$index]['extra_charge'] = $extraCharge;
                $this->cart[$index]['roomAmount'] = $roomAmount;
                $this->cart[$index]['rate_id'] = $rate_id;
                $this->cart[$index]['total_amount'] = $roomAmount + $extraCharge;
            }
        }
    }





    /**
     * ----------------------------- LOADERS -----------------------------
     *
     * Contains methods responsible for loading initial and dynamic data 
     * into the reservation form based on user context and current state.
     * 
     * Responsibilities:
     * - `initializeDates`: Sets the default check-in and check-out dates using the current time in Asia/Manila timezone.
     * - `loadRooms`: Fetches available rooms, applies dynamic rates using the RoomRateService, and maps rate-related metadata.
     * - `loadStaticData`: Loads static reference data such as available activities, payment methods, and guest types.
     * - `loadBranding`: Retrieves company branding details (name, logo, contact, social links) using the BrandingService.
     * 
     * These methods are typically called on mount or when data needs to be refreshed based on user interaction.
     * -------------------------------------------------------------------
     */

    /**
     * Sets default check-in and check-out dates.
     */
    protected function initializeDates()
    {
        $now = Carbon::now('Asia/Manila');
        $this->check_in_date = $now->format('Y-m-d');
        $this->check_out_date = $now->copy()->addDay()->format('Y-m-d');
    }

    /**
     * Loads available rooms and applies dynamic rates.
     */
    protected function loadRooms(): void
    {
        $this->rooms = Property::ofType('Room')
            ->availableRooms()
            ->with(['transactions.feedbacks.feedbackRatings', 'transactions.transactionUser'])
            ->get()
            ->map(function ($room) {
                $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);

                $room->dynamic_rate = $rate['amount'];
                $room->rate_name = $rate['name'];
                $room->rate_type = $rate['rate_type'];
                $room->rate_id = $rate['rate_id'];

                return $room;
            });
    }


    /**
     * Loads available activities, payment methods, and guest types.
     */
    protected function loadStaticData()
    {
        $this->roomCategories = PropertyCategory::all();
        $this->selectedFeatures = [];
        $this->activities = Activity::availableActivities()->get();
        $this->currentStep = 1;
        $this->paymentMethod = PaymentMethod::all();
        $this->terms_and_conditions = Setting::find(1)->terms_and_conditions;
        $this->guest_types = GuestType::all();
    }

    /**
     * Loads company branding info from the branding service.
     */
    protected function loadBranding(): void
    {
        $branding = $this->brandingService->getBrandingData();

        $this->companyName = $branding['branding_company_name'];
        $this->logoPath = $branding['logo_path'];
        $this->companyEmail = $branding['branding_company_email'];
        $this->companyContact = $branding['branding_company_contact'];
        $this->companyAddress = $branding['company_address'];
        $this->facebookLink = $branding['facebook_link'];
        $this->instagramLink = $branding['instagram_link'];
    }
}
