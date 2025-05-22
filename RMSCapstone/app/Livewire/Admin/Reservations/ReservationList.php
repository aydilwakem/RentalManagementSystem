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
use Illuminate\Support\Facades\Mail;


class ReservationList extends Component
{

    use WithPagination; // Enables pagination for Livewire component

    #[Url(history: true)] // Properties that can be modified via URL parameters
    public $search = ''; // Search term for filtering transactions

    #[Url()]
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

    // -------------------------------------- MOUNT -------------------------------------- //

    public function mount()
    {
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
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
            ->with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 2)
            ->when($this->search !== '', function ($query) {
                $query->whereHas('transactionUser', function ($subQuery) {
                    $subQuery->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%" . $this->search . "%"]);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.reservations.reservation-list', compact('transactions'));
    }


    // -------------------------------------- CONFIRMATION MODAL -------------------------------------- //

    public function showActionModal($method, $title, $message, $id, $actionType = 'default')
    {
        $this->actionMethod = $method;  // e.g., 'deleteTransaction'
        $this->actionTitle = $title;    // e.g., 'Delete Transaction'
        $this->actionMessage = $message; // e.g., 'Are you sure you want to delete this transaction?'
        $this->actionId = $id;          // Store the ID for the action
        $this->confirmingAction = true;  // Trigger the confirmation modal
        $this->actionButtonType = $actionType; // categorize if safe or desctructive action
    }

    public function executeAction()
    {
        if (method_exists($this, $this->actionMethod)) {
            // Dynamically call the appropriate method (deleteTransaction, cancelTransaction, etc.)
            $this->{$this->actionMethod}($this->actionId);
        }
        $this->confirmingAction = false;  // Close the modal after action
    }

    // -------------------------------------- BUTTON ACTIONS -------------------------------------- //

    /**
     * Confirms the selected transaction
     */

    public function confirmReservation($id)
    {
        $transaction = Transaction::with(['transactionUser', 'invoice', 'properties.category', 'activities'])->find($id);

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

        if (!$user || !$invoice) {
            logger()->error('User or invoice not found for transaction ID ' . $id);
            session()->flash('error', 'Confirmation email could not be sent due to missing data.');
            return;
        }

        // Prepare data for email
        $reservationData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->id,
            'email' => $user->email,
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $transaction->start_datetime,
            'check_out' => $transaction->end_datetime,
            'total_amount' => $invoice->amount_paid,
            'deposit' => $transaction->deposit_paid,
            'amount_paid' => $invoice->amount_paid, //see the amount paid once reservation is confirmed
            'balance_due' =>  $invoice->balance_due,
            'properties' => $properties,
            'activities' => $activities,
        ];

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
            $transaction->update(['transaction_status' => 'ongoing']); // Update transaction status to 'ongoing'
            session()->flash('message', 'Transaction successfully started!');
        }
    }


    /**
     * Marks the selected transaction as 'done'
     */
    public function markAsDone($id)
    {
        $transaction = Transaction::with(['transactionUser', 'invoice', 'properties.category', 'activities'])->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        $invoice = $transaction->invoice;

        if (!$invoice || $invoice->invoice_status !== 'completed') {
            $this->cannotMarkAsDoneModal = true;
            return;
        }

        // Update transaction status
        $transaction->update(['transaction_status' => 'done']);
        session()->flash('message', 'Transaction successfully confirmed!');

        $user = $transaction->transactionUser;
        $properties = $transaction->properties;
        $activities = $transaction->activities;

        if (!$user) {
            logger()->error('User not found for transaction ID ' . $id);
            session()->flash('error', 'Confirmation email could not be sent due to missing user data.');
            return;
        }

        // Prepare data for email
        $reservationData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->id,
            'invoice_number' => $invoice->invoice_number,
            'check_in' => $transaction->start_datetime,
            'check_out' => $transaction->end_datetime,
            'total_amount' => $invoice->amount_paid,
            'deposit' => $transaction->deposit_paid,
            'amount_paid' => $invoice->amount_paid,
            'balance_due' =>  $invoice->balance_due,
            'properties' => $properties,
            'activities' => $activities,
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



    // -------------------------------------- SORTING -------------------------------------- //

    /**
     * Changes the sorting column and direction when a user selects a different column
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC"; // Toggle sorting direction
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC"; // Default sorting direction when changing columns
    }
}
