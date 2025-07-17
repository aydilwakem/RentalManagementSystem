<?php

namespace App\Livewire\Guest\Reservation;

use App\Mail\NewReservationMail;
use Livewire\Component;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\TransactionUser;
use App\Models\PropertyCategory;
use App\Models\GuestDetail;
use App\Models\Invoice;
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

class ReservationForm extends Component
{
    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';
    public $cart = []; // Keeps all the selected rooms and activities
    public $total_amount; // Total amount for the reservation
    public $total_pax = 2; // Total number of guests (adults + kids)
    public $check_in_date;
    public $check_out_date;
    public $roomCategories;
    public $sub_total;
    // ----------------------- ROOMS ---------------------------- //

    // rooms - adults - kids - extra-guest - extra-charge - amount
    public $rooms = [];
    public $adults = [];
    public $kids = [];
    public $extra_guest = [];
    public $extra_charge = []; // extra_guest * extra_person_charge * days
    public $roomAmount = []; // base rate * days
    public $roomsTotalAmount = [];
    public $selectedFeatures = [];

    // --------------------- ACTIVITIES ------------------------- //

    // activities - quantity - activity_datetime - activityAmount - status
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
    public $paymentMethod;

    // ------------------- OTHERS -------------------- //
    public $terms = 0;
    public $terms_and_conditions;
    public $expirationHours;
    protected $queryString = ['currentStep'];
    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin;
    public $guest_types = [];
    public $guests = [];
    public $editingGuestIndex = null;
    public $roomCategoryFilter = '';


    // ------------------- NAVIGATION STEPS -------------------- //

    public $currentStep = 1;
    public $totalSteps = 4;

    protected $listeners = ['refreshComponent' => '$refresh'];

    // --------------- PAYMENT INTEGRATION ------------------- //
    public $enable_deposit_percentage = true;
    public $depositPercentage;
    public $convenienceFeeInCentavos;
    public $convenience_fee;

    // --------------- EDITING GUEST DETAIL ------------------- //
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

    // ---------------------- MODALS -------------------------- //
    public $confirmReservationModal = false;
    public $showEditModal = false;
    public $showGuestModal = false;

    //----------------------- BRANDING ------------------------ //
    public string $companyName = 'Company'; //Default
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

    public function getFormattedCheckInDate()
    {
        return $this->check_in_date ? Carbon::parse($this->check_in_date)->format('F j, Y') : '';
    }

    public function getFormattedCheckOutDate()
    {
        return $this->check_out_date ? Carbon::parse($this->check_out_date)->format('F j, Y') : '';
    }

    /**
     * Initializes the component with default values.
     *
     * - Loads available rooms and activities.
     * - Sets default check-in date to now and check-out date to the next day.
     * - Sets the current step to the beginning of the form (step 1).
     *
     * @return void
     */

    public function mount()
    {
        // $this->rooms = Property::ofType('Room')->availableRooms()->get();
        $this->rooms = Property::ofType('Room')
            ->availableRooms()
            ->with(['transactions.feedbacks.feedbackRatings', 'transactions.transactionUser'])
            ->get()
            ->map(function ($room) {
                $rate = $this->getDynamicRate($room);
                $room->dynamic_rate = $rate['amount'];
                $room->rate_name = $rate['name'];
                $room->rate_type = $rate['rate_type'];
                $room->rate_id = $rate['rate_id'];
                return $room;
            });

        $this->roomCategories = PropertyCategory::all();
        $this->selectedFeatures = [];
        $this->activities = Activity::availableActivities()->get();
        $this->currentStep = 1;
        $this->paymentMethod = PaymentMethod::all();
        $this->terms_and_conditions = Setting::find(1)->terms_and_conditions;
        $this->guest_types = GuestType::all();

        // For Branding
        // Fetch the first row of the settings table
        $setting = Setting::first();
        if ($setting) {
            $this->companyName = $setting->company_name;
            $this->logoPath = $setting->logo;
            $this->companyEmail = $setting->email;
            $this->companyContact = $setting->contact_number;
            $this->companyAddress = $setting->address;
            $this->facebookLink = $setting->facebook;
            $this->instagramLink = $setting->instagram;
        }

        $now = Carbon::now('Asia/Manila');
        $this->check_in_date = $now->format('Y-m-d');
        $this->check_out_date = $now->copy()->addDay()->format('Y-m-d');
    }

