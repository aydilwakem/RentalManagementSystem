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


    /**
     * ----------------------------- RENDER --------------------------------
     *
     * Displays the reservation creation view.
     * - Calls `getAvailableRooms` to fetch room data before rendering.
     * - Returns the corresponding Livewire view for the reservation form.
     * ---------------------------------------------------------------------
     */

    public function render()
    {
        $this->getAvailableRooms();
        return view('livewire.admin.reservations.create-reservation');
    }

    /**
     * ------------------------------ BOOT ---------------------------------
     *
     * Injects necessary services into the component via ServiceBag.
     *
     * ---------------------------------------------------------------------
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

    /**
     * ----------------------------- MOUNT ---------------------------------
     *
     * Initializes data for the Create Reservation component.
     * - Loads available rooms with dynamic rates.
     * - Sets default check-in and check-out dates.
     * - Prepares occupancy rules.
     * - Loads available activities, guest types, and payment methods.
     * - Retrieves company branding using the BrandingService.
     * - Populates available rooms list.
     * ---------------------------------------------------------------------
     */

    public function mount(): void
    {
        $this->initializeDates();
        $this->prepareOccupancyRules();
        $this->loadStaticData();
        $this->loadRooms();
        $this->loadBranding();
        $this->getAvailableRooms();
        $this->initializeGuestResidency();
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
            $roomId = explode('.', $property)[1];
            $room = Property::find($roomId);

            if (!$room) {
                $this->addError('selectedRooms', 'Room not found.');
                return;
            }

            $this->updateKidOptions($roomId);
            $this->updateSelectedRoomDetails($roomId, $room);
            $this->computeTotalPax();
            $this->getAvailableRooms();
        }

        // If check-in and check-out dates are updated
        if (in_array($property, ['check_in_date', 'check_out_date'])) {

            Log::info('Dates are changed.');
            $this->selectedRooms = [];
            $this->selectedActivities = [];
            $this->selectedServices = [];
            $this->pet_count = 0;
            $this->pets = [];
            $this->getAvailableRooms();
            $this->removePromoCode();
            $this->computeSubtotalAmount();
            $this->computeTotalAmount();
        }
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

    public function computeTotalAmountOfAllServices(): float
    {
        return collect($this->getItemsByType('service'))
            ->sum('amount');
    }
    public function computePetTotal(): float
    {
        $petCount = $this->pet_count ?? 0;
        $stayDuration = $this->getStayDurationProperty() ?? 0;
        $feePerPetPerDay = $this->getPetFeeAmount();

        return $petCount * $feePerPetPerDay * $stayDuration;
    }

    public function computeBaseSubtotal()
    {
        return $this->computeTotalAmountOfAllRooms()
            + $this->computeTotalAmountOfAllActivities()
            + $this->computeTotalAmountOfAllServices()
            + $this->computePetTotal();
    }

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

    // public function computeTotalAmount()
    // {
    //     try {
    //         if (method_exists($this, 'computeSubtotalAmount')) {
    //             $this->computeSubtotalAmount();
    //         } else {
    //             throw new \Exception('computeSubtotalAmount() method not found.');
    //         }

    //         $this->sub_total = is_numeric($this->sub_total) ? $this->sub_total : 0;
    //         $this->convenience_fee = round($this->sub_total * 0.03, 2);
    //         $this->total_amount = max(0, $this->sub_total + $this->convenience_fee);

    //         return $this->total_amount;
    //     } catch (\Throwable $e) {
    //         Log::error('Error in computeTotalAmount: ' . $e->getMessage());

    //         // Safe fallback values
    //         $this->sub_total = 0;
    //         $this->convenience_fee = 0;
    //         $this->total_amount = 0;

    //         session()->flash('error', 'Something went wrong while computing the total amount.');

    //         return 0;
    //     }
    // }



    public function updateApplyConvenienceFee()
    {
        $this->recalculateCart();
    }

    public bool $apply_convenience_fee = true;

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
    public function applyPromoCode()
    {
        Log::info('Apply Promo Code method called with promoCode: ' . $this->promoCode);

        if (empty($this->promoCode) || !is_string($this->promoCode)) {
            Log::warning('No valid promo code provided. Skipping promo application.');
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

    public function SelectedActivities($activityId)
    {

        // Resets any previous error messages
        $this->resetErrorBag();

        $newActivityCart = $this->cartService->addItem(
            'activity',
            $activityId,
            $this->selectedActivities,
            $this->quantity,
            $this->status,
            $this->paymentStatus
        );

        if ($this->isItemAlreadyInCart('activity', $activityId)) {
            $this->addError('selectedActivities', 'This item is already in the cart.');
            return;
        }

        $this->selectedActivities = $newActivityCart;
        $this->recalculateCart();
    }
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
    public function editSelectedActivity($activityId)
    {
        Log::info("Edit Activity method called for activity ID: {$activityId}");

        $activity = collect($this->selectedActivities)->firstWhere('activity_id', $activityId);

        if (!$activity) {
            Log::warning("Activity ID {$activityId} not found in selectedActivities.");
            return;
        }

        $this->editingActivityId = $activity['activity_id'];
        $this->activityQuantity = $activity['quantity'] ?? 1;
        $this->activityName = $activity['activity_name'] ?? 1;
        $this->showEditActivityModal = true;
    }
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
    public function updateActivity()
    {
        foreach ($this->selectedActivities as &$activity) {
            if ($activity['activity_id'] == $this->editingActivityId) {
                $activity['quantity'] = $this->activityQuantity;
                $activity['amount'] = $activity['activity_rate'] * $this->activityQuantity;
                break;
            }
        }

        $this->selectedActivities = array_values($this->selectedActivities);

        $this->showEditActivityModal = false;
        $this->editingActivityId = null;
        $this->activityQuantity = 1;

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


    // public function SelectedServices($serviceId)
    // {

    //     // Resets any previous error messages
    //     $this->resetErrorBag();

    //     $newServicesCart = $this->cartService->addItem(
    //         'service',
    //         $serviceId,
    //         $this->selectedServices,
    //         $this->quantity,
    //         $this->status,
    //         $this->paymentStatus
    //     );

    //     if ($this->isItemAlreadyInCart('service', $serviceId)) {
    //         $this->addError('selectedServices', 'This item is already in the cart.');
    //         return;
    //     }

    //     $this->selectedServices = $newServicesCart;
    //     $this->recalculateCart();
    // }

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
        $this->validate([
            'breed' => 'required|string|max:255',
        ]);

        $this->pets[] = $this->makePetsArray();
        $this->pet_count = count($this->pets);

        $this->reset('breed'); // clear input
        $this->recalculateCart();
    }

    public function removeGuestPet($index)
    {
        if (isset($this->pets[$index])) {
            unset($this->pets[$index]);
            $this->pets = array_values($this->pets);
            $this->pet_count = count($this->pets);
        }
        $this->handlePetServiceLogic();
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
        // Reset validation error messages
        $this->resetErrorBag();
        $this->validateData();
        $transaction = null;

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

        // Attempt to send confirmation emails to guest and admin
        // try {
        //     $emailService->sendReservationEmails($reservationData);
        // } catch (\Exception $e) {
        //     // If email sending fails, flash error but still continue
        //     session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        // }

        if (!$transaction) {
            session()->flash('error', 'Something went wrong while creating reservation.');
            return redirect()->route('admin.reservations-list');
        }

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

    protected function prepareRoomCartContext($room, $roomId): array
    {
        // ------------------ FETCH STATIC VALUES ----------------------- //

        // Fetch the stay duration
        $stayDuration = $this->getStayDurationProperty();

        // Fetch room's ideal guest
        $included_guests = $room->ideal_guest;

        // Fetch the current room rate
        $rate = $this->roomRateService->getDynamicRate($room, $this->check_in_date ?? null);
        $roomRate =  $rate['amount'];

        // Fetch the extra charge depending on its room
        $extraCharge = $room->extra_person_charge;

        // --------------------- ASSIGN VALUES ----------------------- //

        // Assign the number of adults and kids
        $adults = (int) ($this->adults[$roomId] ?? 1);
        $kids = (int) ($this->kids[$roomId] ?? 0);

        // Fetch room total pax
        $total_pax = $adults + $kids;

        // Fetch extra guests based on total pax - room's ideal guest
        $extraGuests = max(0, $total_pax - $included_guests);

        // Computes the roomAmount by multiplying rate by stay duration
        $roomAmount = $roomRate * $stayDuration;

        // Computes the extra charge total
        $extraChargeTotal = $extraCharge * $extraGuests * $stayDuration;



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
            'guest_residency' => 'nullable|in:local,foreigner',
            'guest_country_of_origin' => 'nullable|string|max:100',
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
            'country' => 'nullable|string',
            'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
            'reservation_source' => 'required|in:Airbnb,WebApp,Phone,Messenger,Other',
            'terms' => 'required|accepted',
            'requests' => 'nullable|string|max:1000',
            'pets.*.breed' => 'required|string|max:255',
        ]);
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


    protected function initializeDates()
    {
        $now = Carbon::now('Asia/Manila');
        $this->check_in_date = $now->format('Y-m-d');
        $this->check_out_date = $now->copy()->addDay()->format('Y-m-d');
    }

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



    protected function loadStaticData()
    {
        $this->activities = Activity::availableActivities()->get();
        $this->paymentMethod = PaymentMethod::all();
        $this->guest_types = GuestType::all();
        $this->services_charges = Service::all();
    }

    public function initializeGuestResidency()
    {
        $this->countries = Countries::all()->pluck('name.common')->sort()->values()->toArray();
        $this->country = 'Philippines';
        $this->guest_country_of_origin = 'Philippines';

        //Default country
        //  if (strtolower($this->guest_residency) === 'local') {
        // $this->guest_country_of_origin = 'Philippines';
        // } else {
        //     $this->guest_country_of_origin = '';
        // }

    }


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
