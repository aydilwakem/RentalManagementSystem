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
use App\Models\Service;
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
use App\Traits\HasFormattedDates;
use App\Traits\ReservationHelpers;

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
    public $guest_gender, $guest_residency, $guest_country_of_origin;

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
    public $expandedActivity = null;

    // -------------------- MODALS ------------------------ //

    public $roomModal = false, $activityModal = false, $guestModal = false;

    public $editRoomModal = false, $editActivityModal = false, $editGuestModal = false;

    public $addRoomFirstModal = false;

    // -------------------- PETS ------------------------ //

    public bool $bringingPets = false;
    public int $pet_count;
    public array $pet_breed = [];


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


    public $dynamicKidOptions = [];

    public $paymentLink;

    protected ServiceBag $services;
    protected RoomRateService $roomRateService;
    protected CartService $cartService;
    protected RoomAvailabilityService $roomAvailabilityService;
    protected PromoCodeService $promoCodeService;
    protected BrandingService $brandingService;
    protected PayMongoService $payMongo;
    protected EmailService $emailService;



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
            $this->pet_count = 0;
            $this->pet_breed = [];
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
    public function computePetTotal(): float
    {
        $petCount = $this->pet_count ?? 0;
        $stayDuration = $this->getStayDurationProperty() ?? 0;
        $feePerPetPerDay = $this->getPetFeeAmount();

        return $petCount * $feePerPetPerDay * $stayDuration;
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
        $baseSubtotal = $this->computeSubtotalAmount();

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
    }

    public function incrementActivity($activityId)
    {

        if (!isset($this->quantity[$activityId])) {
            $this->quantity[$activityId] = 1;
        }

        $newQuantity = $this->quantity[$activityId] + 1;
        $this->quantity[$activityId] = $newQuantity;

        $this->selectedActivities = $this->cartService->updateQuantity('activity', $this->selectedActivities, $activityId, $newQuantity);
    }

    public function decrementActivity($activityId)
    {
        if (!isset($this->quantity[$activityId])) {
            $this->quantity[$activityId] = 1;
        }

        $newQuantity = max(1, $this->quantity[$activityId] - 1);
        $this->quantity[$activityId] = $newQuantity;

        $this->selectedActivities = $this->cartService->updateQuantity('activity', $this->selectedActivities, $activityId, $newQuantity);
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
    }


    public function RemoveRoom($roomId)
    {
        $this->selectedRooms = $this->cartService->removeItem('room', $roomId, $this->selectedRooms);
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











    // public function CreateReservation()
    // {
    //     $this->validateData();

    //     $this->resetErrorBag();

    //     $reservationData = []; // Initialize an empty array to store reservation data for email

    //     DB::transaction(function () use (&$reservationData) {

    //         // If enable_deposit is true, retrieve the deposit percentage from the settings table
    //         $setting = Setting::first();

    //         // Set the expiration hours for payment proof
    //         $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24; // default value

    //         $this->depositPercentage = $setting && $setting->enable_deposit_percentage
    //             ? $setting->deposit_percentage
    //             : 0;

    //         // Find the promo code in the database
    //         $promo = PromoCode::where('code', $this->promoCode)->first();

    //         // Step 1: Create transaction user
    //         $transactionUser = TransactionUser::create([
    //             'first_name' => $this->first_name,
    //             'middle_name' => $this->middle_name,
    //             'last_name' => $this->last_name,
    //             'email' => $this->email,
    //             'contact_number' => $this->contact_number,
    //             'company_name' => $this->company_name,
    //             'country' => $this->country,
    //             'trn_user_type' => $this->trn_user_type,
    //         ]);

    //         // Step 2: Create transaction
    //         $transaction = Transaction::create([
    //             'transaction_number' => 'TXN-' . strtoupper(Str::random(8)),
    //             'reservation_type_id' => $this->reservation_type_id,
    //             'created_by' => $transactionUser->id,
    //             'promo_id' => $promo?->id,
    //             'start_datetime' => $this->check_in_date,
    //             'end_datetime' => $this->check_out_date,
    //             'total_adults' => collect($this->selectedRooms)->sum('adults'),
    //             'total_kids' => collect($this->selectedRooms)->sum('kids'),
    //             'pax' => $this->total_pax,
    //             'sub_total' => $this->sub_total ?? 0,
    //             'convenience_fee' => $this->convenience_fee ?? 0,
    //             'promo_discount_amount' => $this->promo_discount_amount ?? 0,
    //             'total_amount' => $this->computeTotalAmount(),
    //             'deposit_amount' => $this->computeTotalAmount() * ($this->depositPercentage / 100),
    //             'heard_from' => $this->heard_from,
    //             'reservation_source' => $this->reservation_source,
    //             'transaction_status' => $this->transaction_status,
    //             'terms' => $this->terms,
    //         ]);

    //         // Step 3 & 4: Generate invoice number
    //         $latestInvoice = Invoice::whereYear('created_at', now()->year)->orderBy('created_at', 'desc')->first();
    //         $invoiceNumber = 'INV-' . now()->year . '-' . str_pad($latestInvoice ? (int) substr($latestInvoice->invoice_number, -3) + 1 : 1, 3, '0', STR_PAD_LEFT);

    //         // Step 5: Create invoice
    //         $invoice = Invoice::create([
    //             'transaction_id' => $transaction->id,
    //             'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
    //             'invoice_type' => 'Room',
    //             'sub_total' => $this->computeTotalAmount(),
    //             'deposit_paid' => 0,
    //             'amount_paid' => 0,
    //             'balance_due' => $this->computeTotalAmount(),
    //             'due_date' => $this->check_out_date,
    //             'invoice_status' => 'pending',
    //         ]);

    //         // Step 6: Attach rooms and activities
    //         foreach ($this->selectedRooms as $item) {
    //             $transaction->properties()->attach($item['room_id'], [
    //                 'adults' => $item['adults'],
    //                 'kids' => $item['kids'],
    //                 'days' => $item['days'],
    //                 'extra_charge' => $item['extra_charge'],
    //                 'amount' => $item['roomAmount'],
    //                 'total_amount' => $item['total_amount'],
    //                 'extra_guest' => $item['extra_guest'],
    //             ]);
    //         }

    //         foreach ($this->selectedActivities as $item) {
    //             $transaction->activities()->attach($item['activity_id'], [
    //                 'quantity' => $item['quantity'],
    //                 'amount' => $item['amount'],
    //             ]);
    //         }

    //         // Step 7: Insert GuestDetails
    //         foreach ($this->guests as $guest) {
    //             GuestDetail::create([
    //                 'transaction_id' => $transaction->id,
    //                 'first_name' => $guest['guest_first_name'],
    //                 'middle_name' => $guest['guest_middle_name'],
    //                 'last_name' => $guest['guest_last_name'],
    //                 'suffix' => $guest['guest_suffix'],
    //                 'gender' => $guest['guest_gender'],
    //                 'residency' => $guest['guest_residency'],
    //                 'country_of_origin' => $guest['guest_country_of_origin'],
    //                 'guest_type_id' => $guest['guest_type_id'],
    //             ]);
    //         }

    //         // Get the payment_proof_expiration_hours from database
    //         $setting = Setting::first();
    //         $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24; // default value

    //         // ---------------------- PAYMONGO PAYMENT LINK INTEGRATION STARTS HERE ------------------------ //

    //         // Creates a new HTTP client instance (likely from GuzzleHttp\Client).
    //         // This client will be used to send HTTP requests to the PayMongo API.
    //         $client = new Client();

    //         // Retrieves the PayMongo secret key from .env.
    //         $apiKey = env('PAYMONGO_SECRET_KEY');

    //         // Calculates the amount to be charged in centavos (smallest currency unit for PHP).
    //         $amountInCentavos = intval($this->computeTotalAmount() * ($this->depositPercentage / 100) * 100);

    //         try {

    //             // Sends an HTTP POST request to PayMongo's API endpoint to create a checkout session (payment link).
    //             $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [

    //                 'headers' => [ // Extra pieces of information to be sent with the HTTP request.
    //                     'Accept' => 'application/json', // What type of data the client expects in response.
    //                     'Content-Type' => 'application/json', // What type of data is being sent in the request body.
    //                     'Authorization' => 'Basic ' . base64_encode($apiKey . ':'), // Access to paymongo, contains the secret key.
    //                 ],

    //                 // JSON payload to be sent in the request body.
    //                 'json' => [
    //                     'data' => [
    //                         'attributes' => [ //  Payment Session Settings
    //                             'send_email_receipt' => true, // Instructs PayMongo to email a receipt to the payer after a successful payment.
    //                             'show_description' => true, // Shows the overall description of the payment on the checkout page.
    //                             'show_line_items' => true, // Displays the breakdown of items (from line_items) on the PayMongo checkout page
    //                             'payment_method_types' => ['card', 'gcash', 'qrph', 'paymaya',], // Specifies the payment methods that are accepted for this checkout session.
    //                             'success_url' => route('guest.thank-you-page'), // This is where the user will be redirected after successful payment.
    //                             'cancel_url' => 'http://127.0.0.1:8000/payment-failed', // If the user cancels or the payment fails, they will be sent here.

    //                             'line_items' => [ // This is a list of what the user is paying for.
    //                                 [
    //                                     'currency' => 'PHP',
    //                                     'amount' => $amountInCentavos,  // e.g. 150000 for PHP 1,500.00
    //                                     'description' => 'Reservation ' . $transaction->transaction_number,
    //                                     'name' => 'Bayad ka na uy',
    //                                     'quantity' => 1,
    //                                 ],
    //                             ],

    //                             'description' => 'Reservation for ' . $this->first_name . ' ' . $this->last_name, // This is a general description for the transaction, shown to the payer.

    //                             // Additional metadata for tracking purposes.
    //                             'metadata' => [
    //                                 'invoice_id' => (string) $invoice->id,
    //                                 'payment_type' => 'Security Deposit',
    //                                 'notes' => 'Deposit for Reservation',
    //                             ]

    //                         ],
    //                     ],
    //                 ],


    //             ]);

    //             // Converts the JSON response from the PayMongo API into a PHP array.
    //             $responseData = json_decode($response->getBody(), true);

    //             // Retrieves the 'data' key from the response, which contains the attributes of the created checkout session.
    //             // $responseAllData = $responseData['data'] ?? [];
    //             // dd($responseAllData);

    //             // Retrieves the checkout_url from the response
    //             $paymentLink = $responseData['data']['attributes']['checkout_url'] ?? null;

    //             // Save payment link to transaction (optional)
    //             $transaction->update(['payment_link' => $paymentLink]);

    //             // If a promo code is used, increment the uses_count of Promo Code
    //             if ($promo) {
    //                 $promo->increment('uses_count');
    //             }
    //         } catch (\Exception $e) {

    //             Log::error('PayMongo link creation failed: ' . $e->getMessage());
    //             $paymentLink = null; // fallback

    //         }

    //         // ---------------------- PAYMONGO PAYMENT LINK INTEGRATION ENDS HERE ------------------------ //


    //         $total = $this->computeTotalAmount();
    //         $deposit = $total * ($this->depositPercentage / 100);

    //         // Prepare data for the email (accessible outside transaction)
    //         $reservationData = [
    //             'name' => $this->first_name . ' ' . $this->last_name,
    //             'transaction_number' => $transaction->transaction_number,
    //             'email' => $this->email,
    //             'invoice_number' => $invoiceNumber,
    //             'check_in' => $this->check_in_date,
    //             'check_out' => $this->check_out_date,
    //             'total_amount' => $total,
    //             'deposit' => $deposit,
    //             'expirationHours' => $this->expirationHours,
    //             'payment_link' => $paymentLink,
    //             'branding_company_name' => $this->companyName,
    //             'logo_path' => $this->logoPath,
    //             'branding_company_email' => $this->companyEmail,
    //             'branding_company_contact' => $this->companyContact,
    //             'company_address' => $this->companyAddress,
    //             'facebook_link' => $this->facebookLink,
    //             'instagram_link' => $this->instagramLink,
    //         ];
    //     });

    //     // // Step 7: Send confirmation email
    //     // try {
    //     //     Mail::to($reservationData['email'])->send(new ReservationSubmittedMail($reservationData));
    //     // } catch (\Exception $e) {
    //     //     logger()->error('Email send failed: ' . $e->getMessage());
    //     //     session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
    //     // }

    //     // Step 8: Flash success message and redirect
    //     session()->flash('success', 'Reservation successfully created!');
    //     return redirect()->route('admin.reservations-list');
    // }






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
        try {
            $emailService->sendReservationEmails($reservationData);
        } catch (\Exception $e) {
            // If email sending fails, flash error but still continue
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        session()->flash('success', 'Reservation successfully created!');
        return redirect()->route('admin.reservations-list');
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
            default                       => [],
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
                $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;

                $this->selectedRooms[$index] = array_merge($item, [
                    'adults'        => $adults,
                    'kids'          => $kids,
                    'extra_guest'   => $extraGuests,
                    'extra_charge'  => $extraCharge,
                    'roomAmount'    => $roomAmount,
                    'rate_id'       => $rate['rate_id'],
                    'total_amount'  => $roomAmount + $extraCharge,
                ]);
            }
        }
    }

    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
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





    /**
     * ----------------------------- ACCESSORS -----------------------------
     *
     * These computed properties provide easy access to summarized or transformed data.
     * ---------------------------------------------------------------------
     */

    protected function getItemsByType(string $type): array
    {
        $combined = array_merge($this->selectedActivities, $this->selectedRooms);

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
            'country' => 'required|string',
            'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
        ]);

        if (count($this->guests) !== $this->total_pax) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'guests' => 'Please input all the guests before proceeding.',
            ]);
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
            ->map(function ($room) {
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
    protected function insertGuestPetDetails(Transaction $transaction): void
    {
        if (!$this->bringingPets) {
            return;
        }

        $nights_stayed = $this->getStayDurationProperty();
        $feePerPetPerDay = $this->getPetFeeAmount();

        $this->validate([
            'pet_count' => 'required|integer|min:1',
            'pet_breed' => 'required|array|min:1',
            'pet_breed.*' => 'required|string|max:255',
        ]);

        // dd($this->pet_count, $this->pet_breed, $transaction->id); // Debug

        GuestPet::create([
            'transaction_id'   => $transaction->id,
            'pet_breed'        => json_encode($this->pet_breed),
            'pet_count'        => $this->pet_count,
            'nights_stayed'    => $nights_stayed,
            'total_fee'        => $this->pet_count * $feePerPetPerDay * $nights_stayed,
        ]);
    }
}