    /**
     * Get the dynamic rate for the given room based on date and rate type.
     *
     * @param mixed $room The room object for which to fetch the dynamic rate.
     * @return array{amount: mixed, name: mixed, rate_type: mixed}
     *
     * This method checks for a valid rate based on priority:
     * 1. Peak Rate
     * 2. Holiday Rate
     * 3. Weekend/Weekday Rate
     * 
     * If no matching rate is found, it defaults to the base amount from the room.
     */
    public function getDynamicRate($room)
    {
        // $date = now(); // or selected check-in date
        $date = $this->check_in_date ? Carbon::parse($this->check_in_date) : now();
        $dayOfWeek = $date->dayOfWeek; // $date->dayOfWeek returns an integer that represents the day of the week

        // 1. Check for Peak rate
        $peakRate = RoomRate::where('property_id', $room->id)
            ->where('rate_type', 'Peak')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereNull('deleted_at')
            ->first();

        if ($peakRate) {
            return [
                'rate_id' => $peakRate ? $peakRate->id : null,
                'amount' => $peakRate->amount,
                'name' => $peakRate->name ?? 'Peak Rate',
                'rate_type' => 'Peak',
            ];
        }

        Log::info('Peak Rate:', [$peakRate]);

        // 2. Check for Holiday rate
        $holidayRate = RoomRate::where('property_id', $room->id)
            ->where('rate_type', 'Holiday')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereNull('deleted_at')
            ->first();

        if ($holidayRate) {
            return [
                'rate_id' => $holidayRate ? $holidayRate->id : null,
                'amount' => $holidayRate->amount,
                'name' => $holidayRate->name ?? 'Holiday Rate',
                'rate_type' => 'Holiday',
            ];
        }
        Log::info('Weekend/Weekdays Rate:', [$holidayRate]);



        // 3. Determine Weekend or Weekday
        $rateType = ($dayOfWeek === 0 || $dayOfWeek === 6) ? 'Weekend' : 'Weekdays';

        // 4. Check for that rate
        $rate = RoomRate::where('property_id', $room->id)
            ->where('rate_type', $rateType)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereNull('deleted_at')
            ->first();

        Log::info('Weekend/Weekdays Rate:', [$rate]);

        // 5. Return found rate or fallback to base
        return [
            'amount' => $rate ? $rate->amount : $room->amount,
            'name' => $rate ? $rate->name : 'Base Rate',
            'rate_type' => $rate ? $rate->rate_type : null,
            'rate_id' => $rate ? $rate->id : null,
        ];
    }


    /**
     * Renders the Livewire reservation form view for guests.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->getAvailableRooms();
        return view('livewire.guest.reservation.reservation-form');
    }


    // --------------------------------------------- NAVIGATION STEPS ------------------------------------- //

    /**
     * Advances to the next step in the multi-step reservation form.
     *
     * - Validates current step data before proceeding.
     * - Increases the current step up to the maximum allowed.
     *
     * @return void
     */

    public function increaseStep()
    {
        $this->resetErrorBag();
        $this->validateData();

        $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
    }

    /**
     * Goes back to the previous step in the reservation form.
     *
     * - Resets any validation errors.
     * - Decreases the current step down to a minimum of 1.
     *
     * @return void
     */

    public function decreaseStep()
    {
        $this->resetErrorBag();
        $this->currentStep = max($this->currentStep - 1, 1);
    }


    // --------------------------------------------- LOGIC ----------------------------------------------- //

    /**
     * Handles dynamic updates to component properties like adults, kids, check-in/out, or activity quantity.
     *
     * This method responds to Livewire property changes. If the property is related to
     * room guest counts (adults/kids), it updates the respective values in the cart.
     * If the property is check-in/check-out date, it fetches available rooms.
     * If the property is related to activity quantity, it updates the quantity in the cart.
     *
     * @param string $property The name of the property that was updated.
     *
     * @return void
     */

