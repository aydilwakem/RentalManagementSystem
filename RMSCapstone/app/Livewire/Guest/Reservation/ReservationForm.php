<?php

namespace App\Livewire\Guest\Reservation;

use Livewire\Component;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationForm extends Component
{
    public $reservation_type_id = 2; // This reservation is for Rooms
    public $trn_user_type = 'guest'; // This reservation is made by a 'guest'
    public $total_pax = 0; //  Running total for the total pax
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';
    public $cart = []; // Keeps all the selected rooms and activities


    // ----------------------- ROOMS ---------------------------- // 
    public $check_in_date;
    public $check_out_date;
    public $rooms = [];
    public $adults = [];
    public $kids = [];

    // --------------------- ACTIVITIES ------------------------- // 
    public $activities = [];
    public $quantity = [];
    public $activity_id;

    // ------------------- GUEST DETAIL ------------------------ // 
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $country;
    public $heard_from = 'Facebook';

    // ------------------- NAVIGATION STEPS -------------------- // 

    public $currentStep = 1;
    public $totalSteps = 4;
    protected $queryString = ['currentStep'];



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
        $this->rooms = Property::ofType('Room')->where('property_status', 'available')->get();
        $this->activities = Activity::all();

        // Set default check-in to now, and check-out to +1 day
        $this->check_in_date = Carbon::now('Asia/Manila')->format('Y-m-d\TH:i');
        $this->check_out_date = Carbon::now('Asia/Manila')->addDay()->format('Y-m-d\TH:i');

        $this->currentStep = 1;
    }


    /**
     * Renders the Livewire reservation form view for guests.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
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
        if (Str::startsWith($property, 'adults.') || Str::startsWith($property, 'kids.')) {

            // adults.2 or kids.2

            $roomId = explode('.', $property)[1];
            // extract the room id from the adults.'room_id'
            // "adults.{{ $room->id }}"
            // adults. - seperated by a dot
            // extracted room id = 2

            foreach ($this->cart as $index => $item) { // prints all the item in the cart index - 0 item [ room_id - 1, adults - 2, kids - 3 ] 
                if ($item['type'] === 'room' && $item['room_id'] == $roomId) { // if item[2] ==(int) 2 then change the adults or kids
                    $this->cart[$index]['adults'] = $this->adults[$roomId] ?? 0; // change adults or if not make it 0
                    $this->cart[$index]['kids'] = $this->kids[$roomId] ?? 0; // change kids or if not make it 0
                }
            }

            // Triggers compute total pax method
            $this->computeTotalPax();
        }

        // Other update logic
        if (in_array($property, ['check_in_date', 'check_out_date'])) {
            $this->getAvailableRooms();
        }

        if (Str::startsWith($property, 'quantity.')) {
            // Extract the activity ID from the property name
            $activityId = explode('.', $property)[1];

            // Update the cart item's quantity dynamically
            foreach ($this->cart as $index => $item) {
                if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                    $this->cart[$index]['quantity'] = $this->quantity[$activityId] ?? 0;
                }
            }
        }
    }


    /**
     * Compute the number of days between check-in and check-out.
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
            return $in->diffInDays($out);
        }
        return 0;
    }


    /**
     * Fetch available rooms for the selected check-in and check-out dates.
     * 
     * Filters out rooms already booked during the specified range by checking
     * overlapping transactions. Only available rooms of type 'Room' are returned.
     * 
     * @return void
     */

    public function getAvailableRooms()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);
        $checkOut = \Carbon\Carbon::parse($this->check_out_date);

        $this->rooms = Property::ofType('Room')
            ->where('property_status', 'available')
            ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('start_datetime', '<', $checkOut)
                        ->where('end_datetime', '>', $checkIn);
                });
            })
            ->get();
    }

    /**
     * Computes the total number of guests (pax) by summing all adults and kids from the cart.
     * 
     * Loops through the `adults` and `kids` arrays and adds their values to get the total pax.
     * Updates the `total_pax` property accordingly.
     * 
     * @return void
     */

    public function computeTotalPax()
    {

        // FIX: This should check the rooms in the cart and then get the sum of adults and kids

        $total = 0; // running total of the pax

        // prints out all the adults as the room_id and count
        // ex. there are two rooms in the cart
        // room id | count
        // 1       | 3
        // 2       | 2

        foreach ($this->adults as $roomId => $count) {
            $total += (int) $count;
        }

        // total + count = total
        // room 1 - 0 + 3  = 3 
        // room 2 - 3 + 2  = 5 
        // total = 5 


        foreach ($this->kids as $roomId => $count) {
            $total += (int) $count;
        }

        $this->total_pax = $total;
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

        // Find the room using the provided roomId, or fail if it doesn't exist
        $room = Property::findOrFail($roomId);

        // Check if the room is already in the cart
        foreach ($this->cart as $item) {
            // If the room is already in the cart, show an error and return
            if ($item['type'] === 'room' && $item['room_id'] == $roomId) {
                $this->addError('cart', 'This room is already in the cart.');
                return; // Exit the function to prevent adding a duplicate room
            }
        }

        // Add the room to the cart if it isn't already there
        $this->cart[] = [
            'type' => 'room', // Define the type as 'room'
            'room_id' => $room->id, // Set the room ID from the room object
            'room_name' => $room->name_number, // Set the room's name or number
            'adults' => (int) ($this->adults[$roomId] ?? 0),   // Cast the number of adults to an integer (default to 0 if not set) 
            'kids' => (int) ($this->kids[$roomId] ?? 0),      // Cast the number of kids to an integer (default to 0 if not set)
        ];

        // Optional debugging line to inspect the cart's content (can be removed in production)
        // dd($this->cart);

        // Call a method to compute the total number of people (pax) in the cart after adding the room
        $this->computeTotalPax();
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
        // Find the activity using the provided activityId, or fail if it doesn't exist
        $activity = Activity::findOrFail($activityId);

        // If the activity is already in the cart, show an error and return
        foreach ($this->cart as $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                $this->addError('cart', 'This activity is already in the cart.');
                return; // Exit the function to avoid adding the same activity again
            }
        }

        // Add the activity to the cart if it isn't already present
        $this->cart[] = [
            'type' => 'activity',  // Define the type as 'activity'
            'activity_id' => $activity->id,  // Set the activity ID from the activity object
            'activity_name' => $activity->name,  // Set the activity name
            'quantity' => $this->quantity[$activityId] ?? 1,  // Set the quantity (default to 1 if not set)
            'amount' => $activity->amount * ($this->quantity[$activityId] ?? 1),  // Calculate the total amount based on the quantity
        ];
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

        // dd($this->cart);

        $this->resetErrorBag();

        DB::transaction(function () {
            $transactionUser = TransactionUser::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'country' => $this->country,
                'trn_user_type' => $this->trn_user_type,
            ]);

            $transaction = Transaction::create([
                'reservation_type_id' => $this->reservation_type_id,
                'created_by' => $transactionUser->id,
                'start_datetime' => $this->check_in_date,
                'end_datetime' => $this->check_out_date,
                'total_adults' => collect($this->cart)->sum('adults'), // goes through each item and adds the value of adults.
                'total_kids' => collect($this->cart)->sum('kids'), // goes through each item and adds the value of kids.
                'pax' => $this->total_pax,
                'total_amount' => 0, // calculate this if needed
                'heard_from' => $this->heard_from,
                'reservation_source' => $this->reservation_source,
                'transaction_status' => $this->transaction_status,
            ]);

            foreach ($this->cart as $item) {
                if ($item['type'] === 'room') {
                    $transaction->properties()->attach($item['room_id'], [
                        'adults' => $item['adults'],
                        'kids' => $item['kids'],
                    ]);
                }

                if ($item['type'] === 'activity') {
                    $transaction->activities()->attach($item['activity_id'], [
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        });

        session()->flash('success', 'Reservation successfully submitted!');
        return redirect()->route('guest.reservation-form');
    }
}
