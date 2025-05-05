<?php

namespace App\Livewire\Admin\Reservations;

use App\Mail\ConfirmationEmail;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\Property;
use App\Models\ReservationType;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;


class ReservationList extends Component
{

    use WithPagination; // Enables pagination for Livewire component

    // Properties that can be modified via URL parameters
    #[Url(history: true)]
    public $search = ''; // Search term for filtering transactions

    #[Url()]
    public $perPage = 10; // Number of transactions displayed per page

    #[Url(history: true)]
    public $sortBy = 'created_at'; // Column used for sorting transactions

    #[Url(history: true)]
    public $sortDir = 'ASC'; // Sorting direction (ascending/descending)

    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 2;




    // ------------------------------- FLAGS -------------------------------- //
    public $confirmingAction = false;
    public $actionTitle = '';
    public $actionMessage = '';
    public $actionMethod = '';
    public $actionId;

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
        $transactions = Transaction::with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 2) // Room reservation type
            ->when($this->search !== '', callback: function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.reservations.reservation-list', compact('transactions'));
    }



    // -------------------------------------- CONFIRMATION MODAL -------------------------------------- //

    public function showActionModal($method, $title, $message, $id)
    {
        $this->actionMethod = $method;  // e.g., 'deleteTransaction'
        $this->actionTitle = $title;    // e.g., 'Delete Transaction'
        $this->actionMessage = $message; // e.g., 'Are you sure you want to delete this transaction?'
        $this->actionId = $id;          // Store the ID for the action
        $this->confirmingAction = true;  // Trigger the confirmation modal
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
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'confirmed']); // Update transaction status to 'confirmed'
            session()->flash('message', 'Transaction successfully confirmed!');
        }

        // Send confirmation email
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
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'done']); // Update transaction status to 'done'
            session()->flash('message', 'Transaction marked as done!');
        }

        Log::info('Transaction ID: ' . $id);
        Log::info('Transaction: ', [$transaction]);
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








/**
 * Marks a transaction as reserved
 */
    // public function confirmReservation($id)
    // {
    //     $transaction = Transaction::find($id);

    //     if ($transaction) {
    //         $transaction->update(['isReserved' => true]); // Updates only the 'isReserved' field

    //         //Mail::to($transaction->email)->send(new ConfirmationEmail());

    //         session()->flash('message', 'Reservation confirmed successfully.'); // Success message
    //     } else {
    //         session()->flash('error', 'Reservation not found.'); // Error message if transaction not found
    //     }
    // }


    // public function confirmReservation() {}

    // public function startReservation() {}

    // public function maskAsDone() {}

    // public function markAsNoShow() {}

    // public function cancelReservation() {}

    // public function terminateReservation() {}