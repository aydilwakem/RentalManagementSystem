public function updateransaction()
{
try {
// Validate form input
$this->validate([
'room_id' => 'required|exists:prd_rooms,id',
'check_in_time' => 'required',
'check_out_time' => 'required',
'check_in_date' => 'required|date',
'check_out_date' => 'required|date|after_or_equal:check_in_date',
'total_adults' => 'required|integer|min:1',
'total_kids' => 'nullable|integer|min:0',
'pax' => 'required|integer|min:1',
'total_amount' => 'required|numeric|min:0',
'first_name' => 'required|string',
'middle_name' => 'nullable|string',
'last_name' => 'required|string',
'suffix' => 'nullable|string',
'email' => 'required|email',
'contact_number' => 'required|string',
'house_number' => 'nullable|string',
'street' => 'nullable|string',
'barangay' => 'nullable|string',
'city_municipality' => 'required|string',
'province' => 'required|string',
'region' => 'required|string',
'postal_code' => 'required|string',
'country' => 'required|string',
'pets' => 'nullable|string',
'terms' => 'accepted',
'payment_method_id' => 'required|integer|exists:pm_payment_methods,id',
'payment_screenshot' => 'nullable|image|max:1024',
'payment_reference_number' => 'nullable|string',
'isPaid' => 'boolean',
'isReserved' => 'boolean',
'isConfirmed' => 'boolean',
]);
} catch (\Illuminate\Validation\ValidationException $e) {
$this->confirmEditItem = false;
throw $e;
}

// Ensure image upload is complete before storing
$imagePath = null;
if ($this->payment_screenshot) {
if (!$this->payment_screenshot->isValid()) {
session()->flash('error', 'Image upload failed. Please try again.');
return;
}
$imagePath = $this->payment_screenshot->store('transactions', 'public'); // Saves in storage/app/public/transactions
}

// Create Transaction and retrieve its ID
$transaction = Transaction::update([
'room_id' => $this->room_id,
'check_in_time' => $this->check_in_time,
'check_out_time' => $this->check_out_time,
'check_in_date' => $this->check_in_date,
'check_out_date' => $this->check_out_date,
'total_adults' => $this->total_adults,
'total_kids' => $this->total_kids,
'pax' => $this->pax,
'activity_id' => $this->activity_id,
'total_amount' => $this->total_amount,
'first_name' => $this->first_name,
'middle_name' => $this->middle_name,
'last_name' => $this->last_name,
'suffix' => $this->suffix,
'email' => $this->email,
'contact_number' => $this->contact_number,
'house_number' => $this->house_number,
'street' => $this->street,
'barangay' => $this->barangay,
'city_municipality' => $this->city_municipality,
'province' => $this->province,
'region' => $this->region,
'postal_code' => $this->postal_code,
'country' => $this->country,
'pets' => $this->pets,
'terms' => $this->terms,
'payment_method_id' => $this->payment_method_id,
'payment_screenshot' => $imagePath,
'payment_reference_number' => $this->payment_reference_number,
'isPaid' => false, // Initially set to false
'isReserved' => false, // Initially set to false
'isConfirmed' => false, // Initially set to false
]);

}

// Reset form fields
$this->reset();

// Flash message for success
session()->flash('message', 'Reservation successfully created!');

// Redirect back to reservations list
return redirect()->route('admin.view-new-transactions');
}