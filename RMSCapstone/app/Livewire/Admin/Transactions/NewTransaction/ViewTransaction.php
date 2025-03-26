<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;

#[Layout('layouts.app')]
class ViewTransaction extends Component
{
    public $transaction; // Holds the transaction details
    public $resident; // Holds the associated residents
    public $confirmItemDelete = false; // Stores the ID of the transaction to be deleted

    /**
     * Initialize the component with the selected transaction.
     *
     * @param int $transaction The ID of the transaction passed from the route.
     */
    public function mount($transaction)
    {
        // Retrieve the transaction record based on the given ID.
        $this->transaction = Transaction::find($transaction);

        // If the transaction is not found, return a 404 error.
        if (!$this->transaction) {
            abort(404, 'Transaction not found');
        }

        // Retrieve the residents associated with the transaction.
        $this->resident = $this->transaction->residents;
    }

    /**
     * Set the ID of the transaction to be deleted.
     *
     * This function is triggered when the delete button is clicked.
     *
     * @param int $id The ID of the transaction to be deleted.
     */
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    /**
     * Delete the selected transaction from the database.
     *
     * This function checks if the transaction exists and deletes it permanently.
     * After deletion, it resets the confirmation variable and displays a success message.
     */
    public function deleteTransaction()
    {
        $transaction = Transaction::find($this->confirmItemDelete);

        if ($transaction) {
            $transaction->delete(); // Permanently deletes the transaction
            $this->confirmItemDelete = false; // Reset confirmation variable
            session()->flash('message', 'Transaction successfully deleted!'); // Display success message
        }
    }

    /**
     * Render the component view and pass the transaction data.
     *
     * @return \Illuminate\View\View The view for displaying the transaction details.
     */
    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.view-transaction', [
            'transactions' => $this->transaction, // Pass transaction data to the view
            'residents' => $this->resident, // Pass associated residents to the view
        ]);
    }
}
