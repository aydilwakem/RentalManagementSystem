<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Activity;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


#[Layout('layouts.app')]
class AddTransaction extends Component
{
    public $transaction;
    public $activities;
    public $cart = []; // Store newly added activities
    public $quantity = [];
    public $activity_datetime = [];
    public $activityAmount = [];
    public $status = [];
    public $total_pax;

    public $availableActivities;


    public function render()
    {
        return view(view: 'livewire.admin.reservations.add-transaction');
    }

    public function mount(Transaction $transaction)
    {
        // Method to load related Payment data
        $this->loadTransactionData($transaction);
        $this->availableActivities = Activity::all();
        $this->total_pax = $transaction->pax;
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

    public function loadTransactionData(Transaction $transaction)
    {
        // Eager-load relationships only if not already loaded
        $transaction->loadMissing([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
            'properties',
            'activities',
        ]);

        if (!$transaction->exists) {
            abort(404, 'Transaction not found.');
        }

        $this->transaction = $transaction;
        $this->activities = $transaction->activities;

        // Check if invoice or transaction is missing
        if (!$this->activities) {
            abort(404, 'Activities are not found for this transaction');
        }
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

        // Add the activity to the cart if it isn't already present
        $this->cart[] = [
            'type' => 'activity',  // Define the type as 'activity'
            'activity_id' => $activity->id,  // Set the activity ID from the activity object
            'activity_name' => $activity->name,  // Set the activity name
            'quantity' => $quantity,  // Set the quantity from the input or default to 1
            'amount' => $activityAmount,  // Set the calculated amount for the activity
            'status' => $activitystatus,  // Set the status of the activity
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
        $current = $this->quantity[$activityId] ?? 1;
        $this->quantity[$activityId] = $current + 1;
    }

    public function decrementActivity($activityId)
    {
        $current = $this->quantity[$activityId] ?? 1;
        if ($current > 1) {
            $this->quantity[$activityId] = $current - 1;
        }
    }


    public function register()
    {
        $this->resetErrorBag();

        DB::transaction(function () {
            foreach ($this->cart as $item) {
                if ($item['type'] === 'activity') {
                    $activity = $this->transaction->activities()->where('activity_id', $item['activity_id'])->first();

                    if ($activity) {
                        // Existing: increment quantity and amount
                        $current = $activity->pivot;
                        $newQuantity = $current->quantity + $item['quantity'];
                        $newAmount = $current->amount + $item['amount'];

                        $this->transaction->activities()->updateExistingPivot($item['activity_id'], [
                            'quantity' => $newQuantity,
                            'amount' => $newAmount,
                        ]);
                    } else {
                        // New activity: attach it
                        $this->transaction->activities()->attach($item['activity_id'], [
                            'quantity' => $item['quantity'],
                            'amount' => $item['amount'],
                        ]);
                    }

                    // Update invoice subtotal and balance_due
                    $this->transaction->invoice->increment('sub_total', $item['amount']);
                    $this->transaction->invoice->increment('balance_due', $item['amount']);
                }
            }

            // ✅ Sync the invoice status based on the new balance
            $invoice = $this->transaction->invoice->fresh(); // Get the updated invoice
            if ($invoice->balance_due > 0 && $invoice->invoice_status === 'completed') {
                $invoice->invoice_status = 'pending';
                $invoice->save();
            }
        });

        $this->cart = [];
    }
}

// Debbuger

/**
 *     dd($this->activities->pluck('name'));
 */
