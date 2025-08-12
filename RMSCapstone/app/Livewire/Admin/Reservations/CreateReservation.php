<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\TransactionUser;
use App\Models\GuestDetail;
use App\Models\GuestPet;
use App\Models\Invoice;
use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSubmittedMail;
use App\Models\PaymentMethod;
use App\Models\GuestType;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceBag;
use App\Services\RoomRateService;
use App\Services\RoomAvailabilityService;
use App\Services\CartService;
use App\Services\BrandingService;
use App\Services\PromoCodeService;
use App\Services\PayMongoService;
use App\Services\EmailService;
use App\Models\Service;
use App\Traits\HasFormattedDates;
use App\Traits\ReservationHelpers;
use PragmaRX\Countries\Package\Countries;

class CreateReservation extends Component
{

    public $reservation_type_id = 2; // This reservation is for Rooms
    public $transaction_number;

    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source;
    public $transaction_status = 'pending';

    // Room related public properties
    public $check_in_date;
    public $check_out_date;
    public $rooms = [];
    public $adults = [];
    public $kids = [];
    public $total_pax = 0;
    public $roomsTotalAmount = [];
    public $extra_guest = [];
    public $extra_charge = []; // extra_guest * extra_person_charge * days
    public $roomAmount = []; // base rate * days
    public $paymentStatus = [];
    public $requests;



    // Activity related public properties

