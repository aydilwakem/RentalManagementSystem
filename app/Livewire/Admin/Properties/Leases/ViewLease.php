<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentMethod;
use App\Services\PaymentService;
use App\Services\ServiceBag;
use App\Services\InvoiceService;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ViewLease extends Component
{

    use WithFileUploads;

    public Transaction $transaction;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public $invoice;
    public $payments;

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
    public $sub_total;
    public $balance_due;

    // ---------------------------- MODALS -------------------------- //
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $createPaymentModal = false;


    // --------------------- PAYMENTS ------------------------- //
    public $payment_methods;
    public $payment_method_id;
    public $payment_screenshot;

    // --------------------- SERVICES ------------------------- //

    protected PaymentService $paymentService;
    protected InvoiceService $invoiceService;


    // ---------------- EDIT PAYMENT MODAL PROPERTIES ------------------ //
    public $showEditPaymentModal = false;
    public $editingPayment;
    public $edit_amount_paid;
    public $edit_payment_date;
    public $edit_payment_type;
    public $edit_notes;
    public $edit_payment_method_id;
    public $edit_payment_screenshot;
    public $existing_payment_screenshot;

    public function boot(ServiceBag $services)
    {
        $this->paymentService = $services->paymentService;
        $this->invoiceService = $services->invoiceService;
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount(Transaction $transaction)
    {
        $this->loadTransactionData($transaction);

        //default date in create payment modal
        $now = Carbon::now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');
        $this->payment_methods = PaymentMethod::all();
    }

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

    public function deleteLease(Transaction $transaction)
    {
        if (!$transaction) {
            session()->flash('error', 'Lease not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            if (in_array($transaction->transaction_status, ['done', 'terminated'])) {
                $transaction->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Lease successfully deleted!');
                return redirect()->route('admin.leases');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
    }


    public function exportLeaseDetails()
    {
        //eager load the relationship
        $transaction = Transaction::with([
            'invoice.payments',
        ])->findOrFail($this->transaction->id);

        $pdf = Pdf::loadView('livewire.admin.properties.leases.lease-details', [
            'transaction' => $transaction,  // Pass the actual lease
            //pass the relationship
            'invoice' => $transaction->invoice,
            'payments' => $transaction->invoice->payments,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Lease-Details-' . $transaction->invoice->invoice_number . '.pdf');
    }

    public function getMonthCount($startDatetime, $endDatetime)
    {
        //parse the end and start date
        $start = Carbon::parse($startDatetime);
        $end = Carbon::parse($endDatetime);

        //get the number of months in between
        $months = $start->diffInMonths($end);

        //if the start and end are in the same month, we count it as 1
        if ($start->isSameMonth($end)) {
            return 1;
        }

        //add 1 to include the starting month
        return $months + 1;
    }


    public function render()
    {
        return view('livewire.admin.properties.leases.view-lease');
    }

    public function editPayment($paymentId)
    {
        $this->editingPayment = \App\Models\Payment::with('paymentMethod')->find($paymentId);
        
        if ($this->editingPayment) {
            $this->edit_amount_paid = $this->editingPayment->amount_paid;
            $this->edit_payment_date = $this->editingPayment->payment_date 
                ? \Carbon\Carbon::parse($this->editingPayment->payment_date)->format('Y-m-d')
                : now()->format('Y-m-d');
            $this->edit_payment_type = $this->editingPayment->payment_type;
            $this->edit_notes = $this->editingPayment->notes;
            $this->edit_payment_method_id = $this->editingPayment->payment_method_id;
            $this->existing_payment_screenshot = $this->editingPayment->payment_screenshot;
            $this->edit_payment_screenshot = null;
            $this->showEditPaymentModal = true;
        }
    }

    public function updatePayment()
    {
        $this->validate([
            'edit_amount_paid' => 'required|numeric|min:0',
            'edit_payment_type' => 'required|in:House Rent,Security Deposit',
            'edit_payment_date' => 'required|date',
            'edit_notes' => 'nullable|string|max:500',
            'edit_payment_method_id' => 'required|exists:pm_payment_methods,id',
            'edit_payment_screenshot' => 'nullable|image|max:2048',
        ]);

        if ($this->editingPayment) {
            // Upload new screenshot if provided
            $screenshotPath = $this->uploadEditScreenshot();

            $updateData = [
                'amount_paid' => $this->edit_amount_paid,
                'payment_type' => $this->edit_payment_type,
                'payment_date' => $this->edit_payment_date,
                'notes' => $this->edit_notes,
                'payment_method_id' => $this->edit_payment_method_id,
                'updated_at' => now(),
            ];

            // Only update screenshot if a new one was uploaded
            if ($screenshotPath) {
                $updateData['payment_screenshot'] = $screenshotPath;
            }

            $this->editingPayment->update($updateData);

            // Recalculate invoice totals
            $this->recalculateInvoice();
            
            $this->showEditPaymentModal = false;
            return redirect()->route('admin.view-lease', ['transaction' => $this->transaction->id])
                ->with('success', 'Payment updated successfully.');
        }
    }

    public function closeEditPaymentModal()
    {
        $this->showEditPaymentModal = false;
        $this->reset([
            'editingPayment', 
            'edit_amount_paid', 
            'edit_payment_date', 
            'edit_payment_type', 
            'edit_notes', 
            'edit_payment_method_id',
            'edit_payment_screenshot',
            'existing_payment_screenshot'
        ]);
    }

    protected function uploadEditScreenshot(): ?string
    {
        // If no file was uploaded, return null
        if (!$this->edit_payment_screenshot) {
            return null;
        }

        // If uploaded file is invalid
        if (!$this->edit_payment_screenshot->isValid()) {
            throw new \Exception('Image upload failed. Please try again.');
        }

        // Store and return the file path
        return $this->edit_payment_screenshot->store('proof-of-payments', 'public');
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


    // --------------------- DATABASE INSERTION --------------------------- //


    public function CreatePayment(PaymentService $paymentService)
    {
        Log::info('Create Payment method called.');

        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:House Rent,Security Deposit',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'payment_method_id' => 'required|exists:pm_payment_methods,id',
            'payment_screenshot' => 'nullable|image|max:2048',
        ]);

        if (!$this->invoice) {
            abort(404, 'No invoice found.');
        }

        // Gets the screenshotpath of the image
        $screenshotPath = $this->uploadScreenshot();


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
            'payment_screenshot' => $screenshotPath,
            'payment_method_id' => $this->payment_method_id,
        ]);

        $this->updatePaymentStatus($paymentService, $this->amount_paid);
        $this->recalculateInvoice();

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

        return redirect()->route('admin.view-lease', ['transaction' => $this->transaction->id])
            ->with('success', 'Payment created successfully.');
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



    // ---------------------- HELPER METHODS --------------------------- //

    public function updatePaymentStatus(PaymentService $paymentService, float $amountPaid)
    {
        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);

        $this->transaction->load('activities', 'properties', 'services', 'guestPets');
    }

    public function recalculateInvoice()
    {
        $this->invoiceService->updateDiscountTotal($this->invoice, $this->transaction);
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
}
