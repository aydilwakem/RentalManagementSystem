<?php

namespace App\Livewire\Guest\Reservation;

use App\Livewire\Admin\Settings\Branding\SettingsBranding;
use App\Livewire\Admin\Settings\Branding\ViewBranding;
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
use App\Models\PropertyBed;
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
use PragmaRX\Countries\Package\Countries;
use App\Helpers\Toast;
use App\Services\PaymentMethodService;

class ReservationForm extends Component
{

    // ----------------------- GENERAL ---------------------------- //
    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source = 'Website';
    public $transaction_status = 'pending';
    public $check_in_date;
    public $check_out_date;
    public $cart = []; // Keeps all the selected rooms and activities
    public $total_amount; // Total amount for the reservation
    public $total_pax = 2; // Total number of guests (adults + kids)
    public $sub_total;
    public $requests;

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
    public $beds;

    // --------------------- ACTIVITIES ------------------------- //

    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];
    public $expandedActivity;

    // ------------------- GUEST DETAIL ------------------------ //
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $countries = [];
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
    public $addPetModal = false;

    //----------------------- BRANDING ------------------------ //
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;
    public $country_code = '+63'; // default for PH

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
    protected PaymentMethodService $paymentMethodService;
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
    public $selectedTimes = [];

    public $editingPetIndex = null;
    public $editingPet = [
        'breed' => '',
    ];

    public $showEditPetModal = false;

    public $services;
    public $expandedService;





    /* ----------------------- BOOT METHOD ------------------------
     *
     * This method is called when the component is booted.
     * It initializes the services needed for the component.
     * -------------------------------------------------------------
     */
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





    /* ----------------------- RENDER METHOD ------------------------
     *
     * This method is called to render the component view.
     * It retrieves available rooms and returns the view.
     * -------------------------------------------------------------
     */

    public function render()
    {
        $this->getAvailableRooms();
        return view('livewire.guest.reservation.reservation-form');
    }





    /* ----------------------- MOUNT METHOD ------------------------
     *
     * This method is called when the component is mounted.
     * It initializes various properties and loads necessary data.
     * -------------------------------------------------------------
     */
    public function mount()
    {
        $this->initializeDates();
        $this->prepareOccupancyRules();
        $this->loadStaticData();
        $this->loadRooms();
        $this->loadBranding();
        $this->initializeCountries();
        $this->loadCountryCode();

        // Load cart and session data
        $this->cart = session()->get('cart', []);
        $this->adults = session()->get('adults', []);
        $this->kids = session()->get('kids', []);
        $this->total_pax = session()->get('total_pax', 0);

        foreach ($this->cart as $index => $item) {
            if ($item['type'] === 'room') {
                $room = Property::find($item['room_id']);
                if (
                    !$room || // room does not exist
                    !$this->isRoomAvailable($room, $this->check_in_date, $this->check_out_date) // no longer available
                ) {
                    unset($this->cart[$index]);
                    unset($this->adults[$item['room_id']]);
                    unset($this->kids[$item['room_id']]);
                }
            }
        }


        $this->cart = array_values($this->cart);

        // Recompute total pax after filtering unavailable rooms
        $this->computeTotalPax();

        // Save cleaned cart back to session
        session([
            'cart' => $this->cart,
            'adults' => $this->adults,
            'kids' => $this->kids,
            'total_pax' => $this->total_pax,
        ]);

        // Populate quantity array for activities/services
        foreach ($this->cart as $item) {
            if ($item['type'] === 'activity') {
                $this->quantity[$item['activity_id']] = $item['quantity'];
            } elseif ($item['type'] === 'service') {
                $this->quantity[$item['service_id']] = $item['quantity'];
            }
        }

        // Load available rooms for display
        $this->getAvailableRooms();
    }



    protected function isRoomAvailable($room, $checkInDate, $checkOutDate)
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        // Check if room has overlapping transactions
        $booked = $room->transactions()
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

        return !$booked; // available if not booked
    }

    /**
     * Remove unavailable rooms from cart based on current check-in/out dates.
     */
    protected function removeUnavailableRooms()
    {
        $removedRoom = false;

        foreach ($this->cart as $index => $item) {
            if ($item['type'] === 'room') {
                $room = Property::find($item['room_id']);
                if (!$room || !$this->isRoomAvailable($room, $this->check_in_date, $this->check_out_date)) {
                    unset($this->cart[$index]);
                    unset($this->adults[$item['room_id']]);
                    unset($this->kids[$item['room_id']]);
                    $removedRoom = true;

                    // Add a notice for the summary tab
                    $this->cartNotices[] = "Room <strong>{$item['room_name']}</strong> is no longer available and has been removed.";
                }
            }
        }

        // Reindex cart array
        $this->cart = array_values($this->cart);

        // Recompute total pax
        $this->computeTotalPax();

        // Save updated cart to session
        session([
            'cart' => $this->cart,
            'adults' => $this->adults,
            'kids' => $this->kids,
            'total_pax' => $this->total_pax,
        ]);

        // Remove dependent activities/services if needed
        if ($removedRoom) {
            $this->currentStep = 1; // Go back to room selection
            $this->cart = array_filter($this->cart, fn($item) => $item['type'] !== 'activity');
            $this->quantity = []; // Reset quantities for activities/services
            session(['cart' => $this->cart]);
        }
    }

    public $cartNotices = [];



    /* ----------------------- MODAL CONTROL ------------------------
     *
     * These methods control the visibility of various modals in the component.
     * They are used to confirm reservation creation, open guest and pet modals.
     * -------------------------------------------------------------
     */

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

    public function openPetModal()
    {
        $this->addPetModal = true;
    }

    public function closePetModal()
    {
        $this->addPetModal = false;
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
            $this->recalculateCart();
            $this->getAvailableRooms();

            // Save adults, kids, and selected room IDs to session
            session([
                'adults' => $this->adults,
                'kids' => $this->kids,
            ]);
        }

        // --------------- CHECK-IN AND CHECK-OUT DATES ------------------- //
        if (in_array($property, ['check_in_date', 'check_out_date'])) {
            // Clear dependent selections
            $this->cart = [];
            $this->currentStep = 1;
            $this->pet_count = 0;
            $this->pets = [];

            if ($property === 'check_in_date') {
                $checkIn = Carbon::parse($this->check_in_date);
                $checkOut = Carbon::parse($this->check_out_date);

                if ($checkOut->lte($checkIn)) {
                    $this->check_out_date = $checkIn->copy()->addDay()->format('Y-m-d');
                }
            }

            $this->getAvailableRooms();
            $this->removePromoCode();
            $this->recalculateCart();

            // Save to session
            session([
                'check_in_date' => $this->check_in_date,
                'check_out_date' => $this->check_out_date,
            ]);
        }
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
     * ---------------------------- NAVIGATION STEPS ----------------------------
     *
     * Handles the navigation between different steps in the reservation process.
     * Increases or decreases the current step while validating data.
     *
     * --------------------------------------------------------------------------
     */

    public function increaseStep()
    {
        $this->resetErrorBag();
        $this->validateData();
        $this->removeUnavailableRooms();
        $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
    }

    public function decreaseStep()
    {
        $this->resetErrorBag();
        $this->removeUnavailableRooms();
        $this->currentStep = max($this->currentStep - 1, 1);
    }



    /**
     * ---------------------------- SPECIAL REQUESTS LOGIC ----------------------------
     *
     * Handles the addition and removal of special requests in the reservation form.
     * Each request has a status that can be updated.
     *
     * Responsibilities:
     * - `addSpecialRequest`: Adds a new special request to the list.
     * - `removeSpecialRequest`: Removes a special request by its index.
     *
     * ----------------------------------------------------------------------------------
     */








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

    protected function getItemsByType(string $type): array
    {
        return array_filter($this->cart, fn($item) => $item['type'] === $type);
    }

    protected function getPetFeeAmount(): float
    {
        $service = Service::where('name', 'Pet Fee')->first();
        return $service?->amount ?? 0;
    }

    public function getMaxPetsAllowedProperty()
    {
        $roomItems = collect($this->getItemsByType('room'));
        return $roomItems->count() * 2;
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
    public function computeTotalPax(): void
    {
        // Step 1: Call a method named `getItemsByType` with the argument 'room'
        // Step 2: Wrap the returned array in a Laravel Collection using `collect()`
        $this->total_pax = collect($this->getItemsByType('room'))

            // Step 3: Use the `reduce()` method to loop through each item in the collection
            // `reduce()` takes a callback function and an initial value (0 in this case)
            ->reduce(function ($carry, $item) {

                // Step 4: Extract the number of adults from the item
                $adults = (int) ($item['adults'] ?? 0);

                // Step 5: Extract the number of kids from the item
                $kids = (int) ($item['kids'] ?? 0);

                // Step 6: Add the number of adults and kids to the accumulator ($carry)
                return $carry + $adults + $kids;
            }, 0); // Step 7: Initialize the accumulator ($carry) to 0

        // Save to session
        session(['total_pax' => $this->total_pax]);
    }

    public function computeTotalAmountOfAllRooms(): float
    {
        // Get all items of type 'room' (e.g., room bookings)
        // Convert the array to a Laravel Collection for easier manipulation
        return collect($this->getItemsByType('room'))
            // Sum the value of the 'total_amount' key for all room items
            ->sum('total_amount');
    }

    public function computeTotalAmountOfAllActivities(): float
    {
        // Get all items of type 'activity' (e.g., booked activities)
        // Convert the array to a Laravel Collection
        return collect($this->getItemsByType('activity'))
            // Sum the value of the 'amount' key for all activity items
            ->sum('amount');
    }

    public function computeTotalAmountOfAllServices(): float
    {
        // Get all items of type 'service' (e.g., requested services like massage, laundry, etc.)
        // Convert the array to a Laravel Collection
        return collect($this->getItemsByType('service'))
            // Sum the value of the 'amount' key for all service items
            ->sum('amount');
    }

    public function computePetTotal(): float
    {
        // Step 1: Get the number of pets
        $petCount = $this->pet_count ?? 0;

        // Step 2: Get the number of days the guests are staying
        $stayDuration = $this->getStayDurationProperty() ?? 0;

        // Step 3: Get the pet fee amount per pet per day
        $feePerPetPerDay = $this->getPetFeeAmount();

        // Step 4: Compute the total pet fee
        return $petCount * $feePerPetPerDay * $stayDuration;
    }


    /**
     * Computes the base subtotal by summing up all individual totals from rooms, activities, services, and pets.
     * With no promo codes, discount, or convenience fees applied.
     *
     * @return float The computed base subtotal.
     */
    public function computeBaseSubtotal()
    {
        // Step 1: Compute total amounts for rooms, activities, services, and pets
        return $this->computeTotalAmountOfAllRooms()
            + $this->computeTotalAmountOfAllActivities()
            + $this->computeTotalAmountOfAllServices()
            + $this->computePetTotal();
    }

    /**
     * Computes the subtotal amount by applying any promo discount to the base subtotal.
     * Handles errors gracefully and ensures numeric values are used.
     *
     * @return float The computed subtotal amount after applying any discounts.
     */
    public function computeSubtotalAmount()
    {
        // Usess current promoDiscount to calclate discounted subtotal
        $baseSubtotal = $this->computeBaseSubtotal();
        $this->sub_total = max(0, $baseSubtotal - $this->promoDiscount);
        return $this->sub_total;
    }

    /**
     * Computes the total amount for the reservation, including:
     * - Subtotal amount.
     * - Convenience fee (if applicable).
     * Handles errors gracefully and ensures numeric values are used.
     *
     * @return float The computed total amount after applying convenience fee.
     */
    public function computeTotalAmount()
    {
        // Step 1: Compute discounted subtotal
        $this->computeSubtotalAmount();

        // Step 2: Compute 3% convenience fee from discounted subtotal
        // Note: Add this to settings
        $this->convenience_fee = $this->sub_total * 0.03;

        // Step 3: Final total = discounted subtotal + convenience fee
        $this->total_amount = max(0, $this->sub_total + $this->convenience_fee);

        return $this->total_amount;
    }

    /**
     * Recomputes the convenience fee based on the current subtotal.
     * This method is called to ensure the convenience fee is always up-to-date
     * with the latest subtotal calculations.
     *
     * @return float The updated convenience fee.
     */
    public function computeConvenienceFee()
    {
        // Recompute to ensure the most current values
        $this->computeTotalAmount();

        return $this->convenience_fee;
    }

    /**
     * Recalculates the cart totals, applying any promo codes and updating the subtotal and total amount.
     * Handles errors gracefully and resets values to avoid broken cart state.
     *
     * This method is called whenever the cart needs to be recalculated,
     * such as when items are added, removed, or promo codes are applied.
     */
    public function recalculateCart()
    {
        $this->promoDiscount = 0;

        $baseSubtotal = $this->computeBaseSubtotal();

        if (!empty($this->promoCode)) {
            $this->applyPromoCode();
        } else {
            $this->sub_total = $baseSubtotal;
        }

        $this->computeTotalAmount();
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

        if (empty($this->promoCode) || !is_string($this->promoCode)) {
            Log::warning(message: 'No valid promo code provided. Skipping promo application.');
            $this->promoDiscount = 0;
            $this->promo_discount_amount = 0;
            $this->discountMessage = null;
            $this->errorMessage = null;
            return;
        }

        $this->reset(['discountMessage', 'errorMessage']);

        // Compute base subtotal (without discount)
        $baseSubtotal = $this->computeBaseSubtotal();

        // Validate promo based on base subtotal
        $response = $this->promoCodeService->validateAndApply(
            $this->promoCode,
            $this->total_amount,
            $baseSubtotal
        );

        if (!$response['success']) {
            return $this->failPromo($response['message']);
        }

        // Apply discount
        $this->promoDiscount = $response['discount'];
        $this->promo_discount_amount = $this->promoDiscount;

        // Compute final discounted subtotal
        $this->sub_total = max(0, $baseSubtotal - $this->promoDiscount);

        $this->discountMessage = $response['message'];
        $this->errorMessage = null;

        $this->getAvailableRooms();
    }

    public function computeSubtotalAfterDiscount(): float
    {
        $subtotal = $this->computeBaseSubtotal();

        $promoDiscount = $this->promoDiscount ?? 0; // promo discount amount

        return max($subtotal - $promoDiscount, 0);
    }

    public function removePromoCode()
    {
        Log::info('removePromoCode called');
        $this->promoCode = '';
        $this->promoDiscount = 0;
        $this->discountMessage = null;
        $this->errorMessage = null;

        $this->recalculateCart();
    }

    private function failPromo(string $message)
    {
        // Set the error message to display to the user
        $this->errorMessage = $message;

        // Clear the promo code since it's invalid
        $this->promoCode = '';

        // Recalculate the cart to reset totals without promo
        $this->recalculateCart();
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

        session()->put('cart', $this->cart);

        $this->computeTotalPax();
        $this->getAvailableRooms();
        $this->recalculateCart();
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
        $context = [];

        if ($type === 'activity') {
            $activity = Activity::findOrFail($itemId);

            if ($activity->schedule_type !== 'no_schedule') {
                $date = $this->check_in_date ?? now()->toDateString();
                $time = $this->selectedTimes[$itemId] ?? null;

                if (!$time) {
                    $this->addError("selectedTimes.$itemId", 'Please select a time for the activity.');
                    return;
                }

                $datetime = Carbon::parse("$date $time")->format('Y-m-d H:i:s');
                $context['activity_datetime'] = $datetime;
            } else {
                // Explicitly set to null if no schedule is required
                $context['activity_datetime'] = null;
                $context['activity_rate'] = $activity->amount;
            }
        }

        if ($this->isItemAlreadyInCart($type, $itemId)) {
            return;
        }

        $newActivitiesCart = $this->cartService->addItem(
            $type,
            $itemId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->paymentStatus,
            $context
        );

        $this->cart = $newActivitiesCart;

        session()->put('cart', $this->cart);
        $this->recalculateCart();
    }




    /**
     * ----------------------------- SERVICE CART LOGIC -----------------------------
     *
     * Handles the addition of services to the cart, ensuring no duplicates
     * and recalculating totals after each addition.
     *
     * ------------------------------------------------------------------------------
     */
    public function addServiceToCart($type, $itemId)
    {

        $newServicesCart = $this->cartService->addItem(
            $type,
            $itemId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->paymentStatus,
        );


        if ($this->isItemAlreadyInCart($type, $itemId)) {
            return;
        }

        $this->cart = $newServicesCart;

        session()->put('cart', $this->cart);
        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }






    /**
     * ----------------------------- CART ITEM QUANTITY LOGIC -----------------------------
     *
     * Handles the incrementing and decrementing of item quantities in the cart.
     * This is used for both activities and services.
     *
     * ------------------------------------------------------------------------------
     */

    public function incrementItemQuantity($type, $itemId)
    {
        // Ensure quantity exists from session/cart
        $this->quantity[$itemId] = $this->quantity[$itemId] ?? 1;

        $this->quantity[$itemId]++;
        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $this->quantity[$itemId]);
        $this->recalculateCart();
    }

    public function decrementItemQuantity($type, $itemId)
    {
        $this->quantity[$itemId] = $this->quantity[$itemId] ?? 1;

        $this->quantity[$itemId] = max(1, $this->quantity[$itemId] - 1);
        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $this->quantity[$itemId]);
        $this->recalculateCart();
    }

    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
    }

    public function toggleServiceDescription($serviceId): void
    {
        $this->expandedService = $this->expandedService === $serviceId ? null : $serviceId;
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
     * ------------------------- GUEST PET MANAGEMENT LOGIC -----------------------------
     *
     * Handles the addition, editing, and deletion of multiple guest pet entries dynamically
     * within a reservation or booking form.
     * -----------------------------------------------------------------------------------
     */

    public function addMultiplePets()
    {

        // Step 0: Check if the maximum number of pets allowed is reached
        if (count($this->pets) >= $this->maxPetsAllowed) {
            session()->flash('error', 'Maximum number of pets reached based on rooms in cart.');
            return;
        }

        // Step 1: Validate the breed input
        $this->validate([
            'breed' => 'required|string|max:255',
        ]);

        // Step 2: Add pet to the pets array
        $this->pets[] = $this->makePetsArray();

        // Step 3: Re-calculate the total number of pets
        $this->pet_count = count($this->pets);
        $this->addPetModal = false;
        // Step 4: Reset the breed input field for the next entry
        $this->reset('breed');

        // Step 6: Recalculate the pet service amount and selected services
        $this->handlePetServiceLogic();

        // Step 7: Update overall cart totals
        $this->recalculateCart();
    }

    public function removeGuestPet($index)
    {
        // Step 1: Validate the index to ensure it exists
        if (isset($this->pets[$index])) {
            unset($this->pets[$index]);
            $this->pets = array_values($this->pets);
            $this->pet_count = count($this->pets);
        }

        // Step 2: Recalculate the pet service amount and selected services
        $this->handlePetServiceLogic();

        // Step 3: Update overall cart totals
        $this->recalculateCart();
    }






    /**
     * Handles the logic for services that involve pets.
     * This method checks if the pet service is selected, calculates the total amount,
     * and updates the pets array based on the quantity.
     */
    protected function handlePetServiceLogic()
    {
        // Only set to true if a pet service is found, otherwise leave it unchanged
        foreach ($this->cart as &$item) {
            if ($item['type'] === 'service' && (int) $item['service_id'] === 1) {
                $this->bringingPets = true;

                $unitAmount = $this->getPetFeeAmount();
                $days = $this->getStayDurationProperty();
                $qty = $item['quantity'] ?? 1;

                $item['amount'] = $unitAmount * $days * $qty;

                $this->pets = [];
                for ($i = 0; $i < $qty; $i++) {
                    $this->pets[] = $this->makePetsArray();
                }

                $this->pet_count = count($this->pets);
                break;
            }
        }

        $this->recalculateCart();
    }

    public function updatedBringingPets($value)
    {
        if (!$value) {
            // Clear pets
            $this->pets = [];
            $this->pet_count = 0;

            // Remove the pet service from the cart
            $this->cart = collect($this->cart)->reject(function ($item) {
                return $item['type'] === 'service' && (int) $item['service_id'] === 1;
            })->values()->toArray();

            // Recalculate totals
            $this->recalculateCart();
        } else {
            // If turned on, re-trigger logic
            $this->handlePetServiceLogic();
        }
    }

    public function editGuestPet($index)
    {
        $this->editingPetIndex = $index;
        $this->editingPet = $this->pets[$index];
        $this->showEditPetModal = true;
    }

    public function updatePet()
    {
        if (!is_null($this->editingPetIndex)) {
            $this->pets[$this->editingPetIndex] = $this->editingPet;
        }

        $this->showEditPetModal = false;
        $this->recalculateCart();
        $this->handlePetServiceLogic();
        $this->reset('editingPetIndex', 'editingPet');
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
        $this->getAvailableRooms();
        $this->recalculateCart();

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

            //Fetch payment method data for email
            $paymentMethods = app(PaymentMethodService::class)->getPaymentMethodsData();

            // Prepare data for confirmation email
            $reservationData = $this->prepareReservationData($transaction, $invoice, $total, $deposit);
            $reservationData['payment_link'] = $paymentLink;

            $reservationData['payment_methods'] = $paymentMethods; // Add payment methods data to be accessed by email
        });

        // Attempt to send confirmation emails
        try {
            $emailService->sendReservationEmails($reservationData);
        } catch (\Exception $e) {
            // If email sending fails, flash error but still continue
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        // Show success flash message
        session()->flash('success', 'Reservation successfully submitted!');

        // Redirect user to payment page if available
        if (!empty($reservationData['payment_link'])) {
            session()->flash('success', 'Reservation submitted. You are being redirected to the payment page.');
            return redirect()->away($reservationData['payment_link']);
        }

        // Clear session data related to the reservation
        session()->forget([
            'cart',
            'promoCode',
            'checkInDate',
            'checkOutDate',
        ]);

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
            'requests' => $this->requests,
        ]);
    }

    protected function createInvoice(Transaction $transaction): Invoice
    {
        $totalAmount = $this->computeTotalAmount();

        return Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_type' => 'Room',
            'base_subtotal' => $totalAmount, // but this is not modifiable
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

            if ($item['type'] === 'service') {
                $this->attachServiceToTransaction($transaction, $item);
            }
        }
    }

    protected function attachActivityToTransaction(Transaction $transaction, array $item): void
    {
        $transaction->activities()->attach($item['activity_id'], [
            'quantity' => $item['quantity'],
            'amount' => $item['amount'],
            'payment_status' => $item['payment_status'],
            'activity_datetime' => $item['activity_datetime'],
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
            'quantity'       => $item['quantity'] ?? 1,
            'amount'         => $item['amount'] ?? 0,
            'payment_status' => $item['payment_status'] ?? 'unpaid',
            'days'           => $item['days'] ?? 1,
        ]);

        Log::info('Attaching service to transaction with data:', $item);
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
                'first_name' => [
                    'required',
                    'string',
                    'regex:/^[A-Za-z\s\-]+$/', //only letters, space, and hyphens
                ],
                'middle_name' =>  [
                    'nullable',
                    'string',
                    'regex:/^[A-Za-z\s\-]+$/',
                ],
                'last_name' => [
                    'required',
                    'string',
                    'regex:/^[A-Za-z\s\-]+$/',
                ],
                'company_name' => [
                    'nullable',
                    'string',
                    'regex:/^[A-Za-z\s\-]+$/',
                ],
                'email' => 'required|email',
                'contact_number' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{11}$/', //11 digits only
                ],
                'country' => 'required|string',
                'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
                'reservation_source' => 'required|in:Website,AirBnb,Facebook Messenger,Instagram,Walk-In,Other',
                'requests' => 'nullable|string|max:255',
                'pets.*.breed' => 'required|string|max:255',
            ]);
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
            'guest_first_name' => [
                'required',
                'string',
                'regex:/^[A-Za-z\s\-]+$/',
            ],
            'guest_middle_name' => [
                'nullable',
                'string',
                'regex:/^[A-Za-z\s\-]+$/',
            ],
            'guest_last_name' => [
                'required',
                'string',
                'regex:/^[A-Za-z\s\-]+$/',
            ],
            'guest_suffix' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Za-z\s\-]+$/',
            ],
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


    /**
     * Prepare the context for a room item being added to the cart.
     * This includes calculating rates, extra charges, and total amounts.
     *
     * @param Property $room The room being added
     * @param int $roomId The ID of the room
     * @return array The context data for the room item
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
            'image'         => $room->images[0] ?? null,
        ];
    }

    /**
     * Prepare the data needed for the reservation confirmation email.
     * This includes guest details, transaction information, and branding.
     *
     * @param Transaction $transaction The transaction object
     * @param Invoice $invoice The invoice object
     * @param float $total The total amount of the reservation
     * @param float $deposit The deposit amount required
     * @return array The prepared data for the reservation email
     */
    protected function prepareReservationData(Transaction $transaction, Invoice $invoice, float $total, float $deposit): array
    {

        $cartItems = [];

        // ------------------ Rooms ------------------
        if (!empty($this->cart)) {
            foreach ($this->cart as $item) {
                if ($item['type'] === 'room') {
                    $cartItems[] = [
                        'type' => 'Room',
                        'name' => $item['room_name'] ?? 'Unknown Room',
                        'quantity' => $item['days'] ?? 1,
                        'adults' => $item['adults'] ?? 1,
                        'kids' => $item['kids'] ?? 0,
                        'rate_name' => $item['roomRateName'] ?? '',
                        'rate' => $item['roomRate'] ?? 0,
                        'extra_charge' => $item['extra_charge_total'] ?? 0,
                        'total_amount' => $item['total_amount'] ?? 0,
                        'payment_status' => $item['payment_status'] ?? 'unpaid',
                    ];
                }
            }
        }

        // ------------------ Activities ------------------
        if (!empty($this->cart)) {
            foreach ($this->cart as $item) {
                if ($item['type'] === 'activity') {
                    $cartItems[] = [
                        'type' => 'Activity',
                        'name' => $item['activity_name'] ?? 'Unknown Activity',
                        'datetime' => $item['activity_datetime'] ?? null,
                        'quantity' => $item['quantity'] ?? 1,
                        'rate' => $item['activity_rate'] ?? 0,
                        'total_amount' => $item['amount'] ?? 0,
                        'payment_status' => $item['payment_status'] ?? 'unpaid',
                        'schedule_type' => $item['activity_schedule_type'] ?? 'no_schedule',
                    ];
                }
            }
        }


        // ------------------ Services ------------------
        if (!empty($this->cart)) {
            foreach ($this->cart as $item) {
                if ($item['type'] === 'service') {
                    $cartItems[] = [
                        'type' => 'Service',
                        'name' => $item['service_name'] ?? 'Unknown Service',
                        'quantity' => $item['quantity'] ?? 1,
                        'rate' => $item['service_rate'] ?? 0,
                        'unit' => $item['service_unit'] ?? null,
                        'total_amount' => $item['amount'] ?? 0,
                        'payment_status' => $item['payment_status'] ?? 'unpaid',
                        'extra_properties' => $item['properties_with_extra_hour'] ?? null,
                    ];
                }
            }
        }

        if (!empty($this->pets)) {
            $unitAmount = $this->getPetFeeAmount();
            $days = $this->getStayDurationProperty();
            $totalPets = count($this->pets);
            $totalAmount = $unitAmount * $days * $totalPets;

            $cartItems[] = [
                'type' => 'Pet',
                'name' => 'Pet Fee',
                'quantity' => $totalPets,
                'total_amount' => $totalAmount,
                'payment_status' => 'unpaid',
            ];
        }

        return [
            'name' => $this->first_name . ' ' . $this->last_name,
            'transaction_number' => $transaction->transaction_number,
            'email' => $this->email,
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $this->check_in_date,
            'check_out' => $this->check_out_date,
            'convenience_fee' => $this->computeConvenienceFee(),
            'base_subtotal' => $this->computeBaseSubtotal(),
            'subtotal' => $this->computeSubtotalAmount(),
            'promo_code' => $this->promoCode,
            'promo_amount' => $this->promoDiscount,
            'total_amount' => $this->computeTotalAmount(),
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
            'cart_items' => $cartItems,   // unified cart for email
            'convenience_Fe'
        ];
    }


    /**
     * Prepare the payload for PayMongo checkout session.
     * This includes metadata, line items, and URLs for success and cancellation.
     *
     * @param int $amountInCentavos The amount to charge in centavos
     * @param Transaction $transaction The transaction object
     * @param Invoice $invoice The invoice object
     * @return array The prepared payload for PayMongo
     */
    protected function preparePaymongoPayload(int $amountInCentavos, $transaction, $invoice): array
    {
        return [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => [
                        'gcash',      // GCash
                        'paymaya',    // Maya / PayMaya
                    ],
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

    /**
     * Create an array representation of the guest data.
     * This is used to store guest information in the guests array.
     *
     * @return array
     */
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

    /**
     * Create an array representation of the pet data.
     * This is used to store pet information in the pets array.
     *
     * @return array
     */
    protected function makePetsArray()
    {
        return [
            'breed' => $this->breed,
        ];
    }

    /**
     * Reset all guest input fields to their default state.
     * This is useful after adding a guest or when clearing the form.
     */
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

    /**
     * Generate a unique invoice number for the transaction.
     * This can be customized further if needed.
     */
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

        session()->put('cart', $this->cart);
    }






    /**
     * ----------------------------- LOADERS -----------------------------
     *
     * Contains methods responsible for loading initial and dynamic data
     * into the reservation form based on user context and current state.
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

        $this->check_in_date = session('check_in_date', $now->format('Y-m-d'));
        $this->check_out_date = session('check_out_date', $now->copy()->addDay()->format('Y-m-d'));
    }

    /**
     * Initializes the list of countries for guest selection.
     */
    public function initializeCountries()
    {
        $this->countries = Countries::all()->pluck('name.common')->sort()->values()->toArray();
        $this->country = 'Philippines';
        $this->guest_country_of_origin = 'Philippines';
    }

    /**
     * Initializes the list of countries for guest selection.
     */
    public function loadCountryCode()
    {
        $this->country_code = '+63';
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
        $this->beds = PropertyBed::all();
        $this->selectedFeatures = [];
        $this->activities = Activity::availableActivities()->get();
        $this->services = Service::availableServices()->get();
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

    /**
     * Loads payment methods from the payment method service.
     */
    // protected function loadPaymentMethoods(): void{
    //     $this->paymentMethods = $this->paymentMethodService->getPaymentMethodsData();
    // }

    /**
     * Loads dynamic terms and conditions from settings.
     */
    protected function loadTerms()
    {
        $settings = ViewBranding::first();
        $this->terms_and_conditions = $settings->terms_and_conditions ?? '';
    }
}
