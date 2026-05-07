<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Setting;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationCompletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ViewLeases extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public Transaction $transaction; // Holds the current transaction for lease
    //public $transactions;
    public $leases = [];

    public $cannotDeleteItem = false;
    public $selectedLeaseId = null;
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;


    // ------------------------------- FLAGS --------------------------------------------- //
    public $confirmingAction = false;
    public $cannotMarkAsDoneModal = false;
    public $actionTitle = '';
    public $actionMessage = '';
    public $actionMethod = '';
    public $actionId;
    public $actionButtonType = 'default';


    public $statusFilter = ''; // Filter transactions by status

    //public declaration for bulk actions
    public $selectedRows = [];
    public $selectPageRows = false;

    //lazy loading
    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }



    public function mount()
    {
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_leases')) {
            session(['fake_ids_leases' => []]);
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function render()
    {
        $transactions = Transaction::with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 1) // House reservation type
            ->where('transaction_status', '!=', 'archived')
            ->when($this->search !== '', function ($query) {
                $search = '%' . $this->search . '%';
                $query->whereHas('transactionUser', function ($subQuery) use ($search) {
                    $subQuery
                        ->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search]);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $leaseTransactions = Transaction::where('reservation_type_id', 1)->orderBy('created_at', 'ASC')->get();

        $fakeIDs = session('fake_ids_leases', []);

        if (count($fakeIDs) !== $leaseTransactions->count()) {
            $fakeIDs = [];
            foreach ($leaseTransactions as $index => $leaseItem) {
                $fakeIDs[$leaseItem->id] = 'LEASE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_leases' => $fakeIDs]);
        }
        return view('livewire.admin.properties.leases.view-leases', compact('transactions', 'fakeIDs'));
    }

    //Get monthly rent for display:
    public function getMonthlyRent($transaction)
    {
        if (empty($transaction->start_datetime) || empty($transaction->end_datetime) || !is_numeric($transaction->total_amount) || $transaction->total_amount <= 0) {
            return 0;
        }

        $start = Carbon::parse($transaction->start_datetime)->startOfDay();
        $end = Carbon::parse($transaction->end_datetime)->startOfDay();

        if ($start->gt($end)) {
            return 0;
        }

        // Calculate the difference in months between the start and end date, inclusive of both months.
        $months = $start->diffInMonths($end) + 1;

        if ($months <= 0) {
            return 0;
        }

        return round($transaction->total_amount / $months, 2);
    }


    public function confirmLease($id)
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
        // logger()->info('Reservation Data:', $reservationData);
        // try {
        //     Mail::to($reservationData['email'])->send(new ReservationConfirmedMail($reservationData));
        // } catch (\Exception $e) {
        //     logger()->error('Email send failed: ' . $e->getMessage());
        //     session()->flash('error', 'Reservation confirmed, but email failed to send.');
        // }
    }

    /**
     * Starts the selected transaction
     */
    public function startLease($id)
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

        // try {
        //     Mail::to($reservationData['email'])->send(new ReservationCompletedMail($reservationData));
        // } catch (\Exception $e) {
        //     logger()->error('Email send failed: ' . $e->getMessage());
        //     session()->flash('error', 'Reservation marked as done, but email failed to send.');
        // }
    }

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
    public function cancelLease($id)
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
    public function terminateLease($id)
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
    public function deleteLease($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete(); // Permanently delete the transaction
            session()->flash('message', 'Transaction successfully deleted!');
        }
    }

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

    public function archiveLease($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'archived']);
            session()->flash('message', 'Lease successfully archived!');
        }
    }

}
