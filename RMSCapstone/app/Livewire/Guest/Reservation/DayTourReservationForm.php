<?php

namespace App\Livewire\Guest\Reservation;

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
use App\Services\PayMongoService;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PaymentMethodService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;


#[Layout('layouts.guest')]

class DayTourReservationForm extends Component
{
    // ----------------------- GENERAL ---------------------------- //
    public $currentStep = 1;
    public $totalSteps = 3;
    public $reservation_type_id = 4; // Day Tour reservation type
    public $trn_user_type = 'guest';
    public $reservation_source = 'Website';
    public $transaction_status = 'pending';

    // ----------------------- TOUR SELECTION ---------------------------- //
    public $selectedTour = null;
    public $tourDate;
    public $adultCount = 1;
    public $kidCount = 0;
    public $totalGuests = 1;
    public $selectedRate = null;
    public $availableTours = [];

    // ----------------------- GUEST DETAILS ---------------------------- //
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $contact_number;
    public $company_name;
    public $country = 'Philippines';
    public $heard_from;

    // ----------------------- ADDITIONAL GUESTS ---------------------------- //
    public $guests = [];
    public $guest_first_name, $guest_middle_name, $guest_last_name, $guest_suffix, $guest_type_id;
    public $guest_gender, $guest_residency, $guest_country_of_origin;
    public $guest_types = [];
    public $showGuestModal = false;
    public $editingGuestIndex = null;
    public $editingGuest = [];

    // ----------------------- PRICE CALCULATIONS ---------------------------- //
    public $subtotal = 0;
    public $total_amount = 0;



    // ----------------------- PAYMENT & TERMS ---------------------------- //
    public $terms = 0;
    public $terms_and_conditions;
    public $enable_deposit_percentage = false;
    public $deposit_percentage;
    public $convenience_fee = 0;

    // ----------------------- SERVICES ---------------------------- //
    protected PayMongoService $payMongo;
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected PaymentMethodService $paymentMethodService;

    // ----------------------- BRANDING ---------------------------- //
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    public function boot(
        PayMongoService $payMongo,
        EmailService $emailService,
        BrandingService $brandingService,
        PaymentMethodService $paymentMethodService
    ) {
        $this->payMongo = $payMongo;
        $this->emailService = $emailService;
        $this->brandingService = $brandingService;
        $this->paymentMethodService = $paymentMethodService;
    }

    public function mount()
    {
        $this->loadBranding();
        $this->loadStaticData();
        $this->loadAvailableTours();
        $this->tourDate = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.guest.reservation.day-tour-reservation-form');
    }

    // ----------------------- STEP NAVIGATION ---------------------------- //
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

    // ----------------------- TOUR SELECTION LOGIC ---------------------------- //
    public function loadAvailableTours()
    {
        $this->availableTours = DayTour::active()
            ->with(['activeRates']) // Eager load the relationship
            ->get()
            ->map(function ($tour) {
                $tour->available_rates = $this->getAvailableRatesForTour($tour);
                return $tour;
            })
            ->filter(function ($tour) {
                // Only show tours that have available rates
                return $tour->available_rates->isNotEmpty();
            });
    }

    public function getAvailableRatesForTour($tour)
    {
        $dayType = $this->getDayType($this->tourDate);

        Log::info("Getting rates for tour: {$tour->id}, Date: {$this->tourDate}, Day Type: {$dayType}");

        // Ensure we have active rates relationship loaded
        if (!$tour->relationLoaded('activeRates')) {
            $tour->load('activeRates');
        }

        Log::info("Available rates count: " . $tour->activeRates->count());

        $availableRates = $tour->activeRates
            ->filter(function ($rate) use ($dayType) {
                $matches = $rate->day_type === $dayType;
                Log::info("Rate {$rate->id}: day_type={$rate->day_type}, matches={$matches}");
                return $matches;
            })
            ->values();

        Log::info("Filtered rates count: " . $availableRates->count());

        return $availableRates ?? collect();
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
    }

