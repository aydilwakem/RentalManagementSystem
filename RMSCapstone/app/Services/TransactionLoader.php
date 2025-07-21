<?php

namespace App\Services;

use App\Models\Transaction;

/**
 * Service class to load a transaction and all its related data.
 */
class TransactionLoader
{
    /**
     * Loads the transaction along with its necessary relationships,
     * and returns an array of data for use in Livewire components.
     *
     * @param Transaction $transaction
     * @return array
     */
    public function load(Transaction $transaction): array
    {
        // Load only missing relationships to optimize performance
        $transaction->loadMissing([
            'invoice.payments',     // Invoice and associated payments
            'transactionUser',      // User who made the transaction
            'guestDetails',         // Guest info (if applicable)
            'properties',           // Rooms or properties included in transaction
            'activities',
            'services',      // Additional services or activities booked
        ]);

        // Abort with 404 if invoice is missing
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Return all necessary data as an associative array
        return [
            'transaction' => $transaction,
            'transactionUser' => $transaction->transactionUser,
            'invoice' => $transaction->invoice,
            'guestDetails' => $transaction->guestDetails,
            'activities' => $transaction->activities,
            'properties' => $transaction->properties,
            'services' => $transaction->services,
            'payments' => $transaction->invoice->payments ?? collect(), // fallback to empty collection
            'totalRooms' => $transaction->total_rooms,   // Uses accessor
            'totalAddons' => $transaction->total_addons, // Uses accessor
        ];
    }
}
