<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\Activity;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfficialReceiptMail;
use App\Mail\RequestRemainingBalanceMail;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PayMongoService;
use App\Services\ServiceBag;
use App\Services\TransactionLoader;
use App\Services\ActivityCartService;
use App\Services\RoomCartService;
use App\Services\ActivityTransactionService;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\PaymentService;
use App\Services\CartService;






#[Layout('layouts.app')]
class ViewReservation extends Component
{

    // ---------------- RELATIONSHIPS ------------------ //

    public $transaction;
    public $transactionUser;
    public $invoice;
    public $guestDetails;
    public $activities;
    public $properties;
    public $payments;
    public $services;


    // ---------------- COMPUTATIONS ------------------ //
    public $totalAddons;
    public $totalRooms;
    public $total_pax;

    // --------- PAYMENT RELATED PROPERTIES ----------- //
    public $invoice_id;
    public $amount_paid;
    public $mode_of_payment;
    public $payment_type;
    public $payment_date;
    public $payment_status;
    public $notes;
    public $currency;
    public $verified_at;

    // ---------------- MODALS ------------------ //
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $createPaymentModal = false;
    public $addActivityModal = false;
    public $expandedActivity = null;

    // ---------------- BRANDING ------------------ //
    public string $companyName = 'Company'; //Default
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    // ---------------- ACTIVITIES ------------------ //
    public $availableActivities;
    public $cart = []; // Store newly added activities
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];
    public $activityPaymentStatus = [];

    // -------------- SERVICES ------------------- // 

    public $servicePaymentStatus = [];

    public $availableServices;
    // ---------------- INVOICE ------------------ //
    public $sub_total;
    public $balance_due;
    public $convenienceFeeTotal;

    // ---------------- RECEIPT ------------------ //
    public $receipt;
    public $receiptNumber;

    public $editingActivityId;
    public $activityQuantity;
    public $showEditActivityModal = false;

    protected ServiceBag $service;
    protected TransactionLoader $loader;
    protected ActivityCartService $activityCartService;
    protected RoomCartService $roomCartService;
    protected ActivityTransactionService $activityTransactionService;
    protected InvoiceService $invoiceService;
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected NotificationService $notificationService;
    protected PaymongoService $payMongoService;
    protected ReceiptService $receiptService;
    protected PaymentService $paymentService;
    protected CartService $cartService;


    public function render()
    {
        $this->activities = $this->transaction->activities()->withPivot('id', 'quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days', 'room_rate_id')->get();


        return view('livewire.admin.reservations.view-reservation', [
            'activities' => $this->activities,  // Pass activities to the view properly
            'properties' => $this->properties,  // Pass activities to the view properly
        ]);
    }

    public function boot(ServiceBag $services)
    {
        $this->loader = $services->loader;
        $this->activityCartService = $services->activityCartService;
        $this->roomCartService = $services->roomCartService;
        $this->activityTransactionService = $services->activityTransactionService;
        $this->invoiceService = $services->invoiceService;
        $this->emailService = $services->emailService;
        $this->brandingService = $services->brandingService;
        $this->payMongoService = $services->payMongoService;
        $this->notificationService = $services->notificationService;
        $this->receiptService = $services->receiptService;
        $this->paymentService = $services->paymentService;
        $this->cartService = $services->cartService;
    }




    public function mount(Transaction $transaction)
    {

        // Loads all transaction-related relationships
        $data = $this->loader->load($transaction);
        $this->transaction = $data['transaction'];
        $this->transactionUser = $data['transactionUser'];
        $this->invoice = $data['invoice'];
        $this->guestDetails = $data['guestDetails'];
        $this->activities = $data['activities'];
        $this->properties = $data['properties'];
        $this->payments = $data['payments'];
        $this->totalRooms = $data['totalRooms'];
        $this->totalAddons = $data['totalAddons'];

        // Sets default dates for payment
        $now = Carbon::now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');

        // Retrieves all activities (Code Suggestion: Return a model accessor for available activities)
        $this->availableActivities = Activity::all();
        $this->availableServices = Service::all();
    }

    /**
     * ----------------------------- ITEM CART MANAGEMENT ------------------------------
     *
     * Manages the cart functionality for different item types (e.g., services, activities).
     *
     * Core responsibilities:
     * - `addItemToCart`: Adds an item (e.g., activity or service) to the cart using CartService.
     * - `removeItemFromCart`: Removes a specific item from the cart based on its type and ID.
     * - `incrementItemQuantity`: Increases the quantity of a given item in the cart.
     * - `decrementItemQuantity`: Decreases the quantity (with a minimum limit of 1).
     *
     * State Management:
     * - Cart contents are stored in `$this->cart` and synced with the UI.
     * - Item quantities are managed through `$this->quantity[itemId]`.
     * - Status and payment status are also handled and passed to the CartService.
     *
     * ---------------------------------------------------------------------------------
     */
    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
    }

    public function addItemToCart($type, $itemId)
    {
        $newCart = $this->cartService->addItem(
            $type,
            $itemId,
            $this->cart,
            $this->quantity,
            $this->status,
            $this->activityPaymentStatus
        );

        if ($newCart === false) {
            $this->addError('cart', 'This item is already in the cart.');
            return;
        }

        $this->cart = $newCart;
    }

    public function removeItemFromCart($type, $itemId)
    {
        $this->cart = $this->cartService->removeItem($type, $itemId, $this->cart);
    }

    public function incrementItemQuantity($type, $itemId)
    {
        if (!isset($this->quantity[$itemId])) {
            $this->quantity[$itemId] = 1;
        }

        $newQuantity = $this->quantity[$itemId] + 1;
        $this->quantity[$itemId] = $newQuantity;

        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $newQuantity);
    }

    public function decrementItemQuantity($type, $itemId)
    {
        if (!isset($this->quantity[$itemId])) {
            $this->quantity[$itemId] = 1;
        }

        $newQuantity = max(1, $this->quantity[$itemId] - 1);
        $this->quantity[$itemId] = $newQuantity;

        $this->cart = $this->cartService->updateQuantity($type, $this->cart, $itemId, $newQuantity);
    }


    /**
     * ------------------------- ACTIVITY TRANSACTION MANAGEMENT ---------------------------
     *
     * Handles database operations for activity transactions tied to a reservation.
     *
     * These methods interact with the persistent transaction and invoice data:
     * - `saveActivity`: Persists activities from the cart, resets the cart, and recalculates the invoice.
     * - `deleteActivity`: Removes an activity from the transaction and updates the invoice.
     * - `updateActivity`: Updates the quantity of an activity in the transaction with validation.
     * - `editActivity`: Opens the modal for editing activity.
     *
     * After each operation, the invoice totals and status are recalculated via `recalculateInvoice()`
     * and refreshed for display via `refreshInvoice()`.
     *
     * -------------------------------------------------------------------------------------
     */
    public function saveActivity()
    {
        $this->resetErrorBag();

        $this->activityTransactionService->saveActivities($this->cart, $this->transaction, $this->invoice);

        $this->recalculateInvoice();
        $this->cart = [];
        $this->activeModal = false;
    }

    public function deleteActivity($pivotId)
    {
        $this->activityTransactionService->deleteActivity($pivotId, $this->transaction, $this->invoice);
        $this->recalculateInvoice();
    }

    public function updateActivity()
    {
        $this->validate([
            'activityQuantity' => 'required|integer|min:1',
        ]);

        // Passes the value from editActivity() to update the quantity
        try {

            $this->activityTransactionService->updateActivityQuantity(
                $this->editingActivityId,
                $this->activityQuantity,
                $this->transaction
            );

            // Recalculate all amounts in invoice
            $this->recalculateInvoice();

            // Closes the modal
            $this->showEditActivityModal = false;
            $this->dispatch('activity-updated');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function editActivity($pivotId)
    {

        Log::info("Edit Activity method called.");

        $pivot = DB::table('transaction_activities')->where('id', $pivotId)->first();

        if ($pivot) {
            $this->editingActivityId = $pivotId;
            $this->activityQuantity = $pivot->quantity;
            $this->showEditActivityModal = true;
        }
    }


    public function saveServices()
    {
        $this->resetErrorBag();
        dd($this->cart);
    }



    /**
     * --------------------------------- INVOICE RECALCULATION ---------------------------------
     *
     * Recalculates the invoice totals and updates the component's state accordingly.
     *
     * This method performs the following actions:
     * - Updates the invoice grand total based on the latest cart or charges.
     * - Recomputes the balance due after payments or adjustments.
     * - Updates the invoice status (e.g., Paid, Unpaid, Partial) based on the balance.
     * - Refreshes the Livewire component's state by pulling fresh values from the database.
     *
     * Ensures that both the backend (database) and frontend (Livewire view) reflect
     * the most current invoice data after any changes (e.g., adding/removing items).
     * ------------------------------------------------------------------------------------------
     */
    public function recalculateInvoice()
    {
        $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
        $this->invoiceService->updateBalanceDue($this->invoice);
        $this->invoiceService->updateStatus($this->invoice);

        $this->refreshInvoice();
    }

    /**
     * Reloads the invoice and updates related UI-bound properties.
     */
    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }

    // ------------------------ COMPUTATIONS -------------------------- //

    public function computeConvenienceFeeTotal()
    {
        $payments = $this->payments ?? collect();

        return $payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');
    }

    public function computeBaseSubtotal()
    {
        $activities = $this->transaction->activities ?? collect();
        $properties = $this->transaction->properties ?? collect();

        $activitiesTotal = $activities->map(function ($activity) {
            return $activity->pivot->quantity * $activity->amount;
        })->sum();

        $roomsTotal = $properties->map(function ($property) {
            return $property->pivot->total_amount;
        })->sum();

        return $activitiesTotal + $roomsTotal;
    }

    public function updatePaymentStatus()
    {
        // Update unpaid activities to 'paid'
        DB::table('transaction_activities')
            ->where('transaction_id', $this->transaction->id)
            ->where('payment_status', 'unpaid')
            ->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);

        // Update unpaid properties (rooms) to 'paid'
        DB::table('transaction_properties')
            ->where('transaction_id', $this->transaction->id)
            ->where('payment_status', 'unpaid')
            ->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);

        // Optional: reload fresh data
        $this->transaction->load('activities', 'properties');

        Log::info("All unpaid items for transaction {$this->transaction->id} marked as paid.");
    }


    /**
     * -------------------------- SEND OFFICIAL RECEIPT TO EMAIL ----------------------------------
     *
     * Sends the official receipt to the guest's email address.
     * Uses the EmailService and BrandingService to generate the PDF and send it.
     *
     * ---------------------------------------------------------------------------------------------
     */
    public function sendReceiptToEmail(EmailService $emailService, BrandingService $brandingService)
    {
        Log::info('Send Receipt To Email Method called.');

        // Ensure all required data is available before proceeding
        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            Log::error('Missing data for sending official receipt.');
            abort(404, 'Missing data for generating the official receipt.');
        }

        // Fetch related activities tied to the transaction, including pivot data
        $activities = $this->transaction->activities()->withPivot(
            'quantity',
            'amount',
            'activity_datetime',
            'status'
        )->get();

        // Fetch related properties tied to the transaction, including pivot data
        $properties = $this->transaction->properties()->withPivot(
            'adults',
            'kids',
            'extra_guest',
            'extra_charge',
            'amount',
            'total_amount',
            'days'
        )->get();

        // Merge all necessary data for the PDF and email
        $data = array_merge(
            [
                'receipt' => $this->receipt,
                'invoice' => $this->invoice,
                'transaction' => $this->transaction,
                'transactionUser' => $this->transactionUser,
                'properties' => $properties,
                'activities' => $activities,
            ],
            $brandingService->getBrandingData()
        );

        // Generate the receipt PDF using a Blade view
        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);
        $pdfContent = $pdf->output(); // Get the raw PDF content

        // Send the PDF to the user's email using the EmailService
        $emailService->sendOfficialReceipt(
            $this->transactionUser->email,
            $pdfContent,
            $this->receipt->receipt_number,
            $this->transactionUser,
            $data
        );

        // Notify user and log the success
        session()->flash('message', 'Official receipt has been sent to guest\'s email!');
        Log::info('Official receipt sent to email: ' . $this->transactionUser->email);
    }


    /**
     * ------------------------- REQUEST REMAINING BALANCE RECEIPT ----------------------------------
     *
     * Sends a payment link to the guest for paying the remaining balance of their reservation.
     * Uses PayMongoService to generate a checkout session and NotificationService to email the guest.
     *
     * ---------------------------------------------------------------------------------------------
     */
    public function requestRemainingBalance(PayMongoService $payMongo, NotificationService $notifier)
    {
        Log::info('Request Remaining Balance method called.');

        $transaction = $this->transaction;
        $invoice = $this->invoice;
        $user = $this->transactionUser;

        // Build the payload required by PayMongo for the checkout session
        $payload = [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ['card', 'gcash', 'qrph', 'paymaya'],
                    'success_url' => route('guest.thank-you-page'), // Redirect after successful payment
                    'cancel_url' => url('/payment-failed'), // Redirect if payment is cancelled
                    'line_items' => [[
                        'currency' => 'PHP',
                        'amount' => intval($invoice->balance_due * 100), // PayMongo expects amount in centavos
                        'description' => 'Reservation ' . $transaction->transaction_number,
                        'name' => 'Canopy Farm PH',
                        'quantity' => 1,
                    ]],
                    'description' => 'Reservation for ' . $user->first_name . ' ' . $user->last_name,
                    'metadata' => [
                        'invoice_id' => (string) $invoice->id,
                        'payment_type' => 'Remaining Balance',
                        'notes' => 'Payment for Remaining Balance',
                    ],
                ],
            ],
        ];

        // Create the checkout session through PayMongo
        $response = $payMongo->createCheckoutSession($payload);

        // Extract the payment link from the response
        $paymentLink = $response['data']['attributes']['checkout_url'] ?? null;

        // Save the payment link in the transaction record if it exists
        if ($paymentLink) {
            $transaction->update(['payment_link' => $paymentLink]);
        }

        // Mark that the invoice has requested for remaining balance
        $invoice->requested_remaining_balance = true;
        $invoice->save();

        // Attempt to send email notification to guest with the payment link
        try {
            $notifier->sendRemainingBalanceEmail($user, $transaction, $invoice, $paymentLink);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage()); // Show error message in UI
        }

        // Redirect back to the reservation view page
        return redirect()->route('admin.view-reservation', ['transaction' => $transaction->id]);
    }


    /**
     * --------------------------------- OFFICIAL RECEIPT GENERATION ---------------------------------
     *
     * Triggers the generation of an official receipt for a completed invoice
     * -----------------------------------------------------------------------------------------------
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
     *
     * Generates and streams a PDF version of the official receipt.
     *
     * -----------------------------------------------------------------------------------------------
     */
    public function printOfficialReceipt()
    {
        Log::info('Print Official Receipt method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for generating the official receipt.');
        }

        $this->activities = $this->transaction->activities()
            ->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();

        $this->properties = $this->transaction->properties()
            ->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'properties' => $this->properties,
            'activities' => $this->activities,
        ];

        $pdfOutput = $this->receiptService->generatePdf($data, $this->receipt->receipt_number);

        return response()->streamDownload(function () use ($pdfOutput) {
            echo $pdfOutput;
        }, 'official_receipt_' . $this->receipt->receipt_number . '.pdf');
    }


    /**
     * -------------------- EXPORT RESERVATION DETAILS REPORT --------------------
     *
     * Generates a detailed PDF report of a reservation, including:
     * - Guest details
     * - Invoice and payment records
     * - Booked properties
     * - Added activities and their respective pivot data
     * - Totals for rooms and add-ons
     * 
     * ----------------------------------------------------------------------------
     */
    public function exportReservationDetails()
    {
        $transaction = Transaction::with([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
            'properties',
            'activities' => function ($query) {
                $query->withPivot('quantity', 'amount', 'activity_datetime', 'status');
            },
        ])->findOrFail($this->transaction->id);

        $pdf = Pdf::loadView('livewire.admin.reservations.reservation-details', [
            'transaction' => $transaction,  // Pass the actual transaction
            //Pass the relationships
            'guestDetails' => $transaction->guestDetails,
            'invoice' => $transaction->invoice,
            'activities' => $transaction->activities,
            'properties' => $transaction->properties,
            'payments' => $transaction->invoice->payments,
            'totalRooms' => $transaction->totalRooms,
            'totalAddons' => $transaction->totalAddons,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reservation-details-' . $this->transaction->start_datetime . '.pdf');
    }


    /**
     * ---------------------------- MODALS ----------------------------------------
     *
     * Handles the opening and closing of modals related to:
     * - Receipt viewing
     * - Payment creation
     * - Activity addition
     *
     * ----------------------------------------------------------------------------
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

    public $activeModal;

    public function openModal(string $modalType)
    {
        Log::info("Open modal: $modalType");
        $this->activeModal = $modalType;
    }

    public function closeModal()
    {
        $this->activeModal = '';

        // Resets the input quantity
        $this->quantity = [];
        $this->cart = [];
    }




    // --------------------- DATABASE INSERTION --------------------------- // 


    public function CreatePayment(PaymentService $paymentService)
    {
        Log::info('Create Payment method called.');

        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!$this->invoice) {
            abort(404, 'No invoice found.');
        }

        $paymentService->create([
            'invoice'       => $this->invoice,
            'transaction'   => $this->transaction,
            'amount_paid'   => $this->amount_paid,
            'payment_type'    => $this->payment_type,
            'mode_of_payment'  => 'cash',
            'payment_date'  => $this->payment_date,
            'notes'         => $this->notes,
            'payment_status' => 'completed',
            'currency'         => 'PHP',
            'verified_at'      => now(),
        ]);

        // Change the payment status of the items
        $this->updatePaymentStatus();
        $this->recalculateInvoice();

        $this->reset([
            'amount_paid',
            'mode_of_payment',
            'payment_type',
            'payment_date',
            'payment_status',
            'notes',
            'currency',
            'verified_at'
        ]);

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction->id])
            ->with('success', 'Payment created successfully.');
    }
}
