<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Invoice;

class ServiceTransactionService
{

    /**
     * Get all activities for a given transaction,
     * including pivot data (quantity, amount, etc.).
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Support\Collection
     */

    public function saveServices(array $cart, Transaction $transaction, Invoice $invoice)
    {
        DB::transaction(function () use ($cart, $transaction, $invoice) {
            foreach ($cart as $item) {
                if ($item['type'] === 'service') {
                    DB::table('transaction_services')->insert([
                        'transaction_id' => $transaction->id,
                        'service_id' => $item['service_id'],
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount'],
                        'payment_status' => $item['payment_status'] ?? 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $transaction->refresh();
            $transaction->loadMissing(['activities', 'properties', 'services']);

            $invoice->update([
                'requested_remaining_balance' => false,
            ]);

            if (
                $invoice->balance_due > 0 &&
                $invoice->invoice_status === 'completed'
            ) {
                $invoice->invoice_status = 'pending';
                $invoice->save();
            }
        });
    }

    public function deleteService($pivotId, Transaction $transaction, Invoice $invoice)
    {
        DB::table('transaction_services')->where('id', $pivotId)->delete();

        $transaction->refresh();
        $invoice->refresh();
    }

    public function updateServiceQuantity($pivotId, $newQuantity, Transaction $transaction)
    {
        DB::table('transaction_services')
            ->where('id', $pivotId)
            ->update([
                'quantity' => $newQuantity,
                'updated_at' => now(),
            ]);

        $transaction->refresh();
    }
}
