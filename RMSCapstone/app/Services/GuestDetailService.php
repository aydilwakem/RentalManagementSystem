<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\GuestDetail;
use App\Models\TransactionProperty;

class GuestDetailService
{

    public function deleteGuest($guestId, Transaction $transaction)
    {
        // Delete using Eloquent
        GuestDetail::findOrFail($guestId)->delete();

        // Refresh the guestDetails relationship
        $transaction->load('guestDetails');
    }

    public function saveGuest(array $data)
    {
        GuestDetail::create([
            'transaction_id' => $data['transaction_id'],
            'transaction_property_id' => $data['transaction_property_id'],
            'guest_type_id' => $data['guest_type_id'] ?? null,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'],
            'gender' => $data['gender'],
            'residency' => $data['residency'],
            'country_of_origin' => $data['country_of_origin'],
            'birthdate' => $data['birthdate'],
        ]);
    }


    public function saveExtraGuest(array $data)
    {
        // Save the guest detail
        GuestDetail::create([
            'transaction_id' => $data['transaction_id'],
            'transaction_property_id' => $data['transaction_property_id'],
            'guest_type_id' => $data['guest_type_id'] ?? null,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'],
            'gender' => $data['gender'],
            'residency' => $data['residency'],
            'country_of_origin' => $data['country_of_origin'],
        ]);

        // Fetch the related transaction property
        $transactionProperty = TransactionProperty::find($data['transaction_property_id']);

        if (!$transactionProperty) {
            return;
        }

        // Increment the correct count
        if ($data['guest_type_id'] == 1) {
            // 1 = Adult
            $transactionProperty->increment('adults');
        } elseif ($data['guest_type_id'] == 2) {
            // 2 = Kid
            $transactionProperty->increment('kids');
        }
    }
}
