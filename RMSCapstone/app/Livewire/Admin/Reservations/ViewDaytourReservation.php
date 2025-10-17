<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\GuestType;
use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\GuestDetail;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfficialReceiptMail;
use App\Mail\RequestRemainingBalanceMail;
use App\Models\DayTourRate;
use App\Models\DiscountType;
use App\Models\Invoice;
use App\Models\InvoiceDiscount;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PayMongoService;
use App\Services\ServiceBag;
use App\Services\TransactionLoader;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\PaymentService;
use App\Services\GuestDetailService;
use App\Services\PaymentMethodService;

#[Layout('layouts.app')]
class ViewDaytourReservation extends Component
{
    use WithFileUploads;

    // ---------------- RELATIONSHIPS ------------------ //
    public $createPaymentModal = false;
    public $amount_paid;
    public $payment_date;
    public $payment_type;
    public $notes;
    public $transaction;
    public $transactionUser;
    public $invoice;
    public $guestDetails;
    public $payments;
    public $guestTypes;

    // ---------------- COMPUTATIONS ------------------ //
    public $total_pax;

    // --------- PAYMENT RELATED PROPERTIES ----------- //
    public $invoice_id;
    public $mode_of_payment;
    public $payment_status;
    public $currency;
    public $verified_at;

    // ---------------- MODALS ------------------ //
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;

    // ---------------- BRANDING ------------------ //
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    // ---------------- INVOICE ------------------ //
    public $sub_total;
    public $balance_due;
    public $convenienceFeeTotal;

    // ---------------- RECEIPT ------------------ //
    public $receipt;
    public $receiptNumber;

    public $activeModal;

    // ---------------- EDITING ------------------ //
    public $showEditGuestModal = false;
    public $editingGuestId, $editingFirstName, $editingMiddleName, $editingLastName, $editingSuffix, $editingGender, $editingResidency, $editingCountryOfOrigin, $editingGuestTypeId;

    public $guest = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'gender' => null,
        'residency' => null,
        'country_of_origin' => null,
        'guest_type_id' => null,
    ];

    public $countries;
    protected ServiceBag $service;
    protected TransactionLoader $loader;
    protected InvoiceService $invoiceService;
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected NotificationService $notificationService;
    protected PaymongoService $payMongoService;
    protected ReceiptService $receiptService;
    protected PaymentService $paymentService;
    protected GuestDetailService $guestDetailService;

    public $allItems = [];

    public $payment_screenshot;
    public $payment_methods;
    public $payment_method_id;
    public $request_reply;


    // ---------------- ROOM RELATED PROPERTIES ------------------ //
    public $selectedRooms = [];
    public $availableRooms = [];
    public $showAddRoomModal = false;
    public $hasRoomRate = false;


    public function render()
    {
        $this->guestTypes = GuestType::all();

        return view('livewire.admin.reservations.view-daytour-reservation', [
            'guestTypes' => $this->guestTypes,
            'payment_methods' => $this->payment_methods,
        ]);
    }

    public function boot(ServiceBag $services)
    {
        $this->loader = $services->loader;
        $this->invoiceService = $services->invoiceService;
        $this->emailService = $services->emailService;
        $this->brandingService = $services->brandingService;
        $this->payMongoService = $services->payMongoService;
        $this->notificationService = $services->notificationService;
        $this->receiptService = $services->receiptService;
        $this->paymentService = $services->paymentService;
        $this->guestDetailService = $services->guestDetailService;
    }

    public function mount(Transaction $transaction)
    {
        // Loads all transaction-related relationships
        $data = $this->loader->load($transaction);
        $this->transaction = $data['transaction'];
        $this->transactionUser = $data['transactionUser'];
        $this->invoice = $data['invoice'];
        $this->guestDetails = $data['guestDetails'];
        $this->payments = $data['payments'];

        // Sets default dates for payment
        $now = Carbon::now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');

        // Load existing rooms
        $this->loadExistingRooms();
        
        // Check if rate type is 'with_room'
        $this->checkRoomRateType();

        $this->guestTypes = GuestType::all();
        $this->payment_methods = PaymentMethod::all();

        $this->loadAllInvoiceItems();

        $this->countries = \PragmaRX\Countries\Package\Countries::all()->pluck('name.common')->sort()->values()->toArray();
        $this->guest['country_of_origin'] = $this->guest['country_of_origin'] ?? 'Philippines';
    }

    public function loadAllInvoiceItems()
    {
        $items = [];

        // Day Tour base charge
        $items[] = [
            'type' => 'daytour',
            'name' => 'Day Tour Package',
            'quantity' => 1,
            'days' => null,
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => $this->transaction->sub_total,
            'total' => $this->transaction->sub_total,
            'created_at' => $this->transaction->created_at,
            'payment_status' => 'unpaid',
            'id' => $this->transaction->id,
            'pivot_id' => null,
        ];

        // Sort by created_at
        usort($items, function ($a, $b) {
            return $a['created_at']->timestamp <=> $b['created_at']->timestamp;
        });

        $this->allItems = $items;
    }

    // ---------------- ROOM MANAGEMENT METHODS ------------------ //

public function openAddRoomModal()
{
    $this->loadAvailableRooms();
    $this->showAddRoomModal = true;
}

public function closeAddRoomModal()
{
    $this->showAddRoomModal = false;
    $this->availableRooms = [];
}

public function loadAvailableRooms()
{
    try {
        $roomService = app(\App\Services\RoomAvailabilityService::class);
        
        // Use the day tour date for room availability
        $tourDate = $this->transaction->start_datetime->format('Y-m-d');
        $checkOutDate = $this->transaction->start_datetime->copy()->addDay()->format('Y-m-d');
        
        $this->availableRooms = $roomService->getAvailableRooms($tourDate, $checkOutDate);
    } catch (\Exception $e) {
        Log::error('Error loading available rooms: ' . $e->getMessage());
        $this->availableRooms = [];
    }
}

public function addRoom($roomId)
{
    $room = \App\Models\Property::find($roomId);
    
    if ($room) {
        // Check if room is already added
        if (!collect($this->selectedRooms)->contains('room_id', $roomId)) {
            $this->selectedRooms[] = [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'ideal_guest' => $room->ideal_guest,
                'check_in_date' => $this->transaction->start_datetime->format('Y-m-d'),
                'check_out_date' => $this->transaction->start_datetime->copy()->addDay()->format('Y-m-d'),
            ];
            
            session()->flash('message', 'Room added successfully!');
        } else {
            session()->flash('error', 'Room already added!');
        }
    }
}

public function removeRoom($index)
{
    if (isset($this->selectedRooms[$index])) {
        unset($this->selectedRooms[$index]);
        $this->selectedRooms = array_values($this->selectedRooms); // Reindex array
        session()->flash('message', 'Room removed successfully!');
    }
}

// Updated checkRoomRateType() method if you store the info
protected function checkRoomRateType()
{
    try {
        // Check if day_tour_rate_type is stored in transaction
        if (isset($this->transaction->day_tour_rate_type) && $this->transaction->day_tour_rate_type === 'with_room') {
            $this->hasRoomRate = true;
            Log::info('Room rate detected from transaction:', [
                'day_tour_rate_type' => $this->transaction->day_tour_rate_type
            ]);
            return;
        }

        // Check if we have day_tour_id and can check the rate
        if (isset($this->transaction->day_tour_id) && $this->transaction->day_tour_id) {
            $hasRoomRate = \App\Models\DayTourRate::where('day_tour_id', $this->transaction->day_tour_id)
                ->where('rate_type', 'with_room')
                ->where('is_active', true)
                ->exists();
                
            $this->hasRoomRate = $hasRoomRate;
            Log::info('Checked room rate by day_tour_id:', [
                'day_tour_id' => $this->transaction->day_tour_id,
                'hasRoomRate' => $hasRoomRate
            ]);
            return;
        }

        // Fallback to reservation type check
        if ($this->transaction->reservation_type_id == 4) {
            $hasRoomRate = \App\Models\DayTourRate::where('rate_type', 'with_room')
                ->where('is_active', true)
                ->exists();
                
            $this->hasRoomRate = $hasRoomRate;
            return;
        }

        $this->hasRoomRate = false;

    } catch (\Exception $e) {
        Log::error('Error checking room rate type: ' . $e->getMessage());
        $this->hasRoomRate = false;
    }
}

// Load existing rooms when component mounts
protected function loadExistingRooms()
{
    $existingRooms = $this->transaction->properties()
        ->whereHas('type', function($q) {
            $q->where('name', 'Room');
        })
        ->get();

    foreach ($existingRooms as $room) {
        $this->selectedRooms[] = [
            'type' => 'room',
            'room_id' => $room->id,
            'room_name' => $room->name_number,
            'ideal_guest' => $room->ideal_guest,
            'check_in_date' => $this->transaction->start_datetime->format('Y-m-d'),
            'check_out_date' => $this->transaction->end_datetime->format('Y-m-d'),
        ];
    }
}


    /**
     * ------------------------------ REQUESTS ----------------------------------------
     * Manages the guest requests and admin replies.
     */
    public function saveRequestReply()
    {
        Log::info("Saving request reply: {$this->request_reply}");

        $this->validate([
            'request_reply' => 'nullable|string|max:1000',
        ]);

        $this->transaction->update([
            'request_reply' => $this->request_reply,
        ]);

        $this->closeModal();

        session()->flash('success', 'Request reply updated successfully.');
    }

    /**
     * ------------------------- GUEST MANAGEMENT ---------------------------
     */
    public function saveGuest()
    {
        Log::info('Save Guest method called.');

        $this->resetErrorBag();

        $this->validate([
            'guest.first_name' => 'required|string|max:255',
            'guest.last_name' => 'required|string|max:255',
            'guest.gender' => 'nullable|in:male,female',
            'guest.guest_type_id' => 'required|exists:trn_guest_type,id',
            'guest.middle_name' => 'nullable|string|max:255',
            'guest.suffix' => 'nullable|string|max:10',
            'guest.residency' => 'nullable|string|max:255',
            'guest.country_of_origin' => 'nullable|string|max:255',
        ]);

        $data = [
            'transaction_id' => $this->transaction->id,
            'first_name' => $this->guest['first_name'],
            'middle_name' => $this->guest['middle_name'] ?? null,
            'last_name' => $this->guest['last_name'],
            'suffix' => $this->guest['suffix'] ?? null,
            'gender' => $this->guest['gender'] ?? null,
            'residency' => $this->guest['residency'] ?? null,
            'country_of_origin' => $this->guest['country_of_origin'] ?? null,
            'guest_type_id' => !empty($this->guest['guest_type_id']) ? $this->guest['guest_type_id'] : null,
        ];

        $this->guestDetailService->saveGuest($data);

        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        // Reset guest input fields
        $this->reset('guest');
        $this->activeModal = false;
    }

    public function updateGuest()
    {
        $this->validate([
            'editingFirstName' => 'required|string|max:255',
            'editingMiddleName' => 'nullable|string|max:255',
            'editingLastName' => 'required|string|max:255',
            'editingSuffix' => 'nullable|string|max:255',
            'editingGender' => 'required|in:male,female,other',
            'editingResidency' => 'required|in:local,foreigner',
            'editingCountryOfOrigin' => 'nullable|string|max:255',
            'editingGuestTypeId' => 'required|exists:trn_guest_type,id',
        ]);

        DB::table('trn_guest_details')
            ->where('id', $this->editingGuestId)
            ->update([
                'first_name' => $this->editingFirstName,
                'middle_name' => $this->editingMiddleName,
                'last_name' => $this->editingLastName,
                'suffix' => $this->editingSuffix,
                'gender' => $this->editingGender,
                'residency' => $this->editingResidency,
                'country_of_origin' => $this->editingCountryOfOrigin,
                'guest_type_id' => $this->editingGuestTypeId,
                'updated_at' => now(),
            ]);

        $this->reset([
            'editingGuestId',
            'editingFirstName',
            'editingMiddleName',
            'editingLastName',
            'editingSuffix',
            'editingGender',
            'editingResidency',
            'editingCountryOfOrigin',
            'editingGuestTypeId',
            'showEditGuestModal',
        ]);

        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        session()->flash('message', 'Guest updated successfully.');
    }

    public function deleteGuest($guestId)
    {
        $this->guestDetailService->deleteGuest($guestId, $this->transaction);
        $this->loadGuestDetails();
        $this->loadAllInvoiceItems();
        $this->recalculateInvoice();

        session()->flash('message', 'Guest deleted successfully.');
    }

    public function editGuest($guestId)
    {
        Log::info("Edit Guest method called.");

        $guest = GuestDetail::find($guestId);

        if ($guest) {
            $this->editingGuestId = $guest->id;
            $this->editingGuestTypeId = $guest->guest_type_id;
            $this->editingFirstName = $guest->first_name;
            $this->editingMiddleName = $guest->middle_name;
            $this->editingLastName = $guest->last_name;
            $this->editingSuffix = $guest->suffix;
            $this->editingGender = $guest->gender;
            $this->editingResidency = $guest->residency;
            $this->editingCountryOfOrigin = $guest->country_of_origin;
            $this->showEditGuestModal = true;
        } else {
            Log::warning("Guest not found for ID: $guestId");
        }
    }

    /**
     * --------------------------------- INVOICE RECALCULATION ---------------------------------
     */
public function recalculateInvoice()
{
    // Only update discount and grand total, don't touch payment calculations
    $this->invoiceService->updateDiscountTotal($this->invoice, $this->transaction);
    $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
    
    // Refresh the display values
    $this->refreshInvoice();
}


    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }

    // ------------------------ COMPUTATIONS -------------------------- //
    public function computeBaseSubtotal(): float
    {
        return $this->transaction->sub_total;
    }

    public function computeConvenienceFeeTotal()
    {
        $payments = $this->payments ?? collect();
        $total = $payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');

        if ($total == 0 && $this->transaction->convenience_fee > 0) {
            return $this->transaction->convenience_fee;
        }

        return $total;
    }

    /**
     * -------------------------- SEND OFFICIAL RECEIPT TO EMAIL ----------------------------------
     */
    public function sendReceiptToEmail(EmailService $emailService, BrandingService $brandingService)
    {
        Log::info('Send Receipt To Email Method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            Log::error('Missing data for sending official receipt.');
            abort(404, 'Missing data for generating the official receipt.');
        }

        $convenienceFeeTotal = $this->invoice->payments->sum('convenience_fee');

        // Prepare data for email
        $dayTourData = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'guestDetails' => $this->guestDetails,
            'convenienceFeeTotal' => $convenienceFeeTotal,
        ];

        $pdfOutput = $this->receiptService->generatePdf($dayTourData, $this->receipt->receipt_number);

        // Send the PDF to the user's email using the EmailService
        $emailService->sendOfficialReceipt(
            $this->transactionUser->email,
            $pdfOutput,
            $this->receipt->receipt_number,
            $this->transactionUser,
            $dayTourData
        );

        session()->flash('message', 'Acknowledgement receipt has been sent to guest\'s email!');
        Log::info('Acknowledgement receipt sent to email: ' . $this->transactionUser->email);
    }

    /**
     * ------------------------- REQUEST REMAINING BALANCE ----------------------------------
     */
    public function requestRemainingBalance(PayMongoService $payMongo, NotificationService $notifier)
    {
        Log::info('Request Remaining Balance method called.');

        $transaction = $this->transaction;
        $invoice = $this->invoice;
        $user = $this->transactionUser;

        // Calculate convenience fee (3%)
        $convenienceFee = $invoice->balance_due * 0.03;
        $totalAmount = $invoice->balance_due + $convenienceFee;

        // Build the payload
        $payload = [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ['card', 'gcash', 'qrph', 'paymaya'],
                    'success_url' => route('guest.thank-you-page'),
                    'cancel_url' => url('/payment-failed'),
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => intval($convenienceFee * 100),
                            'description' => 'Convenience Fee (3%)',
                            'name' => 'Convenience Fee',
                            'quantity' => 1,
                        ],
                        [
                            'currency' => 'PHP',
                            'amount' => intval($invoice->balance_due * 100),
                            'description' => 'Day Tour ' . $transaction->transaction_number,
                            'name' => 'Day Tour Reservation',
                            'quantity' => 1,
                        ],
                    ],
                    'description' => 'Day Tour for ' . $user->first_name . ' ' . $user->last_name,
                    'metadata' => [
                        'invoice_id' => (string) $invoice->id,
                        'payment_type' => 'Day Tour Remaining Balance',
                        'notes' => 'Payment for Day Tour Remaining Balance',
                        'convenience_fee' => $convenienceFee,
                    ],
                ],
            ],
        ];

        // Create the checkout session through PayMongo
        $response = $payMongo->createCheckoutSession($payload);

        $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

        if ($paymentLink) {
            $transaction->update(['payment_link' => $paymentLink]);
        }

        $invoice->requested_remaining_balance = true;
        $invoice->save();

        try {
            $pdfContent = $this->generateAvailablePaymentMethods();
            $notifier->sendRemainingBalanceEmail($user, $transaction, $invoice, $paymentLink, $pdfContent);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('admin.view-daytour-reservation', ['transaction' => $transaction->id]);
    }

    /**
     * --------------------------------- OFFICIAL RECEIPT GENERATION ---------------------------------
     */
    public function GenerateReceipt()
    {
        Log::info('Show Generate Official Receipt Modal method triggered.');

        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        $receipt = $this->receiptService->generateReceipt($this->invoice);

        if (!$receipt) {
            $this->cannotGenerateReceiptModal = true;
            return;
        }

        $this->receipt = $receipt;
    }

    /**
     * ---------------------------------- OFFICIAL RECEIPT PRINTING ----------------------------------
     */
    public function printOfficialReceipt()
    {
        Log::info('Print Official Receipt method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for generating the official receipt.');
        }

        $convenienceFeeTotal = $this->invoice->payments->sum('convenience_fee');

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'guestDetails' => $this->guestDetails,
            'convenienceFeeTotal' => $convenienceFeeTotal,
        ];

        $pdfOutput = $this->receiptService->generatePdf($data, $this->receipt->receipt_number);

        return response()->streamDownload(function () use ($pdfOutput) {
            echo $pdfOutput;
        }, 'acknowledgement_receipt_' . $this->receipt->receipt_number . '.pdf');
    }

    /**
     * -------------------- EXPORT DAY TOUR DETAILS REPORT --------------------
     */
    public function exportDayTourDetails()
    {
        $transaction = Transaction::with([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
        ])->findOrFail($this->transaction->id);

        $convenienceFeeTotal = $this->computeConvenienceFeeTotal();

        $pdf = Pdf::loadView('livewire.admin.reservations.daytour-details', [
            'transaction' => $transaction,
            'guestDetails' => $transaction->guestDetails,
            'invoice' => $transaction->invoice,
            'payments' => $transaction->invoice->payments,
            'convenienceFeeTotal' => $convenienceFeeTotal,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'daytour-details-' . $this->transaction->start_datetime . '.pdf');
    }

    /**
     * ---------------------------- MODALS ----------------------------------------
     */
    public function ShowReceipt()
    {
        Log::info('Show Receipt method called.');

        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        if ($this->invoice->balance_due > 0) {
            abort(400, 'Receipt cannot be shown. Invoice still has balance due.');
        }

        $existing = Receipt::where('invoice_id', $this->invoice->id)->first();

        if (!$existing) {
            abort(404, 'No receipt found for this invoice.');
        }

        $this->receipt = $existing;
        $this->showReceiptModal = true;
    }

    public function OpenCreatePaymentModal()
    {
        Log::info('Open Create Payment method called.');
        $this->createPaymentModal = true;
    }

    public function CloseCreatePaymentModal()
    {
        Log::info('Close Create Payment method called.');
        $this->createPaymentModal = false;
    }

    public function openModal(string $modalType)
    {
        Log::info("Open modal: $modalType");
        $this->activeModal = $modalType;
    }

    public function closeModal()
    {
        $this->activeModal = '';
    }

    // --------------------- DATABASE INSERTION --------------------------- //
    public function CreatePayment(PaymentService $paymentService)
    {
        Log::info('Create Payment method called.');

        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance,Merchandise,Accommodation Fully Paid,Accommodation Downpayment,Accommodation Balance',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'payment_method_id' => 'required|exists:pm_payment_methods,id',
            'payment_screenshot' => 'nullable|image|max:2048',
        ]);

        if (!$this->invoice) {
            abort(404, 'No invoice found.');
        }

        // Upload screenshot if provided
        $screenshotPath = $this->uploadScreenshot();

        try {
            // 1 Create payment using PaymentService (same logic as ViewEvent)
            $paymentService->create([
                'invoice'             => $this->invoice,
                'transaction'         => $this->transaction,
                'amount_paid'         => $this->amount_paid,
                'payment_type'        => $this->payment_type,
                'mode_of_payment'     => 'cash',
                'payment_date'        => $this->payment_date,
                'notes'               => $this->notes,
                'payment_status'      => 'completed',
                'currency'            => 'PHP',
                'verified_at'         => now(),
                'payment_screenshot'  => $screenshotPath,
                'payment_method_id'   => $this->payment_method_id,
            ]);

            // 2 Recalculate invoice totals (same logic as ViewEvent)
            $this->recalculateInvoiceTotals();

            // 3 Update transaction status if needed (same logic as ViewEvent)
            if ($this->transaction->transaction_status === 'reserved') {
                $this->transaction->update([
                    'transaction_status' => 'receipt_verified',
                    'updated_at' => now(),
                ]);
            }

            // 4 Apply payment to unpaid items
            $this->updatePaymentStatus($paymentService, $this->amount_paid);

            // 5 Reset form fields
            $this->reset([
                'amount_paid',
                'mode_of_payment',
                'payment_type',
                'payment_date',
                'payment_status',
                'notes',
                'currency',
                'verified_at',
                'payment_screenshot',
                'payment_method_id',
            ]);

            // 6 Close modal
            $this->CloseCreatePaymentModal();

            // 7 Refresh component data
            $this->refreshComponentData();

            session()->flash('success', 'Payment created successfully.');

        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            session()->flash('error', 'Payment creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate invoice totals - same logic as ViewEvent component
     */
    protected function recalculateInvoiceTotals()
    {
        // Refresh the invoice to ensure latest values
        $this->invoice->refresh();

        // Get total payments linked to this invoice
        $totalPaid = $this->invoice->payments()->sum('amount_paid');

        // Base subtotal minus discount gives actual total due
        $totalDue = ($this->invoice->sub_total - $this->invoice->total_discount);

        // Adjust total due if a deposit was already paid
        $totalDue -= $this->invoice->deposit_paid;

        // Calculate remaining balance
        $newBalance = max(0, $totalDue - $totalPaid);

        // Determine invoice status
        $newStatus = match (true) {
            $newBalance <= 0 => 'completed',
            now()->gt($this->invoice->due_date) => 'overdue',
            default => 'pending',
        };

        // Update the invoice record
        $this->invoice->update([
            'amount_paid'  => $totalPaid,
            'balance_due'  => $newBalance,
            'invoice_status' => $newStatus,
            'completed_at' => $newBalance <= 0 ? now() : null,
            'updated_at'   => now(),
        ]);

        Log::info("Invoice recalculated", [
            'total_paid' => $totalPaid,
            'total_due' => $totalDue,
            'new_balance' => $newBalance,
            'new_status' => $newStatus
        ]);
    }

    /**
     * Refresh all component data after payment
     */
    protected function refreshComponentData()
    {
        // Reload all relationships
        $this->transaction->refresh();
        $this->invoice->refresh();
        $this->payments = $this->invoice->payments ?? collect();
        
        // Reload guest details
        $this->loadGuestDetails();
        
        // Reload invoice items
        $this->loadAllInvoiceItems();
    }

    protected function uploadScreenshot(): ?string
    {
        // If no file was uploaded, return null
        if (!$this->payment_screenshot) {
            return null;
        }

        // If uploaded file is invalid
        if (!$this->payment_screenshot->isValid()) {
            throw new \Exception('Image upload failed. Please try again.');
        }

        // Store and return the file path
        return $this->payment_screenshot->store('proof-of-payments', 'public');
    }


    // --------------------- HELPER METHODS --------------------------- //
    public function updatePaymentStatus(PaymentService $paymentService, float $amountPaid)
    {
        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);
        $this->transaction->load('guestDetails');
    }

    public function loadGuestDetails()
    {
        $this->guestDetails = GuestDetail::where('transaction_id', $this->transaction->id)->get();
    }

    public function generateAvailablePaymentMethods()
    {
        Log::info('Print available payment methods called.');

        $paymentMethods = app(PaymentMethodService::class)->getPaymentMethodsData();

        foreach ($paymentMethods as &$method) {
            if (!$method['has_convenience_fee']) {
                $method['note'] = 'No convenience fee for manual payment.';
            }
        }

        $pdf = Pdf::loadView('livewire.admin.reports.available-payment-methods', compact('paymentMethods'));

        return $pdf->output();
    }


        // Add these properties with the other properties
    public $showDiscountModal = false;
    public $manualDiscountAmount = 0;


    /**
     * -------------------- DISCOUNT CALCULATIONS -----------------------------
     * Proper discount handling for day tours
     * ---------------------------------------------------------------------------------
     */
    public function computeBaseSubtotalAfterDiscount(): float
    {
        $baseSubtotal = $this->computeBaseSubtotal();
        $discounts = $this->invoice->total_discount + $this->transaction->promo_discount_amount;

        return max($baseSubtotal - $discounts, 0);
    }

    public function computeInvoiceWithDiscount(): float
    {
        $baseSubtotal = $this->computeBaseSubtotal() + $this->computeConvenienceFeeTotal();
        $totalDiscount = $this->invoice->discounts->sum('discount_value') ?? 0;

        Log::info("Day Tour Discount Calculation:", [
            'base_subtotal' => $baseSubtotal,
            'total_discount' => $totalDiscount,
            'result' => max($baseSubtotal - $totalDiscount, 0)
        ]);

        return max($baseSubtotal - $totalDiscount, 0);
    }

    /**
     * Override the applyDiscounts method to ensure proper calculation
     */
    public function applyDiscounts()
    {
        $this->validate([
            'manualDiscountAmount' => 'required|numeric|min:0|max:' . $this->computeBaseSubtotal(),
        ]);

        // Fetch PWD and Senior discount types
        $pwdDiscountType = \App\Models\DiscountType::where('name', 'pwd')->first();
        $seniorDiscountType = \App\Models\DiscountType::where('name', 'senior')->first();

        if (!$pwdDiscountType || !$seniorDiscountType) {
            session()->flash('error', 'PWD/Senior discount types not found in system.');
            return;
        }

        // Delete existing PWD & Senior discount rows before applying new ones
        InvoiceDiscount::where('invoice_id', $this->invoice->id)
            ->whereIn('discount_type_id', [$pwdDiscountType->id, $seniorDiscountType->id])
            ->delete();

        // Apply the manual amount as PWD discount
        InvoiceDiscount::create([
            'invoice_id' => $this->invoice->id,
            'discount_type_id' => $pwdDiscountType->id,
            'discount_value' => $this->manualDiscountAmount,
            'quantity' => 1,
            'notes' => 'Manual PWD/Senior Discount',
        ]);

        // Manually update invoice totals instead of relying solely on recalculateInvoice
        $this->manualInvoiceRecalculation();
        
        $this->closeDiscountModal();
        session()->flash('success', 'PWD/Senior discount applied successfully!');
    }

    /**
     * Manual invoice recalculation for day tours
     */
    protected function manualInvoiceRecalculation()
    {
        $baseSubtotal = $this->computeBaseSubtotal();
        $totalDiscount = $this->invoice->discounts->sum('discount_value') ?? 0;
        $convenienceFee = $this->computeConvenienceFeeTotal();
        
        $grandTotal = max(($baseSubtotal - $totalDiscount) + $convenienceFee, 0);
        
        // Update the invoice
        $this->invoice->update([
            'base_subtotal' => $baseSubtotal,
            'total_discount' => $totalDiscount,
            'sub_total' => $grandTotal,
            'balance_due' => max($grandTotal - $this->invoice->amount_paid, 0),
        ]);
        
        // Refresh the invoice
        $this->invoice = $this->invoice->fresh();
        $this->refreshInvoice();
    }

    public function openDiscountModal()
    {
        $this->manualDiscountAmount = 0;
        $this->showDiscountModal = true;
    }

    public function closeDiscountModal()
    {
        $this->showDiscountModal = false;
        $this->reset(['manualDiscountAmount']);
    }

    /**
     * Check if PWD/Senior discount is already applied
     */
    public function getDiscountsAppliedProperty()
    {
        $appliedTypes = $this->invoice->discounts->pluck('discount_type_id')->toArray();
        $requiredTypes = DiscountType::whereIn('name', ['pwd', 'senior'])->pluck('id')->toArray();

        // Check if any PWD/Senior discount is applied
        return !empty(array_intersect($requiredTypes, $appliedTypes));
    }

    public function removeDiscount($invoiceId, $discountTypeId)
    {
        $discount = InvoiceDiscount::where('invoice_id', $invoiceId)
            ->where('discount_type_id', $discountTypeId)
            ->first();

        if ($discount) {
            $discount->delete();
            Log::info("Discount type {$discountTypeId} removed from invoice {$invoiceId}");
        }

        // Use manual recalculation instead
        $this->manualInvoiceRecalculation();

        Log::info("Invoice {$invoiceId} recalculated after discount removal.");
        session()->flash('success', 'Discount removed successfully!');
    }
    
}