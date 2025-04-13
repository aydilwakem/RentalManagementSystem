<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Transaction;
use App\Models\Room;
use App\Models\Activity;

class ReservationForm extends Component
{
    use WithFileUploads;

    public $room_id;
    public $activity_id;
    public $rooms;
    public $activities;

    public $first_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $payment_reference_number;
    public $payment_screenshot;
    public $terms;

    public $totalSteps = 4;
    public $currentStep = 1;

    public function mount()
    {
        // Fetch all Rooms
        $this->rooms = Room::all();
        // Fetch all Activities
        $this->activities = Activity::all();

        $this->currentStep = 1;
    }

    public function selectRoom($roomId)
    {
        $this->room_id = $roomId; // Set the room_id to the selected room's ID
    }

    public function increaseStep()
    {
        $this->resetErrorBag(); // Clears previous validation error messages stored in the component

        $this->validateData(); // Runs the validateData function before proceeding to the next step

        $this->currentStep++;
        if ($this->currentStep > $this->totalSteps) {
            $this->currentStep = $this->totalSteps;
        }
    }

    public function decreaseStep()
    {
        $this->currentStep--;
        if ($this->currentStep < 1) {
            $this->currentStep = 1;
        }
    }

    public function validateData()
    {
        // Step 1 - Book a Room
        if ($this->currentStep == 1) {
            $this->validate([
                'room_id' => 'required|exists:prd_rooms,id',
            ]);
        }

        // Step 2 - Choose Activity
        elseif ($this->currentStep == 2) {
            $this->validate([
                'activity_id' => 'nullable|integer|exists:prd_activities,id',
            ]);
        }

        // Step 3 - Guest Details
        elseif ($this->currentStep == 3) {
            $this->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'contact_number' => 'required|string',
            ]);
        }
    }

    public function register()
    {
        $this->resetErrorBag();

        // Step 4 - Payment Details
        if ($this->currentStep == 4) {
            $this->validate([
                'payment_screenshot' => 'required|image|max:1024',
                'payment_reference_number' => 'nullable|string',
                'terms' => 'accepted',
            ]);

            // Store payment screenshot
            $paymentScreenshotPath = $this->payment_screenshot->store('payment_screenshots', 'public');

            // Save the booking/reservation
            $transaction = Transaction::create([
                'room_id' => $this->room_id,
                'activity_id' => $this->activity_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'contact_number' => $this->contact_number,
                'payment_screenshot' => $paymentScreenshotPath,
                'payment_reference_number' => $this->payment_reference_number,
            ]);

            // Store the screenshot path in the session (if needed for other steps)
            session()->put('payment_screenshot', $paymentScreenshotPath);

            session()->flash('success', 'Registration completed successfully!');

            // Redirect back to reservations list
            return redirect()->route('guest.reservation-form');
        }
    }

    public function render()
    {
        return view('livewire.guest.reservation-form', [
            'rooms' => $this->rooms,
            'activities' => $this->activities,
        ]);
    }
}