    public $activities = [];
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];
    public $activityImage;
    public $activityDescription;

    // Primary Guest related public properties

    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $country;
    public $heard_from;
    public $terms = 1;

    // Additional Guest related public properties

    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin, $countries;

    public $guest_types = [];
    public $guests = [];
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

    // Invoice related public properties
    public $invoice_number;
    public $paymentMethod;

    // Setting related public properties
    public $expirationHours;


    // Selected Items (Rooms and Activities)
    public $selectedRooms = [];
    public $selectedActivities = [];
    public $selectedServices = [];


    // -------------------- MODALS ------------------------ //
    public $roomModal = false, $activityModal = false, $guestModal = false, $servicesModal = false;
    public $editRoomModal = false, $editActivityModal = false, $editGuestModal = false;
    public $addRoomFirstModal = false;

    // -------------------- PETS ------------------------ //
    public bool $bringingPets = false;
    public int $pet_count;
    public $pets = [];
    public string $breed = '';


    //----------------------- TRAITS -------------------------- //

    use HasFormattedDates;

    use ReservationHelpers;


    // -------------------- SUMMARY TOTALS ------------------------ //
    public $sub_total;
    public $total_amount;
    public $depositPercentage;
    public $enable_deposit_percentage = true;
    public bool $apply_convenience_fee = true;

    // Promo Discount
    public $promo;
    public $promoCode;
    public $discountMessage;
    public $errorMessage;
    public $promoDiscount;
    public $promo_discount_amount;

    // Convenience Fee
    public $convenience_fee;

    //----------------------- BRANDING ------------------------ //
    public string $companyName = 'Company'; //Default
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    //----------------------- EDITING ------------------------ //
    public $editingServiceId;
    public $serviceQuantity;
    public $showEditServiceModal;
    public $editingActivityId;
    public $activityQuantity;
    public $showEditActivityModal;


    //----------------------- CHARGES ------------------------ //
    public $services_charges;


    //----------------------- SERVICES ------------------------ //
    protected ServiceBag $services;
    protected RoomRateService $roomRateService;
    protected CartService $cartService;
    protected RoomAvailabilityService $roomAvailabilityService;
    protected PromoCodeService $promoCodeService;
    protected BrandingService $brandingService;
    protected PayMongoService $payMongo;
    protected EmailService $emailService;

    //----------------------- OTHERS ------------------------ //
    public $expandedService = null;
    public $expandedActivity = null;
    public $dynamicKidOptions = [];
    public $paymentLink;
    public $editingRoomId;
    public $roomTotalAdults;
    public $roomTotalKids;
    public $showEditRoomModal;
    public $roomName;
    public $activityName;
    public $activityDateTime;
    public $special_requests = [
        ['request' => '', 'status' => 'pending'],
    ];
    public $selectedTimes = [];

    public $activityScheduleType; // 'guest' or 'system'



    /* ----------------------- BOOT METHOD ------------------------
     *
     * This method is called when the component is booted.
     * It initializes the services needed for the component.
     * -------------------------------------------------------------
     */
    public function boot(ServiceBag $services): void
    {
        $this->roomRateService = $services->roomRateService;
        $this->cartService = $services->cartService;
        $this->roomAvailabilityService = $services->roomAvailabilityService;
        $this->brandingService = $services->brandingService;
        $this->promoCodeService = $services->promoCodeService;
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
        return view('livewire.admin.reservations.create-reservation');
    }



    /* ----------------------- MOUNT METHOD ------------------------
     *
     * This method is called when the component is mounted.
     * It initializes various properties and loads necessary data.
     * -------------------------------------------------------------
     */
    public function mount(): void
    {
        $this->initializeDates();
        $this->prepareOccupancyRules();
        $this->loadStaticData();
        $this->loadRooms();
        $this->loadBranding();
        $this->getAvailableRooms();
        $this->initializeCountries();
    }


    /**
     * ------------------------ LIVEWIRE HOOK: UPDATED -----------------------
     *
     * Responds to changes in component properties such as:
     * - Adults/Kids: Recalculates valid kid options and pricing.
     * - Dates: Reloads available rooms.
     * ----------------------------------------------------------------------
     */
    public function updated($property): void
    {
        // If adults and kids quantity is updated
        if (Str::startsWith($property, 'adults.') || Str::startsWith($property, 'kids.')) {

            Log::info('Adults and Kids are changed.');

            // Extract room ID from the property name
            $roomId = explode('.', $property)[1];

            // Find the room by ID
            $room = Property::find($roomId);

            // If room is not found, add an error
            if (!$room) {
                $this->addError('selectedRooms', 'Room not found.');
                return;
            }

            // Update dynamic kid options based on the current number of adults
            $this->updateKidOptions($roomId);

            // Update selected room details with new adults and kids counts
            $this->updateSelectedRoomDetails($roomId, $room);

            // Recompute total pax and available rooms
            $this->computeTotalPax();

            // Re-fetch available rooms to reflect changes
            $this->getAvailableRooms();
        }

        // If check-in and check-out dates are updated
        if (in_array($property, ['check_in_date', 'check_out_date'])) {

            Log::info('Dates are changed.');

            // Clears out the selected rooms, activities, and services
            $this->selectedRooms = [];
            $this->selectedActivities = [];
            $this->selectedServices = [];

            // Reset pet-related properties
            $this->pet_count = 0;
            $this->pets = [];

            // Re-fetch available rooms based on new dates
            $this->getAvailableRooms();

            // Reset promo code 
            $this->removePromoCode();

            // Recalculate cart totals
            $this->computeSubtotalAmount();
            $this->computeTotalAmount();
        }
    }

    /**
     * Updates the convenience fee based on the current subtotal.
     * Recalculates the total amount after applying the convenience fee.
     *
     * This method is called when the convenience fee toggle is changed.
     */
    public function updateApplyConvenienceFee()
    {
        $this->recalculateCart();
    }


    /**
     * ----------------------------- MODALS -----------------------------
     *
     * Opens specific modals based on the provided type:
     * - 'room'     : Opens the room selection modal.
     * - 'activity' : If no room is selected, opens 'add room first' modal;
     *                otherwise, opens the activity modal.
     * - 'guest'    : Opens the guest details modal.
     *
     * Logs a warning if the modal type is unrecognized.
     * ------------------------------------------------------------------
     */
    public function openModal(string $type): void
    {
        $this->resetErrorBag();

        if ($type === 'room') {
            $this->roomModal = true;
        } elseif ($type === 'activity') {
            if (empty($this->selectedRooms)) {
                $this->addRoomFirstModal = true;
            } else {
                $this->activityModal = true;
            }
        } elseif ($type === 'guest') {
            $this->guestModal = true;
        } elseif ($type === 'services') {
            if (empty($this->selectedRooms)) {
                $this->addRoomFirstModal = true;
            } else {
                $this->servicesModal = true;
            }
        } else {
            Log::warning("Unknown modal type: $type");
        }
    }













    /**
     * ----------------------------- CART COMPUTATION LOGIC -----------------------------
     *
     * Handles the computation of cart-related totals including:
     * - Pax count (adults + kids) from room items.
     * - Total amounts for rooms, activities, and pets.
     * - Subtotal with promo discount applied.
     * - Final total amount including a 3% convenience fee.
     *
     * Key Methods:
     * - `computeTotalPax`: Sums all guests across room items.
     * - `computeTotalAmountOfAllRooms/Activities/Pets`: Calculates respective totals.
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
        try {
            // Step 1: Compute base subtotal safely
            if (!method_exists($this, 'computeBaseSubtotal')) {
                throw new \Exception('computeBaseSubtotal() not defined.');
            }

            $baseSubtotal = $this->computeBaseSubtotal();
            $baseSubtotal = is_numeric($baseSubtotal) ? floatval($baseSubtotal) : 0;

            // Step 2: Ensure promoDiscount is numeric
            $promo = isset($this->promoDiscount) && is_numeric($this->promoDiscount)
                ? floatval($this->promoDiscount)
                : 0;

            // Step 3: Compute discounted subtotal
            $this->sub_total = max(0, $baseSubtotal - $promo);

            return $this->sub_total;
        } catch (\Throwable $e) {
            Log::error('Error computing sub total: ' . $e->getMessage());

            $this->sub_total = 0;
            return 0;
        }
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
        try {
            if (method_exists($this, 'computeSubtotalAmount')) {
                $this->computeSubtotalAmount();
            } else {
                throw new \Exception('computeSubtotalAmount() method not found.');
            }

            $this->sub_total = is_numeric($this->sub_total) ? $this->sub_total : 0;

            // Only apply convenience fee if toggled ON
            $this->convenience_fee = $this->apply_convenience_fee
                ? round($this->sub_total * 0.03, 2)
                : 0;

            $this->total_amount = max(0, $this->sub_total + $this->convenience_fee);

            return $this->total_amount;
        } catch (\Throwable $e) {
            Log::error('Error in computeTotalAmount: ' . $e->getMessage());

            $this->sub_total = 0;
            $this->convenience_fee = 0;
            $this->total_amount = 0;

            session()->flash('error', 'Something went wrong while computing the total amount.');

            return 0;
        }
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
        try {
            // Clears any existing discount
            $this->promoDiscount = 0;

            // Make sure the computeBaseSubtotal method exists
            if (!method_exists($this, 'computeBaseSubtotal')) {
                throw new \Exception('computeBaseSubtotal() method not found.');
            }

            $baseSubtotal = $this->computeBaseSubtotal();
            $baseSubtotal = is_numeric($baseSubtotal) ? floatval($baseSubtotal) : 0;

            if (!empty($this->promoCode)) {
                // Apply the promo code if the method is available
                if (method_exists($this, 'applyPromoCode')) {
                    $this->applyPromoCode();
                } else {
                    Log::warning('applyPromoCode() not found, skipping promo logic.');
                }
            } else {
                // No promo code, just use the base subtotal
                $this->sub_total = $baseSubtotal;
            }

            // Recalculates total amount with all adjustments
            $this->computeTotalAmount();
        } catch (\Throwable $e) {
            // Log the error for developer visibility
            Log::error('Error recalculating cart: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'promoCode' => $this->promoCode ?? null,
            ]);

            // Resets to safe values to avoid broken cart state
            $this->promoDiscount = 0;
            $this->sub_total = 0;
            $this->total_amount = 0;

            // Show suser-friendly error message
            session()->flash('error', 'We had a problem recalculating your cart. Please try again.');
        }
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


    /**
     * Applies the promo code entered by the user.
     * Validates the promo code, checks minimum booking amount, date range,
     * usage limits, and applies the discount if valid.
     *
     * @return void
     */
    public function applyPromoCode()
    {
        // Log that the method has been triggered, including the entered promo code
        Log::info('Apply Promo Code method called with promoCode: ' . $this->promoCode);

        // Step 1: Check if the promo code is empty or not a string
        // If it's invalid, reset all related discount values and exit early
        if (empty($this->promoCode) || !is_string($this->promoCode)) {
            Log::warning(message: 'No valid promo code provided. Skipping promo application.');

            // Set all discount-related fields to 0 or null
            $this->promoDiscount = 0;
            $this->promo_discount_amount = 0;
            $this->discountMessage = null;
            $this->errorMessage = null;
            return;
        }

        // Step 2: Reset any previous promo messages
        $this->reset(['discountMessage', 'errorMessage']);

        // Step 3: Compute the base subtotal before applying any discounts
        $baseSubtotal = $this->computeBaseSubtotal();

        // Step 4: Call the promo code service to validate and apply the promo code
        // Pass in the promo code, total amount, and base subtotal
        $response = $this->promoCodeService->validateAndApply(
            $this->promoCode,
            $this->total_amount,
            $baseSubtotal
        );

        // Step 5: If promo validation fails, handle the error and exit
        if (!isset($response['success']) || !$response['success']) {
            return $this->failPromo($response['message'] ?? 'Invalid promo code.');
        }

        // Step 6: Apply the discount to the booking
        $this->promoDiscount = $response['discount'];
        $this->promo_discount_amount = $this->promoDiscount;

        // Step 7: Calculate the new subtotal after applying the discount
        // Ensure it doesn't go below 0
        $this->sub_total = max(0, $baseSubtotal - $this->promoDiscount);

        // Step 8: Show the user a success message, and clear any error messages
        $this->discountMessage = $response['message'];
        $this->errorMessage = null;

        // Step 9: Refresh the available rooms list, possibly affected by promo logic
        $this->getAvailableRooms();
    }


    /**
     * Removes the applied promo code, resetting all related discount values.
     * This method is called when the user decides to clear the promo code input.
     */
    public function removePromoCode()
    {
        Log::info('removePromoCode called');
        $this->promoCode = '';
        $this->promoDiscount = 0;
        $this->discountMessage = null;
        $this->errorMessage = null;

        $this->recalculateCart();
    }

    /**
     * Fallback handler for invalid promo codes.
     * Resets all discount-related values and sets an error message.
     *
     * @param string $message The error message to display to the user.
     */
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

    public function SelectedRooms($roomId)
    {

        // Resets any previous error messages
        $this->resetErrorBag();

        // Checks if the dates are valid or existing
        if (!$this->checkInOutDatesAreValid()) {
            $this->addError('cart', 'Please select check-in and check-out dates before adding a room.');
            return;
        }

        // Find the room using the provided roomId, or fail if it doesn't exist
        $room = Property::findOrFail($roomId);

        // Checks if the item is already in the cart
        if ($this->isItemAlreadyInCart('room', $roomId)) {
            $this->addError('selectedRooms', 'This room is already in the cart.');
            return;
        }

        $context = $this->prepareRoomCartContext($room, $roomId);
        Log::info('Prepared context for cart item:', $context);

        $added = $this->cartService->addItem(
            'room',
            $roomId,
            $this->selectedRooms,
            $this->quantity,
            $this->status,
            $this->paymentStatus,
            $context
        );


        if (!$added) {
            $this->addError('selectedRooms', 'Failed to add room to cart.');
            return;
        }

        $this->computeTotalPax();
        $this->getAvailableRooms();
        $this->recalculateCart();
    }
    public function editSelectedRoom($roomId)
    {
        Log::info("Edit Room method called for room ID: {$roomId}");

        $room = collect($this->selectedRooms)->firstWhere('room_id', $roomId);

        if (!$room) {
            Log::warning("Room ID {$roomId} not found in selectedRooms.");
            return;
        }

        $this->editingRoomId = $room['room_id'];
        $this->roomName = $room['room_name'] ?? 'Room';
        $this->roomTotalAdults = $room['adults'] ?? 0;
        $this->roomTotalKids = $room['kids'] ?? 0;
        $this->showEditRoomModal = true;
    }
    public function incrementAdults()
    {
        $this->roomTotalAdults++;
    }
    public function decrementAdults()
    {
        $this->roomTotalAdults = max(1, $this->roomTotalAdults - 1);
    }
    public function incrementKids()
    {
        $this->roomTotalKids++;
    }
    public function decrementKids()
    {
        $this->roomTotalKids = max(0, $this->roomTotalKids - 1);
    }
    public function updateRoom()
    {
        foreach ($this->selectedRooms as &$room) {
            if ($room['room_id'] == $this->editingRoomId) {

                // Fetch rate and charges
                $roomRate = $room['roomRate'] ?? 0;
                $days = $room['days'] ?? 1;
                $extraChargePerGuestPerDay = $room['extra_charge'] ?? 0;


                // Set number of guests
                $room['adults'] = $this->roomTotalAdults;
                $room['kids'] = $this->roomTotalKids;

                // Calculate guest counts
                $totalGuests = $this->roomTotalAdults + $this->roomTotalKids;
                $includedGuests = $room['included_guests'] ?? 2;
                $extraGuests = max(0, $totalGuests - $includedGuests);
                $room['extra_guest'] = $extraGuests;


                // Calculate totals
                $room['roomAmount'] = $roomRate * $days;
                $room['extra_charge_total'] = $extraGuests * $extraChargePerGuestPerDay * $days;
                $room['total_amount'] =  $room['roomAmount'] + $room['extra_charge_total'];

                // Dump the updated room for debugging
                // dd($room);

                break;
            }
        }


        $this->selectedRooms = array_values($this->selectedRooms);
        $this->showEditRoomModal = false;

        $this->editingRoomId = null;
        $this->roomTotalAdults = 0;
        $this->roomTotalKids = 0;
        $this->computeTotalPax();
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

    /**
     * Adds a selected activity to the cart.
     * Validates the activity, checks for duplicates, and updates the cart state.
     * @param int $activityId The ID of the activity to add.
     * @return void
     */
    public function SelectedActivities($activityId)
    {
        // Reset previous error messages
        $this->resetErrorBag();

        $activity = Activity::findOrFail($activityId);

        // Check if activity requires a time
        if ($activity->schedule_type !== 'no_schedule') {
            $date = $this->check_in_date ?? now()->toDateString();
            $time = $this->selectedTimes[$activityId] ?? null;

            if (!$time) {
                $this->addError("selectedTimes.$activityId", 'Please select a time for the activity.');
                return;
            }

            $datetime = Carbon::parse("$date $time")->format('Y-m-d H:i:s');
            $context['activity_datetime'] = $datetime;
        } else {
            $context['activity_datetime'] = null;
        }

        // Check if already in cart
        if ($this->isItemAlreadyInCart('activity', $activityId)) {
            $this->addError('selectedActivities', 'This item is already in the cart.');
            return;
        }

        // Add to cart
        $newActivityCart = $this->cartService->addItem(
            'activity',
            $activityId,
            $this->selectedActivities,
            $this->quantity,
            $this->status,
            $this->paymentStatus,
            $context
        );

        // Update state
        $this->selectedActivities = $newActivityCart;
        $this->recalculateCart();
    }


    /**
     * Increments or decrements the quantity of a selected activity.
     * This method is called when the user clicks the increment or decrement buttons
     * in the activity modal.
     *
     * @param int $activityId The ID of the activity being modified.
     */
    public function incrementActivity($activityId)
    {

        if (!isset($this->quantity[$activityId])) {
            $this->quantity[$activityId] = 1;
        }

        $newQuantity = $this->quantity[$activityId] + 1;
        $this->quantity[$activityId] = $newQuantity;

        $this->selectedActivities = $this->cartService->updateQuantity('activity', $this->selectedActivities, $activityId, $newQuantity);
        $this->recalculateCart();
    }
    public function decrementActivity($activityId)
    {
        if (!isset($this->quantity[$activityId])) {
            $this->quantity[$activityId] = 1;
        }

        $newQuantity = max(1, $this->quantity[$activityId] - 1);
        $this->quantity[$activityId] = $newQuantity;

        $this->selectedActivities = $this->cartService->updateQuantity('activity', $this->selectedActivities, $activityId, $newQuantity);
        $this->recalculateCart();
    }




    /**
     * Opens the edit modal for a selected activity already IN CART.
     * Sets the editingActivityId and initializes activity details for editing.
     *
     * @param int $activityId The ID of the activity to edit.
     */
    public function editSelectedActivity($activityId)
    {
        Log::info("Edit Activity method called for activity ID: {$activityId}");

        $activity = collect($this->selectedActivities)->firstWhere('activity_id', $activityId);

        if (!$activity) {
            Log::warning("Activity ID {$activityId} not found in selectedActivities.");
            return;
        }

        Log::info('Activity details:', [
            'activity_id' => $activity['activity_id'],
            'quantity' => $activity['quantity'] ?? 1,
            'activity_name' => $activity['activity_name'] ?? 'Unknown Activity',
            'activity_schedule_type' => $activity['activity_schedule_type'] ?? 'guest',
            'activity_datetime' => $activity['activity_datetime'] ?? null,
        ]);

        $this->editingActivityId = $activity['activity_id'];
        $this->activityQuantity = $activity['quantity'] ?? 1;
        $this->activityName = $activity['activity_name'] ?? 1;
        $this->activityScheduleType = $activity['activity_schedule_type'] ?? 'guest';

        // Handle availableTimes for "system"
        if ($this->activityScheduleType === 'system') {
            $this->availableTimes = $activity['available_times'] ?? [];
        } else {
            $this->availableTimes = [];
        }


        // If activity datetime is set, parse it to show in the modal
        $this->activityDateTime = isset($activity['activity_datetime'])
            ? \Carbon\Carbon::parse($activity['activity_datetime'])->format('H:i')
            : null;

        $this->showEditActivityModal = true;
    }


    public $availableTimes = []; // Holds available times for system-scheduled activities


    /**
     * Increments or decrements the quantity of a selected activity already IN CART.
     * This method is called when the user clicks the increment or decrement buttons
     * in the edit activity modal.
     *
     * @param int $activityId The ID of the activity being edited.
     */
    public function incrementSelectedActivity($activityId)
    {
        if ($this->editingActivityId != $activityId) return;

        $this->activityQuantity++;
    }
    public function decrementSelectedActivity($activityId)
    {
        if ($this->editingActivityId != $activityId) return;

        $this->activityQuantity = max(1, $this->activityQuantity - 1);
    }


    /**
     * Updates the selected activity in the cart with the new quantity.
     * Recalculates the total amount for the activity based on the new quantity.
     * Closes the edit modal and resets editing properties.
     */
    public function updateActivity()
    {
        foreach ($this->selectedActivities as &$activity) {
            if ($activity['activity_id'] == $this->editingActivityId) {
                $activity['quantity'] = $this->activityQuantity;
                $activity['amount'] = $activity['activity_rate'] * $this->activityQuantity;

                if ($this->activityScheduleType === 'no_schedule') {
                    $activity['activity_datetime'] = null;
                } else {
                    // Only set if a datetime is provided
                    if (!is_null($this->activityDateTime)) {
                        $activity['activity_datetime'] = $this->activityDateTime;
                    }
                }

                break;
            }
        }

        $this->selectedActivities = array_values($this->selectedActivities);

        $this->showEditActivityModal = false;
        $this->editingActivityId = null;
        $this->activityQuantity = 1;
        $this->activityDateTime = null;

        $this->recalculateCart();
    }









    /**
     * ----------------------------- SERVICE CART LOGIC -----------------------------
     *
     * Handles adding and managing quantities of activities (or other non-room items)
     * in the reservation cart.
     *
     * ------------------------------------------------------------------------------
     */

    /**
     * Adds a selected service to the cart.
     * Validates the service, checks for duplicates, and updates the cart state.
     *  @param int $serviceId The ID of the service to add.                         
     * * @return void
     */
    public function SelectedServices($serviceId)
    {
        $this->resetErrorBag();

        if ($this->isItemAlreadyInCart('service', $serviceId)) {
            $this->addError('selectedServices', 'This item is already in the cart.');
            return;
        }

        $newServicesCart = $this->cartService->addItem(
            'service',
            $serviceId,
            $this->selectedServices,
            $this->quantity,
            $this->status,
            $this->paymentStatus
        );

        $this->selectedServices = $newServicesCart;

        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }

    /**
     * Increments or decrements the quantity of a selected service.
     * This method is called when the user clicks the increment or decrement buttons
     * in the service modal.
     *
     * @param int $serviceId The ID of the service being modified.
     */
    public function incrementService($serviceId)
    {
        if (!isset($this->quantity[$serviceId])) {
            $this->quantity[$serviceId] = 1;
        }

        $newQuantity = $this->quantity[$serviceId] + 1;
        $this->quantity[$serviceId] = $newQuantity;

        $this->selectedServices = $this->cartService->updateQuantity('service', $this->selectedServices, $serviceId, $newQuantity);

        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }
    public function decrementService($serviceId)
    {
        if (!isset($this->quantity[$serviceId])) {
            $this->quantity[$serviceId] = 1;
        }

        $newQuantity = max(1, $this->quantity[$serviceId] - 1);
        $this->quantity[$serviceId] = $newQuantity;

        $this->selectedServices = $this->cartService->updateQuantity('service', $this->selectedServices, $serviceId, $newQuantity);

        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }

    /**
     * Handles the logic for services that involve pets.
     * This method checks if the pet service is selected, calculates the total amount,
     * and updates the pets array based on the quantity.
     */
    protected function handlePetServiceLogic()
    {
        $this->bringingPets = false;

        foreach ($this->selectedServices as &$service) {
            if ((int) $service['service_id'] === 1) {
                $this->bringingPets = true;

                // Calculate new amount = unit price * days * quantity
                $unitAmount = $this->getPetFeeAmount();
                $days = $this->getStayDurationProperty();
                $qty = $service['quantity'] ?? 1;

                $service['amount'] = $unitAmount * $days * $qty;

                // Reset pets array based on quantity
                $this->pets = [];
                for ($i = 0; $i < $qty; $i++) {
                    $this->pets[] = $this->makePetsArray();
                }

                $this->pet_count = count($this->pets);
                break; // stop loop once pet fee is found
            }
        }

        $this->recalculateCart();
    }



    /**
     * Opens the edit modal for a selected service already IN CART.
     * Sets the editingServiceId and initializes service details for editing.
     *
     * @param int $serviceId The ID of the service to edit.
     */
    public function editSelectedService($serviceId)
    {
        Log::info("Edit Service method called for service ID: {$serviceId}");

        $service = collect($this->selectedServices)->firstWhere('service_id', $serviceId);

        if (!$service) {
            Log::warning("Service ID {$serviceId} not found in selectedServices.");
            return;
        }

        // Assign to component properties for editing modal
        $this->editingServiceId = $service['service_id'];
        $this->serviceQuantity = $service['quantity'] ?? 1;
        $this->showEditServiceModal = true;
    }


    /**
     * Increments or decrements the quantity of a selected service already IN CART.
     * This method is called when the user clicks the increment or decrement buttons
     * in the edit service modal.
     *
     * @param int $serviceId The ID of the service being edited.
     */
    public function incrementSelectedService($serviceId)
    {
        if ($this->editingServiceId != $serviceId) return;

        $this->serviceQuantity++;
        $this->handlePetServiceLogic();
    }
    public function decrementSelectedService($serviceId)
    {
        if ($this->editingServiceId != $serviceId) return;

        $this->serviceQuantity = max(1, $this->serviceQuantity - 1);
        $this->handlePetServiceLogic();
    }

    /**
     * Updates the selected service in the cart with the new quantity.
     * Recalculates the total amount for the service based on the new quantity.
     * Closes the edit modal and resets editing properties.
     */
    public function updateService()
    {
        foreach ($this->selectedServices as &$service) {
            if ($service['service_id'] == $this->editingServiceId) {
                $service['quantity'] = $this->serviceQuantity;
                $service['amount'] = $service['service_rate'] * $this->serviceQuantity;
                break;
            }
        }

        $this->selectedServices = array_values($this->selectedServices); // Trigger reactivity

        $this->showEditServiceModal = false;
        $this->editingServiceId = null;
        $this->serviceQuantity = 1;
        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }






    /**
     * -------------------------------- REMOVE ITEM FROM CART --------------------------------
     *
     * Handles the removal of a specific item (room or activity) from the reservation cart.
     * ----------------------------------------------------------------------------------------
     */

    public function RemoveActivity($activityId)
    {
        $this->selectedActivities = $this->cartService->removeItem('activity', $activityId, $this->selectedActivities);
        $this->recalculateCart();
    }

    public function RemoveRoom($roomId)
    {
        $this->selectedRooms = $this->cartService->removeItem('room', $roomId, $this->selectedRooms);
        $this->computeTotalPax();
        $this->recalculateCart();
    }

    public function RemoveService($serviceId)
    {
        $this->selectedServices = $this->cartService->removeItem('service', $serviceId, $this->selectedServices);
        $this->handlePetServiceLogic();
        $this->recalculateCart();
    }






    /**
     * ----------------------------- SPECIAL REQUESTS LOGIC -----------------------------
     *
     * Handles the addition and removal of special requests within a reservation or booking form.
     * This allows users to input specific requests that may not fit into standard fields.
     *
     * Key Methods:
     * - `addSpecialRequest`: Adds a new special request entry.
     * - `removeSpecialRequest`: Removes a special request by index.
     *
     * -----------------------------------------------------------------------------------
     */
    public function addSpecialRequest()
    {
        $this->special_requests[] = ['request' => '', 'status' => 'pending'];
    }

    public function removeSpecialRequest($index)
    {
        unset($this->special_requests[$index]);
        $this->special_requests = array_values($this->special_requests); // reindex
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
        $this->resetGuestInputFields();
        $this->guestModal = false;
    }
    public function editGuest($index)
    {
        $this->editingGuestIndex = $index;
        $this->editingGuest = $this->guests[$index];
        $this->editGuestModal = true;
    }
    public function updateGuest()
    {
        if (!is_null($this->editingGuestIndex)) {
            $this->guests[$this->editingGuestIndex] = $this->editingGuest;
        }

        $this->editGuestModal = false;
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
        // Step 1: Validate the breed input
        $this->validate([
            'breed' => 'required|string|max:255',
        ]);

        // Step 2: Add pet to the pets array
        $this->pets[] = $this->makePetsArray();

        // Step 3: Re-calculate the total number of pets
        $this->pet_count = count($this->pets);

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
     * ----------------------------- RESERVATION WORKFLOW LOGIC -----------------------------


     *
     * Handles the complete process of registering a guest reservation, from user creation
     * to payment session generation and confirmation email dispatch.
     *
     * ---------------------------------------------------------------------------------------
     */
    public function CreateReservation(PayMongoService $payMongo, EmailService $emailService)
    {

        Log::info('CreateReservation method called with data:');

        // Step 1: Reset any previous error messages
        $this->resetErrorBag();

        // Step 2: Validate the reservation data before proceeding
        $this->validateData();

        // Step 3: Initialize transaction variable to null
        $transaction = null;

        // Step 4: Prepare an array to hold reservation data
        $reservationData = [];

        // Step 5: Wrap the reservation creation logic in a database transaction
        DB::transaction(function () use (&$reservationData, $payMongo, $emailService) {

            Log::info('Starting reservation creation transaction...');

            // Step 6: Prepare settings and configurations
            Log::info('Fetching settings for deposit percentage and payment proof expiration...');
            $setting = Setting::first();

            // Step 7: Set the deposit percentage based on settings or default to 0
            $this->depositPercentage = $setting && $setting->enable_deposit_percentage
                ? $setting->deposit_percentage
                : 0;

            // Step 8: Set the payment proof expiration hours based on settings or default to 24
            $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24;

            // Step 9: Check if a valid promo code was entered
            $promo = PromoCode::where('code', $this->promoCode)->first();

            // Step 10: Prepare the transaction user and transaction details
            $transactionUser = $this->createTransactionUser();
            $transaction = $this->createTransaction($transactionUser, $promo);
            $invoice = $this->createInvoice($transaction);
            $this->attachCartItemsToTransaction($transaction);
            $this->insertGuestDetails($transaction);
            $this->insertGuestPetDetails($transaction);

            // Step 11: Prepare the total amount for the payment link
            $baseAmount = $this->depositPercentage > 0
                ? $this->computeTotalAmount() * ($this->depositPercentage / 100)
                : $this->computeTotalAmount();

            // Step 12: Convert the base amount to centavos for PayMongo
            $amountInCentavos = intval($baseAmount * 100);

            // Step 13: Prepare the PayMongo payload for creating a checkout session
            $payload = $this->preparePayMongoPayload($amountInCentavos, $transaction, $invoice);

            try {
                // Step 14: Attempt to create a checkout session with PayMongo
                $response = $payMongo->createCheckoutSession($payload);

                // Step 15: Check if the response contains a valid checkout URL
                $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

                // Step 16: If a payment link was successfully created, update the transaction
                if ($paymentLink) {
                    $transaction->update(['payment_link' => $paymentLink]);
                }

                // Step 17: Check if a promo code was applied and increment its usage count
                if ($promo) {
                    $promo->increment('uses_count');
                }
            } catch (\Exception $e) {
                // Step 18: If PayMongo link creation fails, log the error and set payment link to null
                Log::error('PayMongo link creation failed: ' . $e->getMessage());
                $paymentLink = null;
            }

            // Step 19: Compute the total amount and deposit based on the deposit percentage
            $total = $this->computeTotalAmount();

            // Step 20: Calculate the deposit amount based on the total and deposit percentage
            $deposit = $total * ($this->depositPercentage / 100);

            // Step 21: Prepare the reservation data array with all necessary details
            $reservationData = $this->prepareReservationData($transaction, $invoice, $total, $deposit);

            // Step 22: Add the payment link to the reservation data
            $reservationData['payment_link'] = $paymentLink;
        });

        // Step 23: If the transaction was successfully created, proceed to send emails
        try {
            $emailService->sendReservationEmails($reservationData);
        } catch (\Exception $e) {
            // If email sending fails, flash error but still continue
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        // Step 24: If the transaction was not created, flash an error message and redirect
        if (!$transaction) {
            session()->flash('error', 'Something went wrong while creating reservation.');
            return redirect()->route('admin.reservations-list');
        }

        // Step 25: Flash a success message and redirect to the reservation view page
        session()->flash('success', 'Reservation successfully created!');
        return redirect()->route('admin.view-reservation', ['transaction' => $transaction->id]);
    }



    /**
     * ----------------------------- HELPERS -----------------------------
     *
     * Contains utility methods that assist in preparing and transforming
     * data for better usability and accuracy within the reservation process.
     * -------------------------------------------------------------------
     */

    public function prepareOccupancyRules()
    {
        // Ensure rooms are available before processing occupancy rules
        if (empty($this->rooms)) {
            Log::warning('No rooms available to prepare occupancy rules.');
            return;
        }

        // Loop through each room to prepare occupancy rules
        foreach ($this->rooms as $room) {

            switch ($room->occupancy_type) {

                // Combinations: Use unique adult and kid values from occupancy rules
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

                // Whole number: Use a range from 0 to max_guests
                case 'whole_number':
                    $range = range(0, $room->max_guests);

                    // Adults: only values >= 1
                    $room->availableAdultOptions = collect($range)->filter(fn($v) => $v > 0)->values();

                    // Kids: allow from 0
                    $room->availableKidOptions = collect($range);
                    break;

                // Ideal guest: Adults and kids range up to ideal_guest
                case 'ideal_guest':
                    // Adults and kids range up to ideal_guest
                    $room->availableAdultOptions = collect(range(0, $room->ideal_guest));
                    $room->availableKidOptions = collect(range(0, $room->ideal_guest));
                    break;

                // Default case: If occupancy type is unknown, set empty options
                default:
                    $room->availableAdultOptions = collect();
                    $room->availableKidOptions = collect();
            }
        }
    }

    protected function prepareRoomCartContext($room, $roomId): array
    {
        // ------------------ FETCH STATIC VALUES ----------------------- //

        // Step 1: Fetch the stay duration property
        $stayDuration = $this->getStayDurationProperty();

        // Step 2: Fetch the ideal guests for the room
        $included_guests = $room->ideal_guest;

        // Step 3: Fetch the dynamic rate for the room
        $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);

        if (!$rate) {
            Log::error("No valid rate found for room ID {$roomId}.");
            throw new \Exception("No valid rate found for room ID {$roomId}.");
        }

        // Step 4: Fetch the room rate amount
        $roomRate =  $rate['amount'];

        // Step 5: Fetch the extra charge per person for the room
        $extraCharge = $room->extra_person_charge;

        // --------------------- ASSIGN VALUES ----------------------- //

        // Step 6: Assign adults and kids from the component properties
        $adults = (int) ($this->adults[$roomId] ?? 1);
        $kids = (int) ($this->kids[$roomId] ?? 0);

        // Step 7: Assign total pax as the sum of adults and kids
        $total_pax = $adults + $kids;

        // Step 8: Calculate extra guests beyond the included guests
        $extraGuests = max(0, $total_pax - $included_guests);

        // Step 9: Assign the room amount based on the rate and stay duration
        $roomAmount = $roomRate * $stayDuration;

        // Step 10: Calculate the total extra charge based on the extra guests, extra charge per person, and stay duration
        $extraChargeTotal = $extraCharge * $extraGuests * $stayDuration;

        // --------------------- RETURN CONTEXT ----------------------- //
        // Step 11: Return the prepared context array for the cart item
        return [
            'room_name'     => $room->name_number,
            'days'          => $stayDuration,
            'adults'        => $adults,
            'kids'          => $kids,
            'included_guests'  => $included_guests,
            'extra_guest'   => $extraGuests,
            'rate_id'       => $rate['rate_id'],
            'roomRateName'  => $rate['name'],
            'roomRate' => $roomRate,
            'extra_charge'  => $extraCharge,
            'roomAmount'    => $roomAmount,
            'extra_charge_total'  => $extraChargeTotal,
            'total_amount'  => $roomAmount + $extraChargeTotal,
        ];
    }

    protected function preparePaymongoPayload(int $amountInCentavos, $transaction, $invoice): array
    {
        // Prepare the payload for PayMongo checkout session
        Log::info('Preparing PayMongo payload for checkout session.');
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

    public function updateKidOptions($roomId): void
    {
        $room = collect($this->rooms)->firstWhere('id', $roomId);
        if (!$room) return;

        $selectedAdults = $this->adults[$roomId] ?? 0;

        $this->dynamicKidOptions[$roomId] = match ($room->occupancy_type) {
            'whole_number', 'ideal_guest' => $this->generateWholeOrIdealKidOptions($room, $selectedAdults, $roomId),
            'combinations'                => $this->generateCombinationKidOptions($room, $selectedAdults, $roomId),
            default                       => throw new \Exception("Invalid occupancy type: {$room->occupancy_type}"),
        };
    }

    protected function generateWholeOrIdealKidOptions($room, int $adults, $roomId): array
    {
        $maxGuests = $room->max_guests ?? $room->ideal_guest ?? 0;
        $remaining = max(0, $maxGuests - $adults);
        $this->resetIfExceedsLimit($roomId, $remaining);
        return range(0, $remaining);
    }

    protected function generateCombinationKidOptions($room, int $adults, $roomId): array
    {
        $validCombos = collect($room->occupancy_rules ?? [])->where('adults', $adults);
        $options = $validCombos->pluck('kids')->unique()->sort()->values()->all();

        if (!in_array($this->kids[$roomId] ?? 0, $options)) {
            $this->kids[$roomId] = 0;
        }

        return $options;
    }

    protected function resetIfExceedsLimit($roomId, int $limit): void
    {
        if (($this->kids[$roomId] ?? 0) > $limit) {
            $this->kids[$roomId] = 0;
        }
    }

    protected function updateSelectedRoomDetails($roomId, $room): void
    {
        foreach ($this->selectedRooms as $index => $item) {
            if ($item['room_id'] == $roomId) {
                $adults = (int) ($this->adults[$roomId] ?? 1);
                $kids = (int) ($this->kids[$roomId] ?? 0);
                $stayDuration = $this->getStayDurationProperty();

                $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
                $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);
                $roomAmount = $rate['amount'] * $stayDuration;
                $extraCharge = $room->extra_person_charge;
                $extraChargeTotal = $room->extra_person_charge * $extraGuests * $stayDuration;

                $this->selectedRooms[$index] = array_merge($item, [
                    'adults'        => $adults,
                    'kids'          => $kids,
                    'extra_guest'   => $extraGuests,
                    'extra_charge'  => $extraCharge,
                    'extra_charge_total'  => $extraChargeTotal,
                    'roomAmount'    => $roomAmount,
                    'rate_id'       => $rate['rate_id'],
                    'total_amount'  => $roomAmount + $extraChargeTotal,
                ]);
            }
        }
    }

    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
    }

    public function toggleServiceDescription($serviceId)
    {
        $this->expandedService = $this->expandedService === $serviceId ? null : $serviceId;
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

    protected function generateTransactionNumber(): string
    {
        return 'TXN-' . strtoupper(Str::random(8));
    }

    protected function makePetsArray()
    {
        return [
            'breed' => $this->breed,
        ];
    }





    /**
     * ----------------------------- ACCESSORS -----------------------------
     *
     * These computed properties provide easy access to summarized or transformed data.
     * ---------------------------------------------------------------------
     */

    protected function getItemsByType(string $type): array
    {
        $combined = array_merge($this->selectedActivities, $this->selectedRooms, $this->selectedServices);

        return array_filter($combined, fn($item) => $item['type'] === $type);
    }

    protected function getPetFeeAmount(): float
    {
        $service = Service::where('name', 'Pet Fee')->first();
        return $service?->amount ?? 0;
    }

    public function getStayDurationProperty()
    {
        return $this->getStayDuration($this->check_in_date, $this->check_out_date);
    }

    public function getAvailableRooms()
    {
        $this->rooms = $this->roomAvailabilityService
            ->getAvailableRooms($this->check_in_date, $this->check_out_date);

        $this->prepareOccupancyRules();
    }

    public function getDepositProperty()
    {
        return $this->getDeposit($this->computeTotalAmount());
    }





    /**
     * ----------------------------- VALIDATION LOGIC -----------------------------
     * This section contains all methods and rules used for validating user input,
     * such as checking required fields, date logic, and cart duplication.
     * ----------------------------------------------------------------------------
     */
    protected function checkInOutDatesAreValid(): bool
    {
        return $this->check_in_date && $this->check_out_date;
    }

    protected function isItemAlreadyInCart(string $type, int $itemId): bool
    {
        foreach ($this->selectedRooms as $item) {
            if ($item['type'] === $type && $item["{$type}_id"] == $itemId) {
                return true;
            }
        }
        return false;
    }

    protected function validateGuestData()
    {
        return $this->validate([
            'guest_first_name' => 'required|string',
            'guest_middle_name' => 'nullable|string',
            'guest_last_name' => 'required|string',
            'guest_suffix' => 'nullable|string|max:10',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'required|in:local,foreigner',
            'guest_country_of_origin' => 'required|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);
    }

    public function validateData()
    {
        $this->validate([
            'selectedRooms' => 'required|array|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|string',
            'country' => 'required|string',
            'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
            'reservation_source' => 'required|in:Website,AirBnb,Facebook Messenger,Instagram,Walk-In,Other',
            'terms' => 'required|accepted',
            'special_requests.*.request' => 'nullable|string|max:255',
            'pets.*.breed' => 'required|string|max:255',
        ]);
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
        $this->check_in_date = $now->format('Y-m-d');
        $this->check_out_date = $now->copy()->addDay()->format('Y-m-d');
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
     * Loads available rooms and applies dynamic rates.
     */
    protected function loadRooms(): void
    {

        $this->rooms = Property::ofType('Room')
            ->availableRooms()
            ->with(['transactions.feedbacks.feedbackRatings', 'transactions.transactionUser'])
            ->get()
            ->map(callback: function ($room) {
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
        $this->activities = Activity::availableActivities()->get();
        $this->paymentMethod = PaymentMethod::all();
        $this->guest_types = GuestType::all();
        $this->services_charges = Service::all();
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
            'transaction_number' =>  $this->generateTransactionNumber(),
            'reservation_type_id' => $this->reservation_type_id,
            'created_by' => $transactionUser->id,
            'promo_id' => $promo?->id,
            'start_datetime' => $this->check_in_date,
            'end_datetime' => $this->check_out_date,
            'total_adults' => collect($this->selectedRooms)->sum('adults'),
            'total_kids' => collect($this->selectedRooms)->sum('kids'),
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
            'special_requests' => collect($this->special_requests)
                ->filter(fn($req) => isset($req['request']) && trim($req['request']) !== '')
                ->values()
                ->all(),
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
        foreach ($this->selectedRooms as $item) {
            if ($item['type'] === 'room') {
                $this->attachRoomToTransaction($transaction, $item);
            }
        }

        foreach ($this->selectedActivities as $item) {
            if ($item['type'] === 'activity') {
                $this->attachActivityToTransaction($transaction, $item);
            }
        }


        foreach ($this->selectedServices as $item) {
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

    protected function attachServiceToTransaction(Transaction $transaction, array $item): void
    {
        $transaction->services()->attach($item['service_id'], [
            'quantity' => $item['quantity'],
            'amount' => $item['amount'],
            'days' => $item['days'] ?? 0,
            'payment_status' => $item['payment_status'],
        ]);
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
    protected function insertGuestPetDetails(Transaction $transaction): void
    {
        if (!$this->bringingPets || empty($this->pets)) {
            return;
        }

        $nightsStayed = $this->getStayDurationProperty();
        $feePerPetPerDay = $this->getPetFeeAmount();
        $totalFee = 0;

        // Insert individual pet records
        foreach ($this->pets as $pet) {
            $petFee = $feePerPetPerDay * $nightsStayed;
            $totalFee += $petFee;

            GuestPet::create([
                'transaction_id' => $transaction->id,
                'breed'          => $pet['breed'],
                'pet_count'      => 1,
                'nights_stayed'  => $nightsStayed,
                'total_fee'      => $petFee,
            ]);
        }
    }
    // protected function attachPetFeeService(Transaction $transaction, int $petCount, int $days, float $amount): void
    // {
    //     $this->attachServiceToTransaction($transaction, [
    //         'service_id'     => 1, // "Pet Fee" (consider using a constant or config here)
    //         'days'           => $days,
    //         'quantity'       => $petCount,
    //         'amount'         => $amount,
    //         'payment_status' => 'unpaid',
    //     ]);
    // }
}