    public function calculateSubtotal()
    {
        if (!$this->selectedRate) {
            $this->subtotal = 0;
            $this->convenience_fee = 0;
            $this->total_amount = 0;
            return;
        }

        $adultTotal = $this->adultCount * $this->selectedRate->adult_rate;
        $kidTotal = $this->kidCount * $this->selectedRate->kid_rate;
        $this->subtotal = $adultTotal + $kidTotal;

        // Automatically calculate convenience fee (3%)
        $this->convenience_fee = $this->subtotal * 0.03;
        $this->total_amount = $this->subtotal + $this->convenience_fee;
    }

    public function updatedAdultCount()
    {
        $this->totalGuests = $this->adultCount + $this->kidCount;
        $this->validateGuestCount();
        $this->calculateSubtotal();
    }

    public function updatedKidCount()
    {
        $this->totalGuests = $this->adultCount + $this->kidCount;
        $this->validateGuestCount();
        $this->calculateSubtotal();
    }

    protected function validateGuestCount()
    {
        if ($this->selectedRate && $this->totalGuests > $this->selectedRate->max_guests) {
            $this->addError('guest_count', "Maximum guests for this rate is {$this->selectedRate->max_guests}");
        }
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


    // ----------------------- PRICE CALCULATIONS ---------------------------- //
    public function getSubtotalProperty()
    {
        return $this->subtotal;
    }

    public function getTotalAmountProperty()
    {
        return $this->total_amount;
    }

    public function getConvenienceFeeProperty()
    {
        return $this->convenience_fee;
    }

    // ----------------------- GUEST MANAGEMENT ---------------------------- //
    public function openGuestModal()
    {
        $this->showGuestModal = true;
    }

    public function closeGuestModal()
    {
        $this->showGuestModal = false;
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
        $this->showGuestModal = false;
        $this->resetGuestInputFields();
    }

    public function editGuest($index)
    {
        $this->editingGuestIndex = $index;
        $this->editingGuest = $this->guests[$index];
        $this->showGuestModal = true;
    }

    public function updateGuest()
    {
        if (!is_null($this->editingGuestIndex)) {
            $this->guests[$this->editingGuestIndex] = $this->editingGuest;
        }

        $this->showGuestModal = false;
        $this->reset('editingGuestIndex', 'editingGuest');
    }

    public function deleteGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
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
    public function register()
    {
        $this->resetErrorBag();
        $this->validateData();

        $reservationData = [];

        DB::transaction(function () use (&$reservationData) {
            // Create Transaction User
            $transactionUser = $this->createTransactionUser();

            // Create Transaction
            $transaction = $this->createTransaction($transactionUser);

            // Create Invoice
            $invoice = $this->createInvoice($transaction);

            // Insert Guest Details
            $this->insertGuestDetails($transaction);

            // Prepare PayMongo payload
            $amountInCentavos = intval($this->total_amount * 100);
            $payload = $this->preparePayMongoPayload($amountInCentavos, $transaction, $invoice);

            try {
                $response = $this->payMongo->createCheckoutSession($payload);
                $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

                if ($paymentLink) {
                    $transaction->update(['payment_link' => $paymentLink]);
                }
            } catch (\Exception $e) {
                Log::error('PayMongo link creation failed: ' . $e->getMessage());
                $paymentLink = null;
            }

            // Prepare reservation data for email
            $reservationData = $this->prepareReservationData($transaction, $invoice, $paymentLink);
        });

        // Send confirmation email
        try {
            $pdfContent = $this->generateAvailablePaymentMethods();
            $this->emailService->sendDayTourReservationEmails($reservationData, $pdfContent);
        } catch (\Exception $e) {
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        session()->flash('success', 'Day Tour reservation successfully submitted!');

        // Redirect to payment page if available
        if (!empty($reservationData['payment_link'])) {
            session()->flash('success', 'Reservation submitted. You are being redirected to the payment page.');
            return redirect()->away($reservationData['payment_link']);
        }

        return redirect()->route('guest.proof-of-payment-page');
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
            'start_datetime' => $this->tourDate,
            'end_datetime' => $this->tourDate,
            'total_adults' => $this->adultCount,
            'total_kids' => $this->kidCount,
            'pax' => $this->totalGuests,
            'sub_total' => $this->subtotal,
            'total_amount' => $this->total_amount,
            'convenience_fee' => $this->convenience_fee,
            'heard_from' => $this->heard_from,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            'terms' => $this->terms,
        ]);
    }

    protected function createInvoice(Transaction $transaction): Invoice
    {
        return Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => 'INV-DT-' . strtoupper(Str::random(8)),
            'invoice_type' => 'Day_Tour',
            'base_subtotal' => $this->subtotal,
            'sub_total' => $this->total_amount,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $this->total_amount,
            'due_date' => $this->tourDate,
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

    // ----------------------- PAYMONGO & EMAIL ---------------------------- //
    protected function preparePayMongoPayload(int $amountInCentavos, $transaction, $invoice): array
    {
        return [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ['gcash', 'paymaya'],
                    'success_url' => route('guest.thank-you-page'),
                    'cancel_url' => url('/payment-failed'),
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => $amountInCentavos,
                            'description' => 'Day Tour reservation fee for booking #' . $transaction->transaction_number,
                            'name' => 'Day Tour Reservation Fee',
                            'quantity' => 1,
                        ],
                    ],
                    'description' => 'Day Tour Reservation for ' . $this->first_name . ' ' . $this->last_name,
                    'metadata' => [
                        'invoice_id' => (string) $invoice->id,
                        'payment_type' => 'Day Tour Reservation',
                        'notes' => 'Payment for Day Tour',
                    ],
                ],
            ],
        ];
    }

    protected function prepareReservationData($transaction, $invoice, $paymentLink): array
    {
        return [
            'name' => $this->first_name . ' ' . $this->last_name,
            'transaction_number' => $transaction->transaction_number,
            'email' => $this->email,
            'invoice_number' => $invoice->invoice_number,
            'tour_date' => $this->tourDate,
            'tour_name' => $this->selectedTour->name,
            'rate_name' => $this->selectedRate->rate_name,
            'adult_count' => $this->adultCount,
            'kid_count' => $this->kidCount,
            'adult_rate' => $this->selectedRate->adult_rate,
            'kid_rate' => $this->selectedRate->kid_rate,
            'subtotal' => $this->subtotal,
            'convenience_fee' => $this->convenience_fee,
            'total_amount' => $this->total_amount,
            'payment_link' => $paymentLink,
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
        if ($this->currentStep == 1) {
            $this->validate([
                'selectedTour' => 'required',
                'selectedRate' => 'required',
                'tourDate' => 'required|date|after_or_equal:today',
                'adultCount' => 'required|integer|min:1',
                'kidCount' => 'required|integer|min:0',
            ], [
                'selectedTour.required' => 'Please select a day tour.',
                'selectedRate.required' => 'Please select a rate for the tour.',
            ]);
        }

        if ($this->currentStep == 2) {
            $this->validate([
                'first_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
                'middle_name' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
                'last_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
                'email' => 'required|email',
                'contact_number' => 'required|string|regex:/^[0-9]{11}$/',
                'country' => 'required|string',
                'heard_from' => 'required|in:Facebook,Instagram,Tiktok,Youtube,Google',
            ]);
        }

        if ($this->currentStep == 3) {
            $this->validate([
                'terms' => 'accepted',
            ]);
        }
    }

    protected function validateGuestData()
    {
        return $this->validate([
            'guest_first_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_middle_name' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_last_name' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
            'guest_suffix' => 'nullable|string|max:10|regex:/^[A-Za-z\s\-]+$/',
            'guest_gender' => 'nullable|in:male,female,other',
            'guest_residency' => 'nullable|in:local,foreigner',
            'guest_country_of_origin' => 'nullable|string|max:100',
            'guest_type_id' => 'required|exists:trn_guest_type,id',
        ]);
    }

    // ----------------------- LOADERS ---------------------------- //
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
        $this->enable_deposit_percentage = $branding['enable_deposit_percentage'];
        $this->deposit_percentage = $branding['deposit_percentage'];
        $this->terms_and_conditions = $branding['terms_and_conditions'] ?? '';
    }

    protected function loadStaticData()
    {
        $this->guest_types = GuestType::all();
    }
}
