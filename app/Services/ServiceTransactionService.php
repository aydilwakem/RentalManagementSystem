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

                // Save services
                if ($item['type'] === 'service') {

                    // Special handling for "Extra Hour" by name
                    $service = \App\Models\Service::find($item['service_id']);
                    if (strtolower($service->name) === 'extra hour' && !empty($item['properties_with_extra_hour'])) {
                        $propertyIds = array_keys($item['properties_with_extra_hour']); // get actual IDs
                        foreach ($propertyIds as $propertyId) {
                            $property = $transaction->properties->firstWhere('id', $propertyId);
                            if (!$property) continue;

                            $amount = $property->extra_charge_per_hour * $item['quantity'];

                            DB::table('transaction_services')->insert([
                                'transaction_id' => $transaction->id,
                                'service_id'     => $item['service_id'],
                                'property_id'    => $property->id,
                                'quantity'       => $item['quantity'],
                                'amount'         => $amount,
                                'payment_status' => $item['payment_status'] ?? 'pending',
                                'status'         => $item['status'] ?? 'pending',
                                'created_at'     => now(),
                                'updated_at'     => now(),
                            ]);
                        }
                    } else {
                        // Normal service (not tied to properties)
                        DB::table('transaction_services')->insert([
                            'transaction_id' => $transaction->id,
                            'service_id'     => $item['service_id'],
                            'quantity'       => $item['quantity'],
                            'amount'         => $item['amount'],
                            'payment_status' => $item['payment_status'] ?? 'pending',
                            'status'         => $item['status'] ?? 'pending',
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);
                    }
                }
            }

            // Refresh relations and update invoice
            $transaction->refresh();
            $transaction->loadMissing(['activities', 'properties', 'services']);

            $invoice->update([
                'requested_remaining_balance' => false,
            ]);

            if ($invoice->balance_due > 0 && $invoice->invoice_status === 'completed') {
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