    public function updated($property)
    {
        // ---------------------------- ADULTS AND KIDS -------------------------- //
        if (Str::startsWith($property, 'adults.') || Str::startsWith($property, 'kids.')) {

            // haystack - adults.2 or kids.2
            // needle - adults. or kids.

            $roomId = explode('.', $property)[1];
            // extract the room id from the adults.'room_id'
            // "adults.{{ $room->id }}"
            // adults. - seperated by a dot
            // extracted room id = 2

            // Get the Room model
            $room = Property::find($roomId);
            if (!$room) {
                $this->addError('cart', 'Room not found.');
                return;
            }

            // Change the adult of room id 2 to 2
            // Change the kid of room id 2 to 1

            // index => $item
            // 0 - room (type),    room_id 2,     1 adult,     2 kids
            // 1 - room (type),    room_id 3,     2 adult,     2 kids
            // 2 - activity(type), activity_3,    3 quantity

            foreach ($this->cart as $index => $item) {
                if ($item['type'] === 'room' && $item['room_id'] == $roomId) {
                    $adults = (int) ($this->adults[$roomId] ?? 1); // extracts the adults of the item
                    $kids = (int) ($this->kids[$roomId] ?? 0); // extracts the kids of the item

                    $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
                    $stayDuration = $this->getStayDurationProperty();
                    $rate = $this->getDynamicRate($room);
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

            // Triggers compute total pax method
            $this->computeTotalPax();
            $this->getAvailableRooms();
        }

        // --------------- CHECK-IN AND CHECK-OUT DATES ------------------- //
        if (in_array($property, ['check_in_date', 'check_out_date'])) {

            $this->cart = [];
            $this->currentStep = 1;
            $this->getAvailableRooms();
        }
    }

    /**
     * Computes the number of days between check-in and check-out.
     *
     * Uses Carbon to parse the dates and get the difference in days.
     * Returns 0 if either date is not provided.
     *
     * @return int Duration of the stay in days.
     */


    public function getStayDurationProperty()
    {
        if ($this->check_in_date && $this->check_out_date) {
            $in = Carbon::parse($this->check_in_date);
            $out = Carbon::parse($this->check_out_date);

            if ($out->greaterThan($in)) {
                return $in->diffInDays($out);
            }

            return 0;
        }

        return 0;
    }

    // you can call this method as a property in the blade file
    // $this-> getStayDurationProperty() or $this->getStayDuration

    /**
     * Fetch available rooms for the selected check-in and check-out dates.
     *
     * Filters out rooms already booked during the specified range by checking
     * overlapping transactions. Only available rooms of type 'Room' are returned.
     *
     * @return void
     */

    // public function getAvailableRooms()
    // {
    //     if (!$this->check_in_date || !$this->check_out_date) {
    //         return;
    //     }

    //     $checkIn = \Carbon\Carbon::parse($this->check_in_date);
    //     $checkOut = \Carbon\Carbon::parse($this->check_out_date);

    //     $this->rooms = Property::ofType('Room')->availableRooms()
    //         ->where('property_status', 'available') // only explicitly include available
    //         ->where('property_status', '!=', ['out_of_service', 'held', 'booked']) // explicitly exclude out_of_service
    //         ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
    //             $query->whereIn('transaction_status', ['pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing'])
    //                 ->where(function ($q) use ($checkIn, $checkOut) {
    //                     $q->where('start_datetime', '<', $checkOut) // Any booking that starts before the user checks out
    //                         ->where('end_datetime', '>', $checkIn); // Ends after the user checks in
    //                 });
    //         })
    //         ->with('features')
    //         ->get()
    //         ->map(function ($room) {
    //             $rate = $this->getDynamicRate($room);
    //             $room->dynamic_rate = $rate['amount'];
    //             $room->rate_name = $rate['name'];
    //             $room->rate_type = $rate['rate_type'];
    //             return $room;
    //         });
    //     // ->get();
    // }

    public function getAvailableRooms()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);
        $checkOut = \Carbon\Carbon::parse($this->check_out_date);

        $this->rooms = Property::ofType('Room')
            ->whereNotIn('property_status', ['out_of_service', 'held']) // still exclude truly unavailable
            ->with(['features', 'transactions' => function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('transaction_status', ['pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->where('start_datetime', '<', $checkOut)
                            ->where('end_datetime', '>', $checkIn);
                    });
            }])
            ->get()
            ->map(function ($room) use ($checkIn, $checkOut) {
                $isBooked = $room->transactions->isNotEmpty();
                $rate = $this->getDynamicRate($room);

                $room->is_booked = $isBooked;
                $room->dynamic_rate = $rate['amount'];
                $room->rate_name = $rate['name'];
                $room->rate_type = $rate['rate_type'];

                return $room;
            })

