<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\Activity;
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




    public function render()
    {
        $this->activities = $this->transaction->activities()->withPivot('id', 'quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days', 'room_rate_id')->get();


        return view('livewire.admin.reservations.view-reservation', [
            'activities' => $this->activities,  // Pass activities to the view properly
            'properties' => $this->properties,  // Pass activities to the view properly
        ]);
    }

    public function mount(Transaction $transaction)
    {
        $this->loadTransactionData($transaction);

        // Set default dates for payment
        $now = Carbon::now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');


        $this->availableActivities = Activity::all();
    }

    public function loadTransactionData(Transaction $transaction)
    {
        // Eager-load related models to avoid N+1 query problem.
        // This loads relationships only if they haven't already been loaded.
        $transaction->loadMissing([
            'invoice.payments',     // Load the invoice and its related payments
            'transactionUser',      // Load the user related to the transaction
            'guestDetails',         // Load additional guest details associated with the transaction
            'properties',           // Load the properties (e.g., rooms) included in the transaction
            'activities',           // Load activities (e.g., addons or services) related to the transaction
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;
        $this->transactionUser = $transaction->transactionUser;                // Store the full transaction
        $this->invoice = $transaction->invoice;            // Store the invoice details
        $this->guestDetails = $transaction->guestDetails;  // Store guest details for display
        $this->activities = $transaction->activities;      // Store activities (addons/services)
        $this->properties = $transaction->properties;      // Store properties (rooms)

        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();

        // Get computed totals from model accessors (defined in the Transaction model)
        $this->totalRooms = $transaction->total_rooms;     // Total cost from rooms (via accessor)
        $this->totalAddons = $transaction->total_addons;   // Total cost from addons (via accessor)
    }

    public function updated($property)
    {
        if (Str::startsWith($property, 'quantity.')) {
            // Extract the activity ID from the property name
            $activityId = explode('.', $property)[1];


            // Find activities
            $activity = Activity::find($activityId);
            if (!$activity) {
                return;
            }


            // Update the cart item's quantity dynamically
            foreach ($this->cart as $index => $item) {
                if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                    $quantity = (int) ($this->quantity[$activityId] ?? 0);
                    $activityAmount = $activity->amount * $quantity; // Calculate the new amount based on the new quantity

                    $this->cart[$index]['quantity'] = $quantity;
                    $this->cart[$index]['amount'] = $activityAmount; // Update the amount in the cart
                }
            }
        }
    }

    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }


    // ------------ ADD TRANSACTION:ACTIVITY --------------------- // 

    public function toggleActivityDescription($activityId)
    {
        $this->expandedActivity = $this->expandedActivity === $activityId ? null : $activityId;
    }

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
        $activityPaymentStatus = $this->activityPaymentStatus[$activityId] = 'unpaid'; // Set the payment status of the activity to 'unpaid'

        // Add the activity to the cart if it isn't already present
        $this->cart[] = [
            'type' => 'activity',  // Define the type as 'activity'
            'activity_id' => $activity->id,  // Set the activity ID from the activity object
            'activity_name' => $activity->name,  // Set the activity name
            'quantity' => $quantity,  // Set the quantity from the input or default to 1
            'amount' => $activityAmount,  // Set the calculated amount for the activity
            'status' => $activitystatus,  // Set the status of the activity
            'payment_status' => $activityPaymentStatus,  // Set the payment status of the activity
        ];

        //  dd($this->cart);

        // $this->computeTotalAmount();
    }

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

    public function incrementActivity($activityId)
    {
        $activity = Activity::find($activityId);
        if (!$activity) return;

        // Get the current quantity or default to 1
        $currentQuantity = $this->quantity[$activityId] ?? 1;


        $this->quantity[$activityId] = $currentQuantity + 1;

        foreach ($this->cart as $index => $item) {
            if ($item['type'] === 'activity' && $item['activity_id'] == $activityId) {
                $quantity = $this->quantity[$activityId];
                $this->cart[$index]['quantity'] = $quantity;
                $this->cart[$index]['amount'] = $activity->amount * $quantity;
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

    public function deleteActivity($pivotId)
    {
        $pivot = DB::table('transaction_activities')->where('id', $pivotId)->first();

        if (! $pivot) {
            return;
        }

        DB::transaction(function () use ($pivotId) {
            // Delete the activity from the pivot table
            DB::table('transaction_activities')->where('id', $pivotId)->delete();

            // Refresh transaction relationships to reflect deletion
            $this->transaction->refresh();
            $this->transaction->loadMissing(['activities', 'properties']);

            // Recalculate everything
            $this->recalculateInvoice();

            // Reset manual override if any
            $this->invoice->update(['requested_remaining_balance' => false]);

            // Optionally re-evaluate invoice status
            if (
                $this->invoice->balance_due > 0 &&
                $this->invoice->invoice_status === 'completed'
            ) {
                $this->invoice->invoice_status = 'pending';
                $this->invoice->save();
            }
        });

        $this->refreshInvoice();
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

    public function updateActivity()
    {
        // Validate input
        $this->validate([
            'activityQuantity' => 'required|integer|min:1',
        ], [
            'activityQuantity.min' => 'Quantity must be at least 1.',
            'activityQuantity.required' => 'Quantity is required.',
        ]);

        // Fetch the original activity amount
        $activity = $this->transaction->activities->firstWhere('pivot.id', $this->editingActivityId);

        if (!$activity) {
            session()->flash('error', 'Activity not found.');
            return;
        }

        $newAmount = $this->activityQuantity * $activity->amount;

        DB::table('transaction_activities')
            ->where('id', $this->editingActivityId)
            ->update([
                'quantity' => $this->activityQuantity,
                'amount' => $newAmount,
                'updated_at' => now(),
            ]);

        // Reload updated transaction data before recalculating
        $this->transaction->refresh(); // Refreshes relationships too if eager-loaded
        $this->transaction->loadMissing(['activities', 'properties']);

        // Recalculate subtotal and balance due
        $this->recalculateInvoice();

        $this->showEditActivityModal = false;
        $this->dispatch('activity-updated');
    }

    // ------------------------ INVOICE UPDATES -------------------------- //

    public function updateInvoiceGrandTotal()
    {
        $activities = $this->transaction->activities ?? collect();
        $properties = $this->transaction->properties ?? collect();

        $activitiesTotal = $activities->map(function ($activity) {
            return $activity->pivot->quantity * $activity->amount;
        })->sum();

        $roomsTotal = $properties->map(function ($property) {
            return $property->pivot->total_amount;
        })->sum();

        // Fetch the convenience fee total
        $this->convenienceFeeTotal = $this->computeConvenienceFeeTotal();

        $subtotal = $activitiesTotal + $roomsTotal + $this->convenienceFeeTotal;

        $this->invoice->update([
            'sub_total' => $subtotal,
        ]);
    }

    public function updateInvoiceBalanceDue()
    {
        // Get the latest payments related to the invoice
        $payments = $this->payments ?? collect();

        // Sum only successful or completed payments
        $totalPaid = $payments->where('payment_status', 'completed')->sum('amount_paid');

        // Get the latest subtotal from the invoice
        $subTotal = $this->invoice->sub_total;

        // Compute the remaining balance with floor at 0
        $balanceDue = max($subTotal - $totalPaid, 0);

        // Update the invoice
        $this->invoice->update([
            'balance_due' => $balanceDue,
            'amount_paid' => $totalPaid,
        ]);
    }

    public function updateInvoiceStatus()
    {
        $invoice = $this->invoice;

        if ($invoice->balance_due <= 0) {
            // Fully paid
            if (!$invoice->completed_at) {
                $invoice->update([
                    'invoice_status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        } elseif ($invoice->balance_due > 0 && $invoice->sub_total > 0) {
            // Has remaining balance
            $invoice->update([
                'invoice_status' => 'pending',
                'completed_at' => null,
            ]);
        } else {
            // No subtotal or unpaid
            $invoice->update([
                'invoice_status' => 'failed',
                'completed_at' => null,
            ]);
        }
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

    public function recalculateInvoice()
    {
        $this->updateInvoiceGrandTotal();
        $this->updateInvoiceBalanceDue();
        $this->updateInvoiceStatus();
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




    // ------------------ EMAIL SENDING ------------------------ // 

    public function sendReceiptToEmail()
    {
        Log::info('Send Receipt To Email Method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            Log::error('Missing data for sending official receipt.');
            abort(404, 'Missing data for generating the official receipt.');
        }

        // Get related data for PDF
        $activities = $this->transaction->activities()->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();
        $properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();

        //Mount the branding
        $setting = Setting::first();

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'properties' => $properties,
            'activities' => $activities,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];

        // Generate PDF from view with data
        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);
        $pdfContent = $pdf->output();

        // Send mail with attachment
        Mail::to($this->transactionUser->email)->send(new SendOfficialReceiptMail($pdfContent, $this->receipt->receipt_number, $this->transactionUser, $data));

        session()->flash('message', 'Official receipt has been sent to guest\'s email!');

        Log::info('Official receipt sent to email: ' . $this->transactionUser->email);
    }

    public function requestRemainingBalance()
    {
        Log::info('Request Remaining Balance method called.');


        $transaction = $this->transaction;
        $invoice = $this->invoice;
        $transactionUser = $this->transactionUser;

        $client = new Client();
        $apiKey = env('PAYMONGO_SECRET_KEY');
        $remainingBalanceInCentavos = intval($invoice->balance_due * 100);

        try {
            $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
                ],
                'json' => [
                    'data' => [
                        'attributes' => [
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                            'payment_method_types' => ['card', 'gcash', 'qrph', 'paymaya'],
                            'success_url' => route('guest.thank-you-page'),
                            'cancel_url' => url('/payment-failed'),
                            'line_items' => [[
                                'currency' => 'PHP',
                                'amount' => $remainingBalanceInCentavos,
                                'description' => 'Reservation ' . $transaction->transaction_number,
                                'name' => 'Canopy Farm PH',
                                'quantity' => 1,
                            ]],
                            'description' => 'Reservation for ' . $transactionUser->first_name . ' ' . $transactionUser->last_name,
                            'metadata' => [
                                'invoice_id' => (string) $invoice->id,
                                'payment_type' => 'Remaining Balance',
                                'notes' => 'Payment for Remaining Balance',
                            ],
                        ],
                    ],
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);
            $paymentLink = $responseData['data']['attributes']['checkout_url'] ?? null;

            if ($paymentLink) {
                $this->transaction->update(['payment_link' => $paymentLink]);
            }
        } catch (\Exception $e) {
            Log::error('PayMongo link creation failed: ' . $e->getMessage());
            $paymentLink = null; // fallback
        }

        // Update the invoice to flag that the request has been sent
        $this->invoice->requested_remaining_balance = true;
        $this->invoice->save();

        //Call setting
        $setting = Setting::first();

        $reservationData = [
            'name' => trim($transactionUser->first_name . ' ' . $transactionUser->last_name),
            'transaction_number' => $transaction->transaction_number,
            'email' => $transactionUser->email ?? 'no-reply@example.com',
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $transaction->start_datetime->format('Y-m-d'),
            'check_out' => $transaction->end_datetime->format('Y-m-d'),
            'sub_total' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'remaining_balance' => $invoice->balance_due,
            'payment_link' => $paymentLink,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];

        try {
            Mail::to($reservationData['email'])->send(new RequestRemainingBalanceMail($reservationData));
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation saved, but confirmation email failed to send.');
        }

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction->id]);
    }




    // --------------- OFFICIAL RECEIPT ----------------------- // 
    public function GenerateReceipt()
    {
        Log::info('Show Generate Official Receipt Modal method triggered.');

        // Ensure invoice is available
        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        // Prevent generating receipt for unpaid invoices
        if ($this->invoice->invoice_status != 'completed') {
            $this->cannotGenerateReceiptModal = true;
            Log::warning("Attempt to generate receipt for unpaid invoice ID: {$this->invoice->id}");
            return;
        }

        // Check if receipt already exists
        $existing = Receipt::where('invoice_id', $this->invoice->id)->first();
        if ($existing) {
            $this->receipt = $existing;
            Log::info('Receipt already exists for invoice ID: ' . $this->invoice->id);
        } else {
            // Generate unique receipt number
            $datePart = now()->format('Ymd');
            $lastReceipt = Receipt::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')->first();

            $newNumber = $lastReceipt
                ? str_pad(((int) substr($lastReceipt->receipt_number, -4)) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $receiptNumber = "OR-{$datePart}-{$newNumber}";

            $this->receipt = Receipt::create([
                'invoice_id'      => $this->invoice->id,
                'receipt_number'  => $receiptNumber,
                'amount_received' => $this->invoice->amount_paid,
                'receipt_date'    => now(),
                'notes'           => 'Official receipt generated via system',
            ]);

            Log::info("Receipt created: {$receiptNumber} for Invoice ID {$this->invoice->id}");
        }
    }

    public function printOfficialReceipt()
    {
        Log::info('Print Official Receipt method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for generating the official receipt.');
        }

        $this->activities = $this->transaction->activities()->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
            'transactionUser' => $this->transactionUser,
            'properties' => $this->properties,
            'activities' => $this->activities,
        ];

        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'official_receipt_' . $this->receipt->receipt_number . '.pdf');
    }




    // -------------------- REPORTS --------------------------- // 

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




    // --------------------- MODALS --------------------------- // 

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

    public function OpenAddActivityModal()
    {

        Log::info('Open Add Activity Modal method called.');
        $this->addActivityModal = true;
    }

    public function CloseAddActivityModal()
    {

        Log::info('Close Add Activity Modal called.');
        $this->addActivityModal = false;
    }




    // --------------------- DATABASE INSERTION --------------------------- // 

    public function CreatePayment()
    {
        Log::info('Create Payment method called.');

        // Validate the input data
        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Ensure the invoice exists
        if (!$this->invoice) {
            abort(404, 'No invoice found for this transaction.');
        }

        // Create the payment record
        Payment::create([
            'invoice_id' => $this->invoice->id,
            'amount_paid' => $this->amount_paid,
            'mode_of_payment' => 'cash',
            'payment_type' => $this->payment_type,
            'payment_date' => $this->payment_date,
            'payment_status' => 'completed',
            'notes' => $this->notes,
            'currency' => 'PHP',
            'verified_at' => now(),
        ]);



        // Update the invoice with the new amount paid and balance due
        $newAmountPaid = $this->invoice->amount_paid + $this->amount_paid;
        $newBalanceDue = max($this->invoice->sub_total - $newAmountPaid, 0);

        $this->invoice->update([
            'amount_paid' => $newAmountPaid,
            'balance_due' => $newBalanceDue,
        ]);

        // If the balance is 0, update invoice status to 'completed'
        if ($newBalanceDue == 0) {
            $this->invoice->update([
                'invoice_status' => 'completed',
                'completed_at' => now(),
            ]);

            // Log status change
            Log::info("Invoice status updated to 'completed' because balance due is 0.");
        }

        // If the new amount paid is greater than or equal to the deposit amount, update transaction status to 'receipt_verified'
        if ($newAmountPaid >= $this->transaction->deposit_amount) {
            $this->transaction->update(['transaction_status' => 'receipt_verified']);
            Log::info("Transaction status updated to 'reserved' because amount paid is greater than or equal to deposit amount.");
        }

        $this->updatePaymentStatus();


        // Reset the form fields after successful creation
        $this->reset([
            'amount_paid',
            'mode_of_payment',
            'payment_type',
            'payment_date',
            'payment_status',
            'notes',
            'currency',
            'verified_at',
        ]);

        // Redirect to the same reservation view to refresh data
        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction->id])
            ->with('success', 'Payment created successfully.');
    }

    public function saveActivity()
    {
        $this->resetErrorBag();

        DB::transaction(function () {
            foreach ($this->cart as $item) {
                if ($item['type'] === 'activity') {
                    DB::table('transaction_activities')->insert([
                        'transaction_id' => $this->transaction->id,
                        'activity_id' => $item['activity_id'],
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount'],
                        'payment_status' => $item['payment_status'] ?? 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Refresh relationships to include newly added activities
            $this->transaction->refresh();
            $this->transaction->loadMissing(['activities', 'properties']);

            // Centralized logic to update invoice values
            $this->recalculateInvoice();

            // Reset any requested manual override
            $this->invoice->update([
                'requested_remaining_balance' => false,
            ]);

            // Sync invoice status
            if (
                $this->invoice->balance_due > 0 &&
                $this->invoice->invoice_status === 'completed'
            ) {
                $this->invoice->invoice_status = 'pending';
                $this->invoice->save();
            }
        });

        $this->cart = [];
        $this->addActivityModal = false;
        $this->refreshInvoice();
    }
}
