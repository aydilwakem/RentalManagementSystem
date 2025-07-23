<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\GuestDetail;

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
            'birthdate' => $data['birthdate'],
            'residency' => $data['residency'],
            'country_of_origin' => $data['country_of_origin'],
        ]);
    }
}
