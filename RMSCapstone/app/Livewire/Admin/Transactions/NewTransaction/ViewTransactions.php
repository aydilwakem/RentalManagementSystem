<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewTransactions extends Component
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
    public $selectedTransaction; // Stores the selected transaction for confirmation
    public $confirmItemDelete = false; // Flag to track delete confirmation modal
    public $confirmItemReceipt = false; // Flag to track receipt confirmation modal

    public function mount()
    {
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
        }
    }

    /**
     * Triggers delete confirmation modal for a specific transaction
     */
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    /**
     * Deletes the selected transaction from the database
     */
    public function deleteTransaction()
    {
        $transaction = Transaction::find($this->confirmItemDelete);
        if ($transaction) {
            $transaction->delete(); // Permanently deletes the transaction
            $this->confirmItemDelete = false;
            session()->flash('message', 'Transaction successfully deleted!'); // Success message
        }
    }

    /**
     * Triggers receipt confirmation modal and loads transaction details
     */
    public function confirmReceipt($id)
    {
        $this->selectedTransaction = Transaction::find($id); // Fetch transaction data
        $this->confirmItemReceipt = true; // Show receipt confirmation modal
    }

    /**
     * Marks the transaction as paid and updates the database
     */
    public function confirmPaymentReceipt()
    {
        $transaction = Transaction::find($this->selectedTransaction->id);

        if ($transaction) {
            $transaction->update(['isPaid' => true]); // Updates only the 'isPaid' field
            session()->flash('message', 'Payment Receipt confirmed successfully.'); // Success message
        } else {
            session()->flash('error', 'Payment Receipt confirmation failed.'); // Error message if transaction not found
        }

        $this->confirmItemReceipt = false; // Close confirmation modal
    }

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

    /**
     * Marks a transaction as reserved
     */
    public function confirmReservation($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            $transaction->update(['isReserved' => true]); // Updates only the 'isReserved' field
            session()->flash('message', 'Reservation confirmed successfully.'); // Success message
        } else {
            session()->flash('error', 'Reservation not found.'); // Error message if transaction not found
        }
    }

    /**
     * Renders the Livewire component view and fetches transactions based on filters
     */
    public function render()
    {
        $transactions = Transaction::query()
            ->where('isConfirmed', false) // Only fetch unconfirmed transactions
            ->where('isReserved', false) // Only fetch unreserved transactions
            ->where('first_name', 'like', '%' . $this->search . '%') // Apply search filter
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter); // Apply status filter if set
            })
            ->orderBy($this->sortBy, $this->sortDir) // Apply sorting
            ->paginate($this->perPage); // Paginate results

        return view('livewire.admin.transactions.new-transaction.view-transactions', [
            'transactions' => $transactions, // Pass transactions data to the view
        ]);
    }
}