            ->sortBy('is_booked'); // false (available) comes first, true (booked) later
    }

    public function getDepositProperty()
    {
        $setting = DB::table('st_settings')->first();

        // If the enable_deposit_percentage setting is not enabled, return 0
        if (!$setting || !$setting->enable_deposit_percentage) {
            return 0;
        }

        // Otherwise, calculate the deposit using the percentage
        $depositPercentage = $setting->deposit_percentage ?? 0;

        return $this->computeTotalAmount() * ($depositPercentage / 100);
    }

    // -------------------------------- COMPUTED PROPERTIES ------------------------------------------ //


    /**
     * Computes the total number of guests (pax) by summing all adults and kids from the cart.
     *
     * Loops through the `adults` and `kids` arrays and adds their values to get the total pax.
     * Updates the `total_pax` property accordingly.
     *
     * @return void
     */

    // This method calculates the total number of guests (pax) in the cart
    public function computeTotalPax()
    {
        $total = 0; // this will store the total number of guests // 3

        foreach ($this->cart as $item) {
            // type = room, room_id = 1, adults = 2, kids = 1 // ideal guest = 2
            // type = room, room_id = 2, adults = 2, kids = 4
            // type = activity, activity_id = 1, quantity = 2

            if ($item['type'] === 'room') {
                // Fetch items with type = 'room' ex. room_id 1
                $adults = (int) ($item['adults'] ?? 0); // extracts the adults of the item - 2
                $kids = (int) ($item['kids'] ?? 0); // extracts the kids of the item - 1

                // Calculate the total number of guests in the room
                $guestsInRoom = $adults + $kids; // 2 + 1 = 3

                $total += $guestsInRoom; // here the guestsInRoom will be added
            }
        }

        $this->total_pax = $total;
    }

    public function computeTotalAmountOfAllRooms()
    {
        $total = 0;

        foreach ($this->cart as $item) {
            if ($item['type'] === 'room') {
                $total += $item['total_amount'];
            }
        }

        return $total;
    }

    public function computeTotalAmountOfAllActivities()
    {
        $total = 0;

        foreach ($this->cart as $item) {
            if ($item['type'] === 'activity') {
                $total += $item['amount'];
            }
        }

        return $total;
    }

    public function computeSubtotalAmount()
    {
        $baseSubtotal = $this->computeTotalAmountOfAllRooms() + $this->computeTotalAmountOfAllActivities();
        $total = $baseSubtotal - $this->promoDiscount;
        $this->sub_total = max(0, $total);

        return $this->sub_total;
    }

    /**
     * Computes the total amount including room fees, activity fees,
     * a 3% convenience fee, and subtracts any promo discount.
     *
     * @return float The final total amount
     */
    public function computeTotalAmount()
    {
        // Step 1: Calculate base subtotal (rooms + activities)
        $baseSubtotal = $this->computeTotalAmountOfAllRooms() + $this->computeTotalAmountOfAllActivities();

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

    /**
     * Always recalculates the convenience fee based on the latest subtotal.
     *
     * @return float The updated convenience fee
     */
    public function computeConvenienceFee()
    {
        // Recompute to ensure the most current values
        $this->computeTotalAmount();

        return $this->convenience_fee;
    }

    // ------------------------------------------ PROMO CODE ------------------------------------------ //

    public function applyPromoCode()
    {
        Log::info('applyPromoCode method called with promoCode: ' . $this->promoCode);

        $this->reset(['discountMessage', 'errorMessage']);
        $promo = PromoCode::where('code', $this->promoCode)->first();
        $now = Carbon::now('Asia/Manila');

        // Step 1: Validatess promo existence
        if (!$promo) {
            return $this->failPromo('Invalid promo code. Please try again.');
        }


        // Step 2: Checks minimum booking amount
        if ($this->total_amount < $promo->min_booking_amount) {
            return $this->failPromo('This promo code requires a minimum booking amount of ₱' .
                number_format((float) $promo->min_booking_amount, 2) . '.');
        }

        // Step 3: Check promo date validity if it has expiration
        if ($promo->has_expiration && $promo->start_date && $promo->end_date) {
            if (
                $now->lt(Carbon::parse($promo->start_date)) ||
                $now->gt(Carbon::parse($promo->end_date))
            ) {
                return $this->failPromo('This promo code has expired or is not yet active.');
            }
        }

        // Step 4: Checks if the max uses has been reached
        if ($promo->max_uses > 0 && $promo->uses_count >= $promo->max_uses) {
            return $this->failPromo('This promo code has reached its maximum usage limit.');
        }

        // Step 5: Checks if the promo code is active
        if (!$promo->is_active) {
            return $this->failPromo('This promo code is currently inactive.');
        }

        // Step 6: Calculates discountg
        $this->sub_total = $this->computeSubtotalAmount();

        switch ($promo->discount_type) {
            case 'percentage':
                $this->promoDiscount = ($promo->discount_value / 100) * $this->sub_total;
                break;
            case 'fixed':
                $this->promoDiscount = $promo->discount_value;
                break;
            default:
                $this->promoDiscount = 0;
                break;
        }

        $this->promo_discount_amount = $this->promoDiscount;
        $this->total_amount = $this->sub_total - $this->promoDiscount;

        $this->discountMessage = 'Promo code applied! You saved ₱' . number_format($this->promoDiscount, 2) . '.';
        $this->errorMessage = null;

        $this->getAvailableRooms();
    }

    public function removePromoCode()
    {
        Log::info('removePromoCode method called');
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
    // --------------------------------------------- VALIDATIONS ----------------------------------------------- //

    /**
     * Validate the form data based on the current step of the process.
     *
     * This method performs data validation for the reservation form, ensuring that
     * required fields are provided and have valid values depending on the step the user is at.
     * The validation checks include required fields, correct data formats, and logical date constraints.
     *
     * @return void This method does not return any value, it only performs validation.
     */
    public function validateData()
    {

        // If the current step is 1, 2, or 3, validate basic reservation data
        if (in_array($this->currentStep, [1, 2, 3])) {
            $this->validate([
                // 'cart' must be an array and contain at least one item (room or activity)
                'cart' => 'required|array|min:1',
                // 'check_in_date' must be a valid date and not in the past
                'check_in_date' => 'required|date|after_or_equal:today',
                // 'check_out_date' must be a valid date and after the check-in date
                'check_out_date' => 'required|date|after:check_in_date',
            ]);
        }

        // Uncommented debugging line to display the cart (not currently needed)
        //dd($this->cart);

        // If the current step is 3, validate guest details
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
        }

        if ($this->currentStep == 4) {
            $this->validate([
                'terms' => 'accepted',
            ]);
        }
    }

    // ------------------------------------------ ADD ITEMS TO CART ------------------------------------------ //

    /**
     * Adds a room to the cart.
     *
     * This method adds a room to the cart if it's not already there. It ensures that
     * the room is not duplicated in the cart by checking the cart before adding it.
     * It also updates the number of adults and kids for that room and calls a method to
     * compute the total number of people (pax) in the cart.
     *
     * @param int $roomId The ID of the room to be added to the cart.
     * @return void This method does not return any value but modifies the cart.
     */

    public function addRoomToCart($roomId)
    {
        // Log::info('PHP ini loaded: ' . php_ini_loaded_file());
        // Log::info('curl.cainfo: ' . ini_get('curl.cainfo'));
        Log::info('addRoomToCart method called');

        // Resets any previous error messages
        $this->resetErrorBag();

        // Check if check-in and check-out dates are provided
        if (!$this->check_in_date || !$this->check_out_date) {
            $this->addError('cart', 'Please select check-in and check-out dates before adding a room.');
            return; // Exit the function if dates are not set
        }

        // Find the room using the provided roomId, or fail if it doesn't exist
        $room = Property::findOrFail($roomId);

        // Get the dynamic rate based on check-in/check-out
        $rate = $this->getDynamicRate($room);
        // dd('Rate used for cart:', $rate);

        // Check if the room is already in the cart
        foreach ($this->cart as $item) {
            if ($item['type'] === 'room' && $item['room_id'] == $roomId) {
                $this->addError('cart', 'This room is already in the cart.');
                return; // Exit the function to prevent adding a duplicate room
            }
        }

        $adults = (int) ($this->adults[$roomId] ?? 1);
        $kids = (int) ($this->kids[$roomId] ?? 0);
        $stayDuration = $this->getStayDurationProperty();
        $extraGuests = max(0, $adults + $kids - $room->ideal_guest);
        $roomAmount = $rate['amount'] * $stayDuration;
        $roomRateName = $rate['name'];
        $rate_id = $rate['rate_id'];

        $extraCharge = $room->extra_person_charge * $extraGuests * $stayDuration;

        $this->cart[] = [
            'type' => 'room',
            'room_id' => $room->id,
            'room_name' => $room->name_number,
            'extra_guest' => $extraGuests,
            'days' => $stayDuration,
            'adults' => $adults,
            'kids' => $kids,
            'roomAmount' => $roomAmount, // base rate * days
            'roomRateName' => $roomRateName,
            'rate_id' => $rate_id,
            'extra_charge' => $extraCharge, // extra_guest * extra_person_charge * days
            'total_amount' => $roomAmount + $extraCharge,
        ];

        // Optional debugging line to inspect the cart's content (can be removed in production)
        // dd($this->cart);

        // Call a method to compute the total number of people (pax) in the cart after adding the room
        $this->computeTotalPax();

        // Refresh room list with updated dynamic rates
        $this->getAvailableRooms();
    }


    /**
     * Adds an activity to the cart.
     *
     * This method adds an activity to the cart if it isn't already present. It ensures
     * that duplicate activities are not added by checking if the activity already exists
     * in the cart. It also calculates the total amount for the activity based on the quantity
     * and stores it in the cart.
     *
     * @param int $activityId The ID of the activity to be added to the cart.
     * @return void This method modifies the cart but does not return any value.
     */
    public function addActivityToCart($activityId)
    {
        // Resets any previous error messages
        $this->resetErrorBag();

        // Find the activity using the provided activityId, or fail if it doesn't exist
        $activity = Activity::findOrFail($activityId);

        // If the activity is already in the cart, show an error and return
        foreach ($this->cart as $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                $this->addError('cart', 'This activity is already in the cart.');
                return; // Exit the function to avoid adding the same activity again
            }
        }

        // Calculate the total amount for the activity based on the quantity
        $quantity = (int) ($this->quantity[$activityId] ?? 1); // Default to 1 if not set
        $activityAmount = $activity->amount * $quantity; // Calculate the total amount for the activity
        $activitystatus = $this->status[$activityId] = 'pending'; // Set the status of the activity to 'pending'

        // Add the activity to the cart if it isn't already present
        $this->cart[] = [
            'type' => 'activity', // Define the type as 'activity'
            'activity_id' => $activity->id, // Set the activity ID from the activity object
            'activity_name' => $activity->name, // Set the activity name
            'quantity' => $quantity, // Set the quantity from the input or default to 1
            'amount' => $activityAmount, // Set the calculated amount for the activity
            'status' => $activitystatus, // Set the status of the activity
        ];

        // $this->computeTotalAmount();
    }

    public function incrementActivity($activityId)
    {
        $activity = Activity::find($activityId);
        if (!$activity) return;

        // Get the current quantity or default to 1
        $currentQuantity = $this->quantity[$activityId] ?? 1;

        // Check if the current quantity is less than total_pax before incrementing
        if ($currentQuantity < $this->total_pax) {
            $this->quantity[$activityId] = $currentQuantity + 1;

            foreach ($this->cart as $index => $item) {
                if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                    $quantity = $this->quantity[$activityId];
                    $this->cart[$index]['quantity'] = $quantity;
                    $this->cart[$index]['amount'] = $activity->amount * $quantity;
                }
            }
        }
    }

    public function decrementActivity($activityId)
    {
        $activity = Activity::find($activityId);
        if (!$activity) return;

        // Decrease the quantity, but prevent going below 1
        $this->quantity[$activityId] = max(1, ($this->quantity[$activityId] ?? 1) - 1);

        // Update the cart with the new quantity and amount
        foreach ($this->cart as $index => $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                $quantity = $this->quantity[$activityId];
                $this->cart[$index]['quantity'] = $quantity;
                $this->cart[$index]['amount'] = $activity->amount * $quantity;
            }
        }
    }



    public function addMultipleGuests()
    {

        Log::Info('addMultipleGuests method called.');


        // Validate the guest details
        $this->validate([
            'guest_first_name' => 'required|string',
            'guest_middle_name' => 'nullable|string',
            'guest_last_name' => 'required|string',
            'guest_suffix' => 'nullable|string|max:10',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'nullable|in:local,foreigner',
            'guest_country_of_origin' => 'nullable|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);

        // Add the guest details to the guests array
        $this->guests[] = [
            'guest_first_name' => $this->guest_first_name,
            'guest_middle_name' => $this->guest_middle_name,
            'guest_last_name' => $this->guest_last_name,
            'guest_suffix' => $this->guest_suffix,
            'guest_type_id' => $this->guest_type_id,
            'guest_gender' => $this->guest_gender,
            'guest_residency' => $this->guest_residency,
            'guest_country_of_origin' => $this->guest_country_of_origin,
        ];

        $this->showGuestModal = false;

        // Optionally clear the form inputs after adding a guest
        $this->reset(['guest_first_name', 'guest_middle_name', 'guest_last_name', 'guest_suffix', 'guest_type_id', 'guest_gender', 'guest_residency', 'guest_country_of_origin']);
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

    // ------------------------------------------ REMOVE ITEMS FROM CART ----------------------------------- //

    /**
     * Remove an item from the cart based on the given type and item ID.
     *
     * This method filters out the item from the cart array, depending on whether
     * the item is an activity or a room. It ensures that the correct item is removed
     * from the cart based on its type and ID.
     *
     * @param mixed $type The type of item to remove ('activity' or 'room').
     * @param mixed $itemId The ID of the item to be removed.
     *
     * @return void This method does not return any value, it modifies the cart directly.
     */

    public function removeFromCart($type, $itemId)
    {

        Log::info('removeFromCart method called');

        // Filter the cart items to exclude the one with the matching type and ID
        $this->cart = array_filter($this->cart, function ($item) use ($type, $itemId) {
            if ($type === 'activity') {
                return $item['type'] !== 'activity' || $item['activity_id'] != $itemId;
            }

            // If the type is 'room', filter out the matching room ID
            if ($type === 'room') {
                return $item['type'] !== 'room' || $item['room_id'] != $itemId;
            }

            return true; // Fallback case (this should rarely be hit)
        });

        // Reindex the array after filtering to ensure keys are sequential
        $this->cart = array_values($this->cart);

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
            $this->currentStep = 1; // Go back to the first step
            $this->promoCode = '';
            $this->promoDiscount = 0;
            $this->discountMessage = null;
            $this->errorMessage = null;
            // session()->flash('error', 'No rooms left in your cart. Returning to the first step.');
        }
    }


    // ------------------------------------------ DATABASE INSERTION -------------------------------------- //

    /**
     * Finalize and register a reservation based on the current cart and user input.
     *
     * This method resets error messages, initiates a database transaction,
     * stores user and reservation information, and links selected rooms and
     * activities to the created transaction. The cart is expected to contain
     * both 'room' and 'activity' types. Adults, kids, and pax totals are calculated
     * from the cart data.
     *
     * On success, a success message is flashed to the session and the user is
     * redirected to the reservation form.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the reservation form route.
     */



    public function register()
    {

        $this->resetErrorBag(); // Reset any previous error messages

        $reservationData = []; // Initialize an empty array to store reservation data for email

        // ---------------------- DB:TRANSACTION STARTS HERE ------------------------ //

        DB::transaction(function () use (&$reservationData) {

            // If enable_deposit is true, retrieve the deposit percentage from the settings table
            $setting = Setting::first();

            $this->depositPercentage = $setting && $setting->enable_deposit_percentage
                ? $setting->deposit_percentage
                : 0;

            // Set the expiration hours for payment proof
            $this->expirationHours = $setting ? $setting->payment_proof_expiration_hours : 24; // default value

            // Find the promo code in the database
            $promo = PromoCode::where('code', $this->promoCode)->first();

            // Step 1: Create transaction user
            $transactionUser = TransactionUser::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'company_name' => $this->company_name,
                'country' => $this->country,
                'trn_user_type' => $this->trn_user_type,
            ]);

            // Step 2: Create transaction
            $transaction = Transaction::create([
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
                'total_amount' => $this->computeTotalAmount(),
                'deposit_amount' => $this->computeTotalAmount() * ($this->depositPercentage / 100),
                'heard_from' => $this->heard_from,
                'reservation_source' => $this->reservation_source,
                'transaction_status' => $this->transaction_status,
                'terms' => $this->terms,
            ]);

            // Step 3: Create invoice
            $invoice = Invoice::create([
                'transaction_id' => $transaction->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'invoice_type' => 'Room',
                'sub_total' => $this->computeTotalAmount(),
                'deposit_paid' => 0,
                'amount_paid' => 0,
                'balance_due' => $this->computeTotalAmount(),
                'due_date' => $this->check_out_date,
                'invoice_status' => 'pending',
            ]);

            // Step 4: Attach rooms and activities
            foreach ($this->cart as $item) {
                if ($item['type'] === 'room') {
                    $transaction->properties()->attach($item['room_id'], [
                        'adults' => $item['adults'],
                        'kids' => $item['kids'],
                        'days' => $item['days'],
                        'extra_charge' => $item['extra_charge'],
                        'amount' => $item['roomAmount'],
                        'total_amount' => $item['total_amount'],
                        'room_rate_id' => $item['rate_id']
                    ]);
                }

                if ($item['type'] === 'activity') {
                    $transaction->activities()->attach($item['activity_id'], [
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount'],
                    ]);
                }
            }

            // Step 5: Insert GuestDetails
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

            // Step 6: Create payment link using PayMongo API

            // ---------------------- PAYMONGO PAYMENT LINK INTEGRATION STARTS HERE ------------------------ //

            // Creates a new HTTP client instance (likely from GuzzleHttp\Client).
            // This client will be used to send HTTP requests to the PayMongo API.
            $client = new Client();

            // Retrieves the PayMongo secret key from .env.
            $apiKey = env('PAYMONGO_SECRET_KEY');

            // If depositPercentage is 0, we charge the full amount
            $baseAmount = $this->depositPercentage > 0
                ? $this->computeTotalAmount() * ($this->depositPercentage / 100)
                : $this->computeTotalAmount();

            $amountInCentavos = intval($baseAmount * 100);

            try {

                // Sends an HTTP POST request to PayMongo's API endpoint to create a checkout session (payment link).
                $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [

                    'headers' => [ // Extra pieces of information to be sent with the HTTP request.
                        'Accept' => 'application/json', // What type of data the client expects in response.
                        'Content-Type' => 'application/json', // What type of data is being sent in the request body.
                        'Authorization' => 'Basic ' . base64_encode($apiKey . ':'), // Access to paymongo, contains the secret key.
                    ],

                    // JSON payload to be sent in the request body.
                    'json' => [
                        'data' => [
                            'attributes' => [ //  Payment Session Settings
                                'send_email_receipt' => true, // Instructs PayMongo to email a receipt to the payer after a successful payment.
                                'show_description' => true, // Shows the overall description of the payment on the checkout page.
                                'show_line_items' => true, // Displays the breakdown of items (from line_items) on the PayMongo checkout page
                                'payment_method_types' => ['card', 'gcash', 'paymaya',], // Specifies the payment methods that are accepted for this checkout session.
                                'success_url' => route('guest.thank-you-page'), // This is where the user will be redirected after successful payment.
                                'cancel_url' => 'http://127.0.0.1:8000/payment-failed', // If the user cancels or the payment fails, they will be sent here.

                                'line_items' => [ // This is a list of what the user is paying for.
                                    [
                                        'currency' => 'PHP',
                                        'amount' => $amountInCentavos,  // e.g. 150000 for PHP 1,500.00
                                        'description' => 'Reservation ' . $transaction->transaction_number,
                                        'name' => 'Reservation Fee',
                                        'quantity' => 1,
                                    ],

                                ],

                                'description' => 'Reservation for ' . $this->first_name . ' ' . $this->last_name, // This is a general description for the transaction, shown to the payer.

                                // Additional metadata for tracking purposes.
                                'metadata' => [
                                    'invoice_id' => (string) $invoice->id,
                                    'payment_type' => 'Security Deposit',
                                    'notes' => 'Deposit for Reservation',
                                ]

                            ],
                        ],
                    ],


                ]);

                // Converts the JSON response from the PayMongo API into a PHP array.
                $responseData = json_decode($response->getBody(), true);

                // Retrieves the checkout_url from the response
                $paymentLink = $responseData['data']['attributes']['checkout_url'] ?? null;

                // Save payment link to transaction (optional)
                $transaction->update(['payment_link' => $paymentLink]);

                // If a promo code is used, increment the uses_count of Promo Code
                if ($promo) {
                    $promo->increment('uses_count');
                }
            } catch (\Exception $e) {

                Log::error('PayMongo link creation failed: ' . $e->getMessage());
                $paymentLink = null; // fallback

            }

            // ------------------------------------ EMAIL DATA ------------------------------------------ //

            $total = $this->computeTotalAmount();
            $deposit = $total * ($this->depositPercentage / 100);

            $reservationData = [
                'name' => $this->first_name . ' ' . $this->last_name,
                'transaction_number' => $transaction->transaction_number,
                'email' => $this->email,
                'invoice_number' => $invoice->invoice_number,
                'check_in' => $this->check_in_date,
                'check_out' => $this->check_out_date,
                // 'sub_total' => $this->sub_total,
                // 'convenience_fee' => $this->convenience_fee,
                // 'promo_discount_amount' => $this->promo_discount_amount,
                'total_amount' => $total,
                'deposit' => $deposit,
                'expirationHours' => $this->expirationHours,
                'payment_link' => $paymentLink,
                'branding_company_name' => $this->companyName,
                'logo_path' => $this->logoPath,
                'branding_company_email' => $this->companyEmail,
                'branding_company_contact' => $this->companyContact,
                'company_address' => $this->companyAddress,
                'facebook_link' => $this->facebookLink,
                'instagram_link' => $this->instagramLink,
            ];
        });

        // Step 8: Send confirmation email
        try {
            Mail::to($reservationData['email'])->send(new ReservationSubmittedMail($reservationData));
            Mail::to('rmscapstone26@gmail.com')->send(new NewReservationMail($reservationData));
        } catch (\Exception $e) {
            logger()->error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        // Step 9: Flash success message and redirect
        session()->flash('success', 'Reservation successfully submitted!');

        if (!empty($reservationData['payment_link'])) {
            session()->flash('success', 'Reservation submitted. You are being redirected to the payment page.');
            return redirect()->away($reservationData['payment_link']);
        } else {
            return redirect()->route('guest.proof-of-payment-page'); // Redirect to failback page if payment link is not available
        }
    }
}


 // public function getStayDurationProperty()
    // {
    //     // This allows you to access the method as a property
    //     if ($this->check_in_date && $this->check_out_date) {
    //         $in = Carbon::parse($this->check_in_date);
    //         $out = Carbon::parse($this->check_out_date);
    //         return $in->diffInDays($out);
    //     }
    //     return 0;
    // }
