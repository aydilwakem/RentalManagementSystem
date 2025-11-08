<?php

namespace App\Livewire\Admin\Reservations;

use App\Mail\ConfirmationEmail;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\Property;
use App\Models\ReservationType;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationCompletedMail;
use App\Models\Setting;
use Spatie\Activitylog\Models\Activity as LogActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReservationList extends Component
{
    use WithPagination; // Enables pagination for Livewire component
    // Properties that can be modified via URL parameters

    #[Url(history: true)]
    public $search = ''; // Search term for filtering transactions

    #[Url]
    public $perPage = 10; // Number of transactions displayed per page
    public $invoice;

    #[Url(history: true)]
    public $sortBy = 'updated_at'; // Column used for sorting transactions

    #[Url(history: true)]
    public $sortDir = 'DESC'; // Sorting direction (ascending/descending)

    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 2;

    // ------------------------------- FLAGS --------------------------------------------- //
    public $confirmingAction = false;
    public $cannotMarkAsDoneModal = false;
    public $actionTitle = '';
    public $actionMessage = '';
    public $actionMethod = '';
    public $actionId;
    public $actionButtonType = 'default';

    //-------------------------------- BRANDING -------------------------------------------- //
    public string $companyName = 'Company'; //Default
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;


    //------------------------------------MOUNT------------------------------------------ //
    public function mount()
    {
        // Ensure reservation-list use a separate session key
        if (!session()->has('fake_ids_reservation_list')) {
            session(['fake_ids_reservation_list' => []]);
        }
    }

    // -------------------------------------- RENDER -------------------------------------- //

    /**
     * Renders the Livewire component view and fetches transactions based on filters
     */

    public function render()
    {
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->distinct()
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id')
            ->with(['transactionUser', 'properties' => function ($query) {
                $query->where('property_type_id', 1);
            }])
            ->where('reservation_type_id', 2)
            ->nonArchived()
            ->when($this->search !== '', function ($query) {
                $query
                    ->whereHas('transactionUser', function ($subQuery) {
                        $subQuery
                            ->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                    })
                    ->orWhereHas('properties', function ($subQuery) {
                        $subQuery->where('name_number', 'like', '%' . $this->search . '%')
                            ->where('property_type_id', 1);
                    });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Query for full list (for fake ID generation only)
        $allTransactions = Transaction::where('reservation_type_id', 2)->orderBy('created_at', 'ASC')->get();

        //Store in session
        $fakeIDs = session('fake_ids_reservation_list', []);

        //Refresh to prevent duplicates in event & lease transactions
        $needsRefresh = count($fakeIDs) !== $allTransactions->count();

        // Check if any existing ID doesn't start with TXN-
        foreach ($fakeIDs as $id => $fake) {
            if (!str_starts_with($fake, 'TXN-')) {
                $needsRefresh = true;
                break;
            }
        }

        if ($needsRefresh) {
            $fakeIDs = [];
            foreach ($allTransactions as $index => $transaction) {
                $fakeIDs[$transaction->id] = 'TXN-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_reservation_list' => $fakeIDs]);
        }

        return view('livewire.admin.reservations.reservation-list', compact('transactions', 'fakeIDs'));
    }

    // -------------------------------------- CONFIRMATION MODAL -------------------------------------- //

    public function showActionModal($method, $title, $message, $id, $actionType = 'default')
    {
        $this->actionMethod = $method; // e.g., 'deleteTransaction'
        $this->actionTitle = $title; // e.g., 'Delete Transaction'
        $this->actionMessage = $message; // e.g., 'Are you sure you want to delete this transaction?'
        $this->actionId = $id; // Store the ID for the action
        $this->confirmingAction = true; // Trigger the confirmation modal
        $this->actionButtonType = $actionType; // categorize if safe or desctructive action
    }

    public function executeAction()
    {
        if (method_exists($this, $this->actionMethod)) {
            // Dynamically call the appropriate method (deleteTransaction, cancelTransaction, etc.)
            $this->{$this->actionMethod}($this->actionId);
        }
        $this->confirmingAction = false; // Close the modal after action
    }

    // -------------------------------------- BUTTON ACTIONS -------------------------------------- //

    /**
     * Confirms the selected transaction
     */



    public function computeInvoiceWithDiscount(): float
    {
        $baseSubtotal = $this->computeBaseSubtotal();

        // Sum of all applied discounts (PWD + Senior, etc.)
        $totalDiscount = $this->invoice->discounts->sum('discount_value') ?? 0;

        return max($baseSubtotal - $totalDiscount, 0);
    }


    public function confirmReservation($id)
    {
        $transaction = Transaction::with([
            'transactionUser',
            'invoice.payments',
            'properties.category',
            'activities',
            'services',
            'promoCode'
        ])
            ->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        // Update transaction status
        $transaction->update(['transaction_status' => 'confirmed']);
        session()->flash('message', 'Transaction successfully confirmed!');

        // Gather user and invoice data,
        //and properties and activities
        $user = $transaction->transactionUser;
        $invoice = $transaction->invoice;
        $properties = $transaction->properties;
        $activities = $transaction->activities;
        $services = $transaction->services;

        if (!$user || !$invoice) {
            logger()->error('User or invoice not found for transaction ID ' . $id);
            session()->flash('error', 'Confirmation email could not be sent due to missing data.');
            return;
        }

        //Add convenience fee total
        $convenienceFeeTotal = $invoice->payments->sum('convenience_fee');

        //Call setting
        $setting = Setting::first();

        // Prepare data for email
        $reservationData = [
            'properties' => $properties,
            'activities' => $activities,
            'services' => $services,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->transaction_number,
            'email' => $user->email,
            'check_in' => $transaction->start_datetime,
            'check_out' => $transaction->end_datetime,
            'deposit' => $transaction->deposit_paid,
            'convenience_fee' => $convenienceFeeTotal,
            'deposit_amount' => $transaction->deposit_amount,
            'sub_total' => $transaction->sub_total,
            'promo_discount_amount' => $transaction->promo_discount_amount,
            'requests' => $transaction->requests,
            'request_reply' => $transaction->request_reply,

            'invoice_number' => $invoice->invoice_number,
            'invoice_basesubtotal' => $invoice->base_subtotal,
            'invoice_total_discount' => $invoice->total_discount,
            'invoice_subtotal' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid, //see the amount paid once reservation is confirmed
            'balance_due' => $invoice->balance_due,
            'total_amount' => $invoice->sub_total,

            //Call promo code
            'code' => optional($transaction->promoCode)->code,
            'discount_type' => optional($transaction->promoCode)->discount_type,
            'discount_value' => optional($transaction->promoCode)->discount_value,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];
        logger()->info('Reservation Data:', $reservationData);
        try {
            Mail::to($reservationData['email'])->send(new ReservationConfirmedMail($reservationData));
        } catch (\Exception $e) {
            logger()->error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation confirmed, but email failed to send.');
        }
    }

    /**
     * Starts the selected transaction
     */
    public function startReservation($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            $transaction->update([
                'transaction_status' => 'ongoing', // Update status
                'actual_start_datetime' => now(),   // Record actual check-in
            ]);

            session()->flash('message', 'Transaction successfully started and check-in recorded!');
        } else {
            session()->flash('error', 'Transaction not found.');
        }
    }


    /**
     * Marks the selected transaction as 'done'
     */
    public function markAsDone($id)
    {
        $transaction = Transaction::with([
            'transactionUser',
            'invoice.payments',
            'properties.category',
            'activities',
            'services'
        ])->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        $invoice = $transaction->invoice;

        if (!$invoice || $invoice->invoice_status !== 'completed') {
            $this->cannotMarkAsDoneModal = true;
            return;
        }

        // Update transaction status and record actual check-out
        $transaction->update([
            'transaction_status' => 'done',
            'actual_end_datetime' => now(), // Record actual checkout
        ]);

        session()->flash('message', 'Transaction successfully marked as done!');

        $user = $transaction->transactionUser;
        if (!$user) {
            logger()->error('User not found for transaction ID ' . $id);
            session()->flash('error', 'Confirmation email could not be sent due to missing user data.');
            return;
        }

        $properties = $transaction->properties;
        $activities = $transaction->activities;
        $services = $transaction->services;
        $setting = Setting::first();

        // Prepare email data using actual check-in/out if available
        $reservationData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->transaction_number,
            'check_in' => $transaction->actual_start_datetime ?? $transaction->start_datetime,
            'check_out' => $transaction->actual_end_datetime ?? $transaction->end_datetime,
            'deposit' => $transaction->deposit_paid,
            //'convenience_fee' => $transaction->convenience_fee,
            'promo_discount_amount' => $transaction->promo_discount_amount,
            'sub_total' => $transaction->sub_total,
            'convenience_fee' => $invoice->payments->sum('convenience_fee'),
            'invoice_number' => $invoice->invoice_number,
            'invoice_basesubtotal' => $invoice->base_subtotal,
            'invoice_total_discount' => $invoice->total_discount,
            'invoice_subtotal' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'balance_due' => $invoice->balance_due,
            'total_amount' => $invoice->sub_total,
            'properties' => $properties,
            'activities' => $activities,
            'services' => $services,
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];

        try {
            Mail::to($reservationData['email'])->send(new ReservationCompletedMail($reservationData));
        } catch (\Exception $e) {
            logger()->error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation marked as done, but email failed to send.');
        }
    }


    /**
     * Marks the selected transaction as 'no show'
     */
    public function markNoShow($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'no_show']); // Update transaction status to 'no show'
            session()->flash('message', 'Transaction marked as no show!');
        }

        Log::info('Transaction ID: ' . $id);
        Log::info('Transaction: ', [$transaction]);
    }

    /**
     * Cancels the selected transaction
     */
    public function cancelReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'cancelled']); // Update transaction status to 'cancelled'
            session()->flash('message', 'Transaction successfully cancelled!');
        }
    }

    /**
     * Terminates the selected transaction
     */
    public function terminateReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'terminated']); // Update transaction status to 'terminated'
            session()->flash('message', 'Transaction successfully terminated!');
        }
    }

    /**
     * Deletes the selected transaction from the database
     */
    public function deleteReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete(); // Permanently delete the transaction
            session()->flash('message', 'Transaction successfully deleted!');
        }
    }



    // ------------------------ ALL CHECKOUT EXPORT PDF METHOD ------------------------------- //
    public function exportCheckoutsToday()
    {

        //FOR TESTING - Add target date of checkout that's in your reservation-list
        //$today = Carbon::create(2025, 7, 8);

        $today = Carbon::today();

        //Fetch all transactions with end_datetime today
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->with(['transactionUser', 'properties'])
            ->whereDate('end_datetime', $today)
            ->where('reservation_type_id', 2)
            ->nonArchived()
            ->orderBy('end_datetime')
            ->get();

        //Summary of transactions
        $totalCheckouts = $transactions->count();
        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');

        //Pass variables in pdf
        $pdf = Pdf::loadView('livewire.admin.reports.checkouts-today-report', [
            'transactions' => $transactions,
            'date' => $today->toDateString(),
            'totalCheckouts' => $totalCheckouts,
            'totalGuests' => $totalGuests,
            'totalAmountEarned' => $totalAmountEarned
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Checkouts-Today-' . $today->format('Ymd') . '.pdf');
    }




    // -------------------------------------- SORTING --------------------------------------- //

    /**
     * Changes the sorting column and direction when a user selects a different column
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC'; // Toggle sorting direction
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC'; // Default sorting direction when changing columns
    }


    // -------------------------------------- ROLLBACK OF STATUS ---------------------------- //
    /**
     * This method is used to rollback the status of a transaction.
     * It is currently not implemented, but can be used in the future if needed.
     */

    public function rollbackStatus($id)
    {
        Log::info("Rollback status method called for transaction ID: {$id}");

        // Find the transaction
        $transaction = Transaction::find($id);

        if (!$transaction) {
            Log::warning("Transaction not found for ID: {$id}");
            return back()->with('error', 'Transaction not found.');
        }

        $currentStatus = $transaction->transaction_status;

        // Rollback map: define allowed one-step rollbacks
        $rollbackMap = [
            'done' => 'ongoing',
            'ongoing' => 'confirmed',
            'confirmed' => 'receipt_verified',
            'receipt_verified' => 'reserved',
            'reserved' => 'pending',

            'cancelled' => 'reserved',

            'no_show' => 'confirmed',
            'terminated' => 'ongoing',
            'expired' => 'pending',
        ];

        // Determine previous status
        $previousStatus = $rollbackMap[$currentStatus] ?? null;

        if (!$previousStatus) {
            return back()->with('error', 'No previous status available for rollback.');
        }

        // Update the transaction
        $transaction->transaction_status = $previousStatus;
        $transaction->save();

        Log::info("Transaction ID {$id} rolled back from {$currentStatus} to {$previousStatus}");

        return back()->with('success', "Status rolled back to: {$previousStatus}");
    }

    // ---------------------------------- LAZY LOADING ---------------------------- //
    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }

    /**
     * Archives the selected transaction
     */
    public function archiveReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'archived']);
            session()->flash('message', 'Reservation successfully archived!');
        }
    }
}
