<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentMethod;
use App\Services\PaymentService;
use App\Services\ServiceBag;
use App\Services\TransactionLoader;
use App\Services\ActivityCartService;
use App\Services\RoomCartService;
use App\Services\ActivityTransactionService;
use App\Services\ServiceTransactionService;
use App\Services\PropertyTransactionService;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\CartService;
use App\Services\GuestDetailService;
use App\Services\PaymentMethodService;
use App\Services\RoomAvailabilityService;
use App\Services\EmailService;
use App\Services\BrandingService;
use App\Services\PayMongoService;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ViewEvent extends Component
{

    use WithFileUploads;
    protected PaymentService $paymentService;
    protected ServiceBag $service;
    protected TransactionLoader $loader;
    protected ActivityCartService $activityCartService;
    protected RoomCartService $roomCartService;
    protected ActivityTransactionService $activityTransactionService;
    protected ServiceTransactionService $serviceTransactionService;
    protected PropertyTransactionService $propertyTransactionService;
    protected InvoiceService $invoiceService;
    protected EmailService $emailService;
    protected BrandingService $brandingService;
    protected NotificationService $notificationService;
    protected PaymongoService $payMongoService;
    protected ReceiptService $receiptService;
    protected GuestDetailService $guestDetailService;
    protected CartService $cartService;

    // Create a public property
    public Transaction $event;
    public $transaction;
    public $invoice;
    public $payments;

    public $halls;
    public $guests;
    public $event_invoice;
    public $eventTypes;

    // ---------------- PAYMENT RELATED PROPERTIES ------------------ //

    public $invoice_id;
    public $amount_paid;
    public $mode_of_payment;
    public $payment_type;
    public $payment_date;
    public $payment_status;
    public $notes;
    public $currency;
    public $verified_at;

    // ---------------------------- MODALS -------------------------- //
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $createPaymentModal = false;

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;
    public $payment_methods;
    public $payment_method_id;
    public $payment_screenshot;
    public $sub_total;
    public $balance_due;

    public function boot(ServiceBag $services)
    {
        $this->loader = $services->loader;
        $this->activityCartService = $services->activityCartService;
        $this->roomCartService = $services->roomCartService;
        $this->activityTransactionService = $services->activityTransactionService;
        $this->serviceTransactionService = $services->serviceTransactionService;
        $this->propertyTransactionService = $services->propertyTransactionService;
        $this->invoiceService = $services->invoiceService;
        $this->emailService = $services->emailService;
        $this->brandingService = $services->brandingService;
        $this->payMongoService = $services->payMongoService;
        $this->notificationService = $services->notificationService;
        $this->receiptService = $services->receiptService;
        $this->paymentService = $services->paymentService;
        $this->cartService = $services->cartService;
        $this->guestDetailService = $services->guestDetailService;
    }


    public function render()
    {
        return view(
            'livewire.admin.events.view-event',
            ['payment_methods' => $this->payment_methods,]
        );
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // ---------------- ROOM, ACTIVITY, SERVICE RELATED PROPERTIES ------------------ //
    public $selectedRooms = [];
    public $selectedActivities = [];
    public $selectedServices = [];


    //To display foreign keys
    public function mount(Transaction $event)
    {
        $this->eventTypes = EventType::all();
        $this->event_invoice = Invoice::where('invoice_type', 'Event_Hall')->get();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();
        $this->loadTransactionData($event);

        // Load existing additional items
        $this->loadExistingItems($event);

        //default date in create payment
        $now = now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');
    }

    // Load existing rooms, activities, and services associated with the event
    protected function loadExistingItems(Transaction $event)
    {
        // Load existing rooms
        $existingRooms = $event->properties()->whereHas('type', function($q) {
            $q->where('name', 'Room');
        })->get();

        foreach ($existingRooms as $room) {
            $this->selectedRooms[] = [
                'type' => 'room',
                'room_id' => $room->id,
                'room_name' => $room->name_number,
                'ideal_guest' => $room->ideal_guest,
            ];
        }

        // Load existing activities
        $existingActivities = $event->activities()->get();
        foreach ($existingActivities as $activity) {
            $this->selectedActivities[] = [
                'type' => 'activity',
                'activity_id' => $activity->id,
                'activity_name' => $activity->name,
                'quantity' => $activity->pivot->quantity,
                'activity_datetime' => $activity->pivot->activity_datetime,
            ];
        }

        // Load existing services
        $existingServices = $event->services()->get();
        foreach ($existingServices as $service) {
            $this->selectedServices[] = [
                'type' => 'service',
                'service_id' => $service->id,
                'service_name' => $service->name,
                'quantity' => $service->pivot->quantity,
                'service_unit' => $service->unit,
            ];
        }
    }

    // Load transaction, invoice, and payments data
    public function loadTransactionData(Transaction $transaction)
    {
        // Eager-load related models to avoid N+1 query problem.
        // This loads relationships only if they haven't already been loaded.
        $transaction->loadMissing([
            'invoice.payments',     // Load the invoice and its related payments
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;
        $this->invoice = $transaction->invoice;            // Store the invoice details
        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();
    }


    // ---------------- EXPORT TO PDF ------------------ //
    public function exportEventDetails()
    {
        //eager load the relationship
        $event = Transaction::with([
            'invoice.payments',
        ])->findOrFail($this->transaction->id);

        $pdf = Pdf::loadView('livewire.admin.events.event-details', [
            'event' => $event,  // Pass the actual event
            //pass the relationship
            'invoice' => $event->invoice,
            'payments' => $event->invoice->payments,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'event-details-' . $event->start_datetime . '.pdf');
    }

    public function deleteEventItem(Transaction $event)
    {
        if (!$event) {
            session()->flash('error', 'Event not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            if (in_array($event->transaction_status, ['done', 'terminated'])) {
                $event->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Event successfully deleted!');
                return redirect()->route('admin.events');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
    }

    // ---------------- CREATE PAYMENT ------------------ //
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

    // Create Payment
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

        // 1️⃣ Create payment
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

        // 2️⃣ Recalculate invoice totals
        $this->recalculateInvoice();

        // 3️⃣ Update transaction status if needed
        if ($this->transaction->transaction_status === 'reserved') {
            $this->transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now(),
            ]);
        }

        // 4️⃣ Reset form fields
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
        ]);

        return redirect()->route('admin.view-event', ['event' => $this->event->id])
            ->with('success', 'Payment created and invoice recalculated successfully.');
    }

    protected function recalculateInvoice()
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



    public function updatePaymentStatus(PaymentService $paymentService, float $amountPaid)
    {
        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);

        $this->transaction->load('activities', 'properties', 'services', 'guestPets');
    }
}
