<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Invoice;

class PropertyTransactionService
{

    /**
     * Get all activities for a given transaction,
     * including pivot data (quantity, amount, etc.).
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Support\Collection
     */

    public function updateRoomQuantity($pivotId, $newAdultQuantity, $newKidQuantity, Transaction $transaction)
    {
        DB::table('transaction_properties')
            ->where('id', $pivotId)
            ->update([
                'adults' => $newAdultQuantity,
                'kids' => $newKidQuantity,
                'updated_at' => now(),
            ]);

        $transaction->refresh();
    }
}
