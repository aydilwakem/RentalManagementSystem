<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reservation') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!---------------------------- GUEST DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Guest Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700">
                    <div><strong>Guest Name:</strong></div>
                    <div>
                        {{ $transaction->transactionUser->first_name }}
                        {{ $transaction->transactionUser->middle_name }}
                        {{ $transaction->transactionUser->last_name }}
                        {{ $transaction->transactionUser->suffix }}
                    </div>
                    <div><strong>Email:</strong></div>
                    <div>{{ $transaction->transactionUser->email }}</div>
                    <div><strong>Contact Number:</strong></div>
                    <div>{{ $transaction->transactionUser->contact_number }}</div>
                    <div><strong>Company Name:</strong></div>
                    <div>{{ $transaction->transactionUser->company_name ?? 'Not provided' }}</div>
                    <div><strong>Country:</strong></div>
                    <div>{{ $transaction->transactionUser->country ?? 'Not provided' }}</div>
                </div>
            </div>

            <!---------------------------- ADDITIONAL GUESTS DETAILS ---------------------------->

            <!-- Existing Additional Guests -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Additional Guests Details') }}
                </h2>
                @if ($guestDetails->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Full Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Gender</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Residency</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Country of Origin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($guestDetails as $guestDetail)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $guestDetail->first_name }}
                                            {{ $guestDetail->middle_name }}
                                            {{ $guestDetail->last_name }}
                                            {{ $guestDetail->suffix }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $guestDetail->gender ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $guestDetail->residency }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $guestDetail->country_of_origin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic">No additional guests found for this transaction.</p>
                @endif
            </div>

            <!-- Displaying Added Guests -->
            <div class="mt-6">
                @if (count($guests) > 0)
                    <ul class="space-y-2">
                        @foreach ($guests as $guest)
                            <li class="flex justify-between items-center p-2 bg-gray-100 rounded-md">
                                <span>{{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}</span>
                                <div class="space-x-2">
                                    <button wire:click="editGuest({{ $loop->index }})"
                                        class="text-blue-500">Edit</button>
                                    <button wire:click="deleteGuest({{ $loop->index }})"
                                        class="text-red-500">Delete</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Button to open modal -->
            @if (count($guests) < $total_pax - 1)
                <div>
                    <button type="button" wire:click="openGuestModal"
                        class="inline-flex items-center px-3 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-1"></i> Add Guest
                    </button>
                </div>
            @endif



            <!---------------------------- TRANSACTION DETAILS --------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Transaction Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700">
                    <div class="col-span-full">
                        <strong>Transaction Status:</strong>
                        <div class="mt-1">
                            @if ($transaction->transaction_status === 'pending')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Awaiting
                                    Payement</span>
                            @elseif ($transaction->transaction_status === 'reserved')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-blue-100 text-blue-500">Pending
                                    Verification</span>
                            @elseif ($transaction->transaction_status === 'receipt_verified')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-500">Payment
                                    Verified</span>
                            @elseif ($transaction->transaction_status === 'confirmed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                            @elseif ($transaction->transaction_status === 'ongoing')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-600">In
                                    Progress</span>
                            @elseif ($transaction->transaction_status === 'done')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                            @elseif ($transaction->transaction_status === 'no_show')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-pink-100 text-pink-500">No
                                    Show</span>
                            @elseif ($transaction->transaction_status === 'terminated')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">Terminated</span>
                            @elseif ($transaction->transaction_status === 'expired')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-orange-100 text-orange-500">Expired</span>
                            @elseif ($transaction->transaction_status === 'cancelled')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-red-100 text-red-600">Cancelled</span>
                            @else
                                {{ ucfirst($transaction->transaction_status) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong>Transaction Number:</strong>
                        <div>#{{ $transaction->id }}</div>
                    </div>
                    <div>
                        <strong>Reservation Created At:</strong>
                        <div>{{ $transaction->created_at }}</div>
                    </div>
                    <div>
                        <strong>Reservation Source:</strong>
                        <div>{{ $transaction->reservation_source }}</div>
                    </div>
                    <div>
                        <strong>Check-in Date:</strong>
                        <div>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</div>
                    </div>
                    <div>
                        <strong>Check-out Date:</strong>
                        <div>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</div>
                    </div>
                    <div>
                        <strong>Duration of Stay:</strong>
                        <div>{{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)</div>
                    </div>
                    <div>
                        <strong>Total Adults:</strong>
                        <div>{{ $transaction->total_adults }}</div>
                    </div>
                    <div>
                        <strong>Total Kids:</strong>
                        <div>{{ $transaction->total_kids }}</div>
                    </div>
                    <div>
                        <strong>Total Guests:</strong>
                        <div>{{ $transaction->pax }}</div>
                    </div>
                    <div>
                        <strong>Total Amount:</strong>
                        <div>₱{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Deposit:</strong>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Heard From:</strong>
                        <div>{{ $transaction->heard_from }}</div>
                    </div>
                </div>
            </div>

            <!---------------------------- ROOM DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Room Details') }}
                </h2>
                @if ($properties->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Room</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Category</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">No. of Adults
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">No. of Kids</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Stay Duration
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Extra Guests</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Base Rate</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Extra Guest Charge
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Room Total</th>
                                    {{-- <th class="border px-4 py-2 font-medium text-gray-900 text-right">Action</th>
                                    --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($properties as $property)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700">{{ $property->name_number }}</td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $property->category->name }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->adults ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->kids ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->days ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($property->pivot->amount ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold">
                                            ₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>

                                        {{-- <!-- Edit Icon -->
                                    <td class="px-4 py-3 flex items-center justify-center space-x-2">
                                        <i class="fas fa-edit text-blue-600 cursor-pointer"
                                            wire:click="editRoom({{ $property->id }})">
                                        </i>
                                    </td> --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Room Charges: ₱{{ number_format($totalRooms, 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No properties found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- ADD ON SERVICES ------------------------------------>
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Add-on Services/Activities') }}
                </h2>
                @if ($activities->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-left">Activity Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Quantity</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Unit Cost</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Activity Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 text-left">{{ $activity->name }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $activity->pivot->quantity ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($activity->amount, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold">
                                            @if ($activity->pivot)
                                                ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}
                                            @else
                                                ₱N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Activity Charges: ₱{{ number_format($totalAddons, 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No activities found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- INVOICE DETAILS ------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Invoice Details') }}
                </h2>
                @if ($invoice)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700">
                        <!-- Invoice Info -->
                        <div><strong>Invoice Number:</strong></div>
                        <div># {{ $invoice->invoice_number }}</div>

                        <div><strong>Due Date:</strong></div>
                        <div>
                            {{ $invoice->due_date
                                ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y')
                                : 'Not
                                                                                                                                                                                                    yet set' }}
                        </div>

                        <!-- COst Summary -->
                        <div><strong>Grand Total:</strong></div>
                        <div class="font-semibold">₱{{ number_format($invoice->sub_total, 2) }}</div>

                        <div><strong>Deposit:</strong></div>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>

                        <div><strong>Amount Paid:</strong></div>
                        <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>

                        <div><strong>Balance Due:</strong></div>
                        <div>₱{{ number_format($invoice->balance_due, 2) }}</div>

                        <!-- Payment Status -->
                        <div><strong>Invoice Status:</strong></div>
                        <div>
                            <div class="text-yellow-500">{{ ucfirst($invoice->invoice_status) }}</div>
                        </div>

                        <!-- Timeline -->
                        <div><strong>Completed At:</strong></div>
                        <div>
                            {{ $invoice->completed_at
                                ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y')
                                : 'Not
                                                                                                                                                                                                    yet completed' }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No invoice found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- PAYMENT DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Payments') }}
                </h2>
                @if ($payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Payment ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Invoice ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Method</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Amount Paid</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Type</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Reference #</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Upload Date</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Status</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Notes</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Verified At</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->id }}</td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->invoice_id }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->paymentMethod->mode_of_payment_name }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            ₱{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ ucfirst($payment->payment_type) }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->payment_date ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">
                                            <span
                                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->notes ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->verified_at ?? 'To be verified' }}</td>
                                        <td class="border px-4 py-2 space-x-2">
                                            @if ($payment->payment_status === 'pending')
                                                <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                    class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                                    Verify Receipt
                                                </a>
                                            @elseif($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                                <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                    class="inline-block bg-green-500 hover:bg-green-700 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                                    View Receipt
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic">No payments found for this invoice.</p>
                @endif
            </div>

            <div class="flex justify-between items-center mt-4">
                <!-- Cancel Button -->
                <x-button wire:click="cancelEdit"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>

                <!-- Save Changes Button -->
                <x-button wire:click="saveChanges({{ $transaction->id }})">
                    Save Changes
                </x-button>
            </div>

            <!-- Add Guest Modal -->
            @if ($showGuestModal)
                <div id="guestModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                        <h2 class="text-lg font-semibold mb-4">Enter Guest Details</h2>

                        <!-- Guest Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700">First Name</label>
                                <input type="text" wire:model="guest_first_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('guest_first_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Middle Name</label>
                                <input type="text" wire:model="guest_middle_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('guest_middle_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Last Name</label>
                                <input type="text" wire:model="guest_last_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('guest_last_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Suffix</label>
                                <input type="text" wire:model="guest_suffix"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('guest_suffix')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guest Type -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Guest Type</label>
                            <select wire:model="guest_type_id"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Guest Type</option>
                                @foreach ($guest_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('guest_type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Gender</label>
                            <select wire:model="guest_gender"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            @error('guest_gender')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Residency -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Residency</label>
                            <select wire:model="guest_residency"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Residency</option>
                                <option value="local">Local</option>
                                <option value="foreigner">Foreigner</option>
                            </select>
                            @error('guest_residency')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country of Origin -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Country of Origin</label>
                            <input type="text" wire:model="guest_country_of_origin"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                            @error('guest_country_of_origin')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Actions -->
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" wire:click="closeGuestModal"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Cancel
                            </button>
                            <button type="button" wire:click="addMultipleGuests"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white transition duration-150 ease-in-out">
                                Add Guest
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Modal -->
            @if ($showEditModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                        <h2 class="text-lg font-semibold mb-4">Edit Guest Details</h2>

                        <!-- Guest Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700">First Name</label>
                                <input type="text" wire:model.defer="editingGuest.guest_first_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('editingGuest.guest_first_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Middle Name</label>
                                <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('editingGuest.guest_middle_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Last Name</label>
                                <input type="text" wire:model.defer="editingGuest.guest_last_name"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('editingGuest.guest_last_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Suffix</label>
                                <input type="text" wire:model.defer="editingGuest.guest_suffix"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('editingGuest.guest_suffix')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guest Type -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Guest Type</label>
                            <select wire:model.defer="editingGuest.guest_type_id"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Guest Type</option>
                                @foreach ($guest_types as $type)
                                    <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                @endforeach
                            </select>
                            @error('editingGuest.guest_type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Gender</label>
                            <select wire:model.defer="editingGuest.guest_gender"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            @error('editingGuest.guest_gender')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Residency -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Residency</label>
                            <select wire:model.defer="editingGuest.guest_residency"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="">Select Residency</option>
                                <option value="local">Local</option>
                                <option value="foreigner">Foreigner</option>
                            </select>
                            @error('editingGuest.guest_residency')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country of Origin -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700">Country of Origin</label>
                            <input type="text" wire:model.defer="editingGuest.guest_country_of_origin"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                            @error('editingGuest.guest_country_of_origin')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2 mt-6">
                            <button wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Cancel
                            </button>
                            <button wire:click="updateGuest"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>




{{-- <!-- Edit Room Modal -->
@if ($showRoomModal && isset($editingRooms[$selectedPropertyId]))
<div id="guestRoomModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-bold mb-4">Edit Property Details</h2>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>Total Adults</label>
                <select wire:model="editingRooms.{{ $selectedPropertyId }}.adults" class="w-full border p-1">
                    <option value="">Select</option>
                    @for ($i = 0; $i <= ($maxAdultsPerRoom[$selectedPropertyId] ?? 0); $i++) <option value="{{ $i }}">{{
                        $i }}</option>
                        @endfor
                </select>
            </div>

            <div>
                <label>Total Kids</label>
                <select wire:model="editingRooms.{{ $selectedPropertyId }}.kids" class="w-full border p-1">
                    <option value="">Select</option>
                    @for ($i = 0; $i <= ($maxKidsPerRoom[$selectedPropertyId] ?? 0); $i++) <option value="{{ $i }}">{{
                        $i }}</option>
                        @endfor
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-6 col-span-2">
                <button wire:click="$set('showRoomModal', false)"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="button" wire:click="updateRoom"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white transition duration-150 ease-in-out">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endif --}}
