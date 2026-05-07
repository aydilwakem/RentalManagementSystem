<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\DayTour;
use App\Models\DayTourRate;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\GuestDetail;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\GuestType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PaymentMethodService;
use Barryvdh\DomPDF\Facade\Pdf;
use PragmaRX\Countries\Package\Countries;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CreateDayTourReservation extends Component
{
    public $reservation_type_id = 4; // Day Tour reservation type
    public $transaction_number;
    public $trn_user_type = 'guest';
    public $reservation_source;
    public $transaction_status = 'pending';

    // Tour related properties
    public $tour_date;
    public $selectedTour = null;
    public $selectedRate = null;
    public $adultCount = 1;
    public $kidCount = 0;
    public $totalGuests = 1;
    public $availableTours = [];

    // Primary Guest related properties
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $country;
    public $heard_from;
    // public $terms = 1;

    // Additional Guest related properties
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

    // Invoice related properties
    public $invoice_number;

    // Price calculations
    public $subtotal = 0;
    public $total_amount = 0;
    // public $convenience_fee = 0;

    // Modals
    public $guestModal = false;
    public $editGuestModal = false;

    // Services
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected PaymentMethodService $paymentMethodService;

    // Branding
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    public function boot(
        EmailService $emailService,
        BrandingService $brandingService,
        PaymentMethodService $paymentMethodService
    ) {
        $this->emailService = $emailService;
        $this->brandingService = $brandingService;
        $this->paymentMethodService = $paymentMethodService;
    }

    public function render()
    {
        return view('livewire.admin.reservations.create-day-tour-reservation');
    }

    public function mount(): void
    {
        $this->initializeDates();
        $this->loadStaticData();
        $this->loadAvailableTours();
        $this->loadBranding();
        $this->initializeCountries();
    }

    // ----------------------- TOUR SELECTION LOGIC ---------------------------- //
    public function loadAvailableTours()
    {
        $this->availableTours = DayTour::active()
            ->with(['activeRates']) // Eager load the relationship
            ->get()
            ->map(function ($tour) {
                $tour->available_rates = $this->getAvailableRatesForTour($tour);
                return $tour;
            });
    }

    public function getAvailableRatesForTour($tour)
    {
        $dayType = $this->getDayType($this->tour_date);

        if (!$tour->relationLoaded('activeRates')) {
            $tour->load('activeRates');
        }

        return $tour->activeRates
            ->filter(function ($rate) use ($dayType) {
                return $rate->day_type === $dayType;
            })
            ->values();
    }

    public function getDayType($date)
    {
        $carbonDate = Carbon::parse($date);
        return $carbonDate->isWeekend() ? 'weekend' : 'weekday';
    }

    public function selectTour($tourId, $rateId = null)
    {
        $this->selectedTour = DayTour::find($tourId);

        if ($this->selectedTour) {
            $availableRates = $this->getAvailableRatesForTour($this->selectedTour);

            if ($rateId) {
                $this->selectedRate = $availableRates->firstWhere('id', $rateId);
            } else {
                $this->selectedRate = $availableRates->first();
            }
        }
        $this->calculateSubtotal();
    }

    public function updatedTourDate()
    {
        $this->selectedTour = null;
        $this->selectedRate = null;
        $this->loadAvailableTours();
        $this->calculateSubtotal();
    }

    public function calculateSubtotal()
    {
        if (!$this->selectedRate) {
            $this->subtotal = 0;
            // $this->convenience_fee = 0;
            $this->total_amount = 0;
            return;
        }

        $adultTotal = $this->adultCount * $this->selectedRate->adult_rate;
        $kidTotal = $this->kidCount * $this->selectedRate->kid_rate;
        $this->subtotal = $adultTotal + $kidTotal;

        // $this->convenience_fee = $this->subtotal * 0.03;
        // $this->total_amount = $this->subtotal + $this->convenience_fee;
        $this->total_amount = $this->subtotal;
    }

    public function incrementAdult()
    {
        $this->adultCount++;
        $this->totalGuests = $this->adultCount + $this->kidCount;
        $this->validateGuestCount();
        $this->calculateSubtotal();
    }

    public function decrementAdult()
    {
        if ($this->adultCount > 1) {
            $this->adultCount--;
            $this->totalGuests = $this->adultCount + $this->kidCount;
            $this->validateGuestCount();
            $this->calculateSubtotal();
        }
    }

    public function incrementKid()
    {
        $this->kidCount++;
        $this->totalGuests = $this->adultCount + $this->kidCount;
        $this->validateGuestCount();
        $this->calculateSubtotal();
    }

    public function decrementKid()
    {
        if ($this->kidCount > 0) {
            $this->kidCount--;
            $this->totalGuests = $this->adultCount + $this->kidCount;
            $this->validateGuestCount();
            $this->calculateSubtotal();
        }
    }

    protected function validateGuestCount()
    {
        if ($this->selectedRate && $this->totalGuests > $this->selectedRate->max_guests) {
            $this->addError('guest_count', "Maximum guests for this rate is {$this->selectedRate->max_guests}");
        }
    }

    // ----------------------- GUEST MANAGEMENT ---------------------------- //
    public function openGuestModal()
    {
        $this->resetErrorBag();
        $this->guestModal = true;
    }

    public function closeGuestModal()
    {
        $this->guestModal = false;
        $this->resetGuestInputFields();
    }

    public function addMultipleGuests()
    {
        $this->validateGuestData();

        if (count($this->guests) >= ($this->totalGuests - 1)) {
            $this->addError('guests', 'Maximum number of additional guests reached.');
            return;
        }

        $this->guests[] = $this->makeGuestArray();
        $this->guestModal = false;
        $this->resetGuestInputFields();
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

    public function updatedGuestResidency($value)
    {
        if ($value === 'local') {
            $this->guest_country_of_origin = 'Philippines';
        } else {
            $this->guest_country_of_origin = '';
        }
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

    protected function resetGuestInputFields()
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

    // ----------------------- RESERVATION CREATION ---------------------------- //
    public function createDayTourReservation()
    {
        Log::info('CreateDayTourReservation method called');

        $this->resetErrorBag();
        $this->validateData();

        $reservationData = [];
        $transaction = null;

        DB::transaction(function () use (&$reservationData, &$transaction) {
            Log::info('Starting day tour reservation creation transaction...');

            // Create Transaction User
            $transactionUser = $this->createTransactionUser();

            // Create Transaction
            $transaction = $this->createTransaction($transactionUser);

            // Create Invoice
            $invoice = $this->createInvoice($transaction);

            // Insert Guest Details
            $this->insertGuestDetails($transaction);

            $paymentLink = null;
            // Prepare reservation data for email
            $reservationData = $this->prepareReservationData($transaction, $invoice, $paymentLink);
        });

        // Attempt to send confirmation emails
        try {
            $pdfContent = $this->generateAvailablePaymentMethods();
            $this->emailService->sendDayTourReservationEmails($reservationData, $pdfContent);
        } catch (\Exception $e) {
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        if (!$transaction) {
            session()->flash('error', 'Something went wrong while creating reservation.');
            return redirect()->route('admin.daytour-reservations-list');
        }

        session()->flash('success', 'Day Tour reservation successfully created!');
        return redirect()->route('admin.daytour-reservations-list');
    }

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

    protected function createTransaction(TransactionUser $transactionUser): Transaction
    {
        return Transaction::create([
            'transaction_number' => 'DT-' . strtoupper(Str::random(8)),
            'reservation_type_id' => $this->reservation_type_id,
            'created_by' => $transactionUser->id,
            'start_datetime' => $this->tour_date,
            'end_datetime' => $this->tour_date,
            'total_adults' => $this->adultCount,
            'total_kids' => $this->kidCount,
            'pax' => $this->totalGuests,
            'sub_total' => $this->subtotal,
            'total_amount' => $this->total_amount,
            // 'convenience_fee' => $this->convenience_fee,
            'heard_from' => $this->heard_from,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            // 'terms' => $this->terms,

            // Day Tour specific fields
            'day_tour_id' => $this->selectedTour->id,
            'day_tour_rate_id' => $this->selectedRate->id,
            'day_tour_rate_type' => $this->selectedRate->rate_type,

        ]);
    }

    protected function createInvoice(Transaction $transaction): Invoice
    {
        return Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => 'INV-DT-' . strtoupper(Str::random(8)),
            'invoice_type' => 'Day_Tour',
            'base_subtotal' => $this->total_amount,
            'sub_total' => $this->subtotal,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $this->total_amount,
            'due_date' => $this->tour_date,
            'invoice_status' => 'pending',
        ]);
    }

    protected function insertGuestDetails(Transaction $transaction): void
    {
        // Insert primary guest
        GuestDetail::create([
            'transaction_id' => $transaction->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'guest_type_id' => 1, // Primary guest type
            'country_of_origin' => $this->country ?? 'Philippines',
        ]);

        // Insert additional guests
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



    protected function prepareReservationData($transaction, $invoice, $paymentLink): array
    {
        return [
            'name' => $this->first_name . ' ' . $this->last_name,
            'transaction_number' => $transaction->transaction_number,
            'email' => $this->email,
            'invoice_number' => $invoice->invoice_number,
            'tour_date' => $this->tour_date,
            'tour_name' => $this->selectedTour->name,
            'rate_name' => $this->selectedRate->rate_name,
            'adult_count' => $this->adultCount,
            'kid_count' => $this->kidCount,
            'adult_rate' => $this->selectedRate->adult_rate,
            'kid_rate' => $this->selectedRate->kid_rate,
            'subtotal' => $this->subtotal,
            'convenience_fee' => $this->convenience_fee ?? 0,
            'total_amount' => $this->total_amount,
            'payment_link' => $paymentLink ?? null,
            'branding_company_name' => $this->companyName,
            'logo_path' => $this->logoPath,
            'branding_company_email' => $this->companyEmail,
            'branding_company_contact' => $this->companyContact,
            'company_address' => $this->companyAddress,
            'facebook_link' => $this->facebookLink,
            'instagram_link' => $this->instagramLink,
        ];
    }

    public function generateAvailablePaymentMethods()
    {
        $paymentMethods = $this->paymentMethodService->getPaymentMethodsData();
        $pdf = Pdf::loadView('livewire.admin.reports.available-payment-methods', compact('paymentMethods'));
        return $pdf->output();
    }

    // ----------------------- VALIDATION ---------------------------- //
    public function validateData()
    {
        $this->validate([
            'selectedTour' => 'required',
            'selectedRate' => 'required',
            'tour_date' => 'required|date|after_or_equal:today',
            'adultCount' => 'nullable|integer|min:1',
            'kidCount' => 'nullable|integer|min:0',
            'first_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'middle_name' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
            'last_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'email' => 'required|email',
            'contact_number' => 'required|string|regex:/^[0-9]{11}$/',
            'country' => 'required|string',
            'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
            'reservation_source' => 'required|in:Website,AirBnb,Facebook Messenger,Instagram,Walk-In,Other',
            // 'terms' => 'required|accepted',
        ], [
            'selectedTour.required' => 'Please select a day tour.',
            'selectedRate.required' => 'Please select a rate for the tour.',
        ]);
    }

    protected function validateGuestData()
    {
        return $this->validate([
            'guest_first_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_middle_name' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_last_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_suffix' => 'nullable|string|max:10|regex:/^[A-Za-z\s\-]+$/',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'required|in:local,foreigner',
            'guest_country_of_origin' => 'required|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);
    }

    // ----------------------- HELPERS ---------------------------- //
    protected function initializeDates()
    {
        $this->tour_date = now()->format('Y-m-d');
    }

    protected function initializeCountries()
    {
        $this->countries = Countries::all()->pluck('name.common')->sort()->values()->toArray();
        $this->country = 'Philippines';
        $this->guest_country_of_origin = 'Philippines';
    }

    protected function loadStaticData()
    {
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

    protected function generateInvoiceNumber(): string
    {
        return 'INV-DT-' . strtoupper(Str::random(8));
    }

    protected function generateTransactionNumber(): string
    {
        return 'DT-' . strtoupper(Str::random(8));
    }
}
