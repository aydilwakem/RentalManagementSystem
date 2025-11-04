<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Reservation') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Reservations', 'url' => route('admin.reservations-list')],
            [
                'label' => 'View Reservation',
                'url' => route('admin.view-reservation', ['transaction' => $this->transaction->id]),
            ],
        ]" />
    </x-slot>
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 space-y-6">


            <div class="flex space-x-2">
                <!---------------------------- EXPORT DETAILS ---------------------------------------->
                <x-button wire:click="exportReservationDetails">
                    <!-- Spinner -->
                    <span wire:loading wire:target="exportReservationDetails" class="mr-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                            </path>
                        </svg>
                    </span>

                    <i class="fas fa-file mr-2" wire:loading.remove wire:target="exportReservationDetails"></i>

                    <!-- Button Text -->
                    <span wire:loading.remove wire:target="exportReservationDetails">
                        Export PDF
                    </span>

                </x-button>

                <!------------------------- GENERATE RECEIPT ---------------------------------->
                @if ($transaction->transaction_status == 'done')
                    <div>
                        @if (is_null($transaction->invoice->receipt))
                            <!-- Show this if receipt does NOT exist -->
                            <x-button wire:click="GenerateReceipt" wire:loading.attr="disabled"
                                wire:target="GenerateReceipt">

                                <!-- Show spinner and text while loading -->
                                <span wire:loading wire:target="GenerateReceipt" class=" items-center gap-2">
                                    <span>Generating...</span>
                                </span>

                                <!-- Show default text when not loading -->
                                <span wire:loading.remove wire:target="GenerateReceipt">
                                    <i class="fas fa-receipt"></i>
                                    Generate Acknowledgement Receipt
                                </span>
                            </x-button>
                        @else
                            <!-- Show this if receipt already exists -->
                            <x-button wire:click="ShowReceipt" icon="fas fa-eye">
                                View Receipt
                            </x-button>
                        @endif
                    </div>
                @endif
            </div>


            <!---------------------------- GUEST DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Guest Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">
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
                    <div><strong>Facebook Link:</strong></div>
                    <div>{{ $transaction->transactionUser->facebook_link ?? 'Not provided' }}</div>

                </div>
            </div>
            <!--------------- -------- END OF GUEST DETAILS ------------------------------------->



            <!---------------------- ADDITIONAL GUESTS DETAILS ---------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <div class="text-center justify-between flex mb-4">
                    <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-300">
                        {{ __('Additional Guests Details') }}
                    </h2>
                    <!-- Add Guest -->

                    @if ($transaction->transaction_status != 'done')
                        <x-button wire:click="openModal('guest')" icon="fas fa-user-plus">
                            Add Guest
                        </x-button>
                    @else
                        <x-button disabled class="bg-gray-400 cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i>
                            Add Guest
                        </x-button>
                    @endif

                </div>
                @if ($guestDetails->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500 text-left">
                                        Full Name</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Type</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Gender</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Citizenship</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Country</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Assigned Room</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($guestDetails as $guestDetail)
                                    <tr>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 text-left">
                                            {{ $guestDetail->first_name }}
                                            {{ $guestDetail->middle_name }}
                                            {{ $guestDetail->last_name }}
                                            {{ $guestDetail->suffix }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->guestType->name) ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->gender) ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->residency) }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->country_of_origin) }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst(optional(optional($guestDetail->transactionProperty)->property)->name_number) }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 space-x-3">

                                            <button wire:click="editGuest({{ $guestDetail->id }})"
                                                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button wire:click="deleteGuest({{ $guestDetail->id }})"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic dark:text-gray-200 col-span-7 text-center">No additional guests found
                        for this transaction.
                    </p>
                @endif
            </div>
            <!------------------ END OF ADDITIONAL GUESTS DETAILS ------------------------------->



            <!---------------------------- TRANSACTION DETAILS --------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Transaction Details') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">

                    <div class="col-span-full flex flex-wrap items-center gap-4">
                        <!-- Transaction Status -->
                        <div class="flex flex-col">
                            <strong>Transaction Status:</strong>
                            <div class="mt-1">
                                @if ($transaction->transaction_status === 'pending')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting
                                        Payment</span>
                                @elseif ($transaction->transaction_status === 'reserved')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending
                                        Verification</span>
                                @elseif ($transaction->transaction_status === 'receipt_verified')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment
                                        Verified</span>
                                @elseif ($transaction->transaction_status === 'confirmed')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                                @elseif ($transaction->transaction_status === 'ongoing')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going</span>
                                @elseif ($transaction->transaction_status === 'done')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                                @elseif ($transaction->transaction_status === 'no_show')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500">No
                                        Show</span>
                                @elseif ($transaction->transaction_status === 'terminated')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated</span>
                                @elseif ($transaction->transaction_status === 'expired')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500">Expired</span>
                                @elseif ($transaction->transaction_status === 'cancelled')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Cancelled</span>
                                @else
                                    {{ ucfirst($transaction->transaction_status) }}
                                @endif

                                <!-- Rebooked Indicator -->
                                @if ($transaction->is_rebooked)
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-600 border border-purple-200">
                                        <i class="fas fa-calendar-repeat mr-1"></i>
                                        Rebooked Reservation
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <strong>Transaction ID:</strong>
                        <div>{{ $transaction->transaction_number }}</div>
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
                        <strong>Reservation Created At:</strong>
                        <div>{{ $transaction->created_at->format('F j, Y') }} at
                            {{ $transaction->created_at->format('g:i A') }}
                        </div>
                    </div>
                    <div>
                        <strong>Heard From:</strong>
                        <div>{{ $transaction->heard_from }}</div>
                    </div>

                    <div>
                        <strong>Duration of Stay:</strong>
                        <div>{{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)</div>
                    </div>
                    <div>
                        <strong>Total Guests:</strong>
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
                        <strong>Total Pets:</strong>
                        <div>{{ $transaction->guestPets->sum('pet_count') }}</div>
                    </div>

                    <div>
                        <strong>Subtotal:</strong>
                        <div>₱{{ number_format($transaction->sub_total, 2) }}</div>
                    </div>

                    <div>
                        <strong>Promo Applied:</strong>
                        <div>
                            @if ($transaction->promoCode)
                                <div>
                                    {{ $transaction->promoCode->code }}

                                    @if ($transaction->promoCode->discount_type === 'percentage')
                                        ({{ number_format($transaction->promoCode->discount_value, 0) }}% off)
                                    @else
                                        (₱{{ number_format($transaction->promoCode->discount_value, 2) }} off)
                                    @endif
                                </div>
                            @else
                                <div class="text-gray-500 italic">No promo code used</div>
                            @endif

                        </div>
                    </div>

                    <div>
                        <strong>Total Amount:</strong>
                        <div>₱{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Required Deposit:</strong>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>
                    </div>

                    <div>
                        <strong>Reservation Source:</strong>
                        <div>{{ $transaction->reservation_source }}</div>
                    </div>

                    <div>
                        <strong>Free Breakfast Inclusion:</strong>
                        <div>Free breakfast for {{ $transaction->pax }} pax</div>
                    </div>

                    <div>
                        <strong>Requests:</strong>

                        @if (!empty($transaction->requests))
                            <div class="mt-2">
                                <span
                                    class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg shadow-sm">
                                    {{ Str::limit($transaction->requests, 50) }}
                                </span>

                                <x-button wire:click="openModal('requests')" icon="fas fa-comment-dots">
                                    View & Reply
                                </x-button>

                            </div>
                        @else
                            <div class="text-gray-500 italic">
                                No requests.
                            </div>
                        @endif
                    </div>

                    <div>
                        <strong>Vouchers:</strong>

                        @if ($transaction->vouchers->count())
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @foreach ($transaction->vouchers as $voucher)
                                    <div
                                        class="flex items-center bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg shadow-sm">
                                        <span>
                                            {{ ucfirst(strtolower($voucher->voucher_type)) }}:
                                            ₱{{ number_format($voucher->voucher_amount, 2) }}
                                        </span>
                                        <button wire:click="deleteVoucher({{ $voucher->id }})"
                                            class="text-red-500 hover:text-red-700 ml-2">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-500 italic">No vouchers.</div>
                        @endif


                        @if ($transaction->transaction_status != 'done')
                            <button type="button" wire:click="openModal('voucher')"
                                class="mt-2 text-sm text-green-600 hover:underline font-medium">
                                <i class="fa-solid fa-circle-plus"></i> Add Voucher
                            </button>
                        @else
                            <div class="mt-2 text-sm text-gray-400 italic">
                                <i class="fa-solid fa-lock mr-1"></i> Vouchers disabled for completed transactions
                            </div>
                        @endif


                    </div>

                    @if ($transaction->transaction_status === 'confirmed')
                        <div class="mt-2">
                            <x-button
                                href="{{ route('admin.rebook-reservation', ['transaction' => $transaction->id]) }}"
                                icon="fas fa-calendar-plus">
                                Rebook Reservation
                            </x-button>
                        </div>
                    @endif


                </div>


            </div>
            <!------------------------- END OF TRANSACTION DETAILS ----------------------------->



            <!---------------------------- ROOM DETAILS ---------------------------------------->
            <div
                class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Room Details') }}
                </h2>
                @if ($properties->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Room</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Category</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Total Guests</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Ideal Guests</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Extra Guests</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Extra Guest Charge</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Stay Duration
                                    </th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Room Rate</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Room Total</th>
                                    {{-- <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">
                                        Action</th> --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($properties as $property)
                                    <tr>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($property->name_number) }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ optional($property->category)->name ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->adults ?? 'N/A' }} Adult(s)
                                            @if ($property->pivot->kids)
                                                , {{ $property->pivot->kids }} Kid(s)
                                            @endif

                                            @if ($property->pivot->non_chargeable_guests)
                                                @if ($property->pivot->kids)
                                                    ,
                                                @endif
                                                {{ $property->pivot->non_chargeable_guests }} Infant(s)
                                            @endif
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->ideal_guest ?? 0 }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->extra_guest ?? 'N/A' }}
                                        </td>
                                        {{-- <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td> --}}
                                        @if (($property->pivot->extra_guest ?? 0) > 0)
                                            <td
                                                class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                                ₱{{ number_format($property->pivot->extra_charge, 2) }}
                                                {{-- ₱{{ number_format($totalExtraGuestCharge, 2) }} --}}
                                            </td>
                                        @else
                                            <td
                                                class="border px-4 py-2 text-gray-700 text-center dark:text-gray-400 dark:border-gray-500">
                                                ₱0.00
                                            </td>
                                        @endif
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->days ?? 'N/A' }} day(s)
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-right dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->amount ?? 0, 2) }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                                        {{-- <td
                                            class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            <button wire:click="editRoom({{ $property->pivot->id }})"
                                                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Room Charges: ₱{{ number_format($this->computeRoomsTotal(), 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No properties found for this transaction.</p>
                @endif
            </div>
            <!------------------------ END OF ROOM DETAILS ------------------------------------->


            <!-------------------------- ADD ON (ACTIVITIES) --------------------------------------->
            <div
                class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Add-on Services/Activities') }}
                </h2>
                @if ($activities->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">
                                        Activity Name</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Scheduled Time</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Quantity</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Unit Cost</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">
                                        Activity Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-left dark:text-gray-200 dark:border-gray-500">
                                            {{ $activity->name }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            @if ($activity->pivot && $activity->pivot->activity_datetime)
                                                {{ \Carbon\Carbon::parse($activity->pivot->activity_datetime)->format('g:i A') }}
                                            @else
                                                No schedule
                                            @endif
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $activity->pivot->quantity ?? 'NA' }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($activity->amount, 2) }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ $activity->pivot && $activity->pivot->quantity !== null
                                                ? number_format($activity->amount * $activity->pivot->quantity, 2)
                                                : 'NA' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Activity Charges: ₱{{ number_format($this->computeActivitiesTotal(), 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No activities found for this transaction.</p>
                @endif
            </div>
            <!---------------------- END OF ACTIVITY DETAILS ----------------------------------->


            <!-------------------------- ADD ON (CHARGES) --------------------------------------->
            <div
                class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Additional Charges') }}
                </h2>
                @if ($services->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">
                                        Charge Name</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">
                                        Charge Type</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Quantity</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Unit Cost</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">
                                        Charge Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($services as $service)
                                    <tr>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-left dark:text-gray-200 dark:border-gray-500">
                                            {{ $service->name }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-left dark:text-gray-200 dark:border-gray-500">
                                            @if ($service->type == 'addon')
                                                Add On
                                            @else
                                                {{ ucfirst($service->type) }}
                                            @endif
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $service->pivot->quantity ?? 'NA' }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($service->amount, 2) }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($service->pivot->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Charges: ₱{{ number_format($this->computeServicesTotal(), 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No additional charges found for this transaction.</p>
                @endif
            </div>
            <!---------------------- END OF SERVICE DETAILS ----------------------------------->

            <!-------------------------- GUEST PET INFO --------------------------------------->
            <div
                class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-2 dark:text-green-300">
                        {{ __('Guest Pet Information') }}
                    </h2>
                    <!-- Add Pet Details -->
                    @if ($transaction->transaction_status != 'done')
                        <x-button wire:click="openModal('pet')" icon="fas fa-paw">
                            Add Pet
                        </x-button>
                    @else
                        <x-button disabled class="bg-gray-400 cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i>
                            Add Pet
                        </x-button>
                    @endif

                </div>
                @if ($guestPets->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">
                                        Pet Breed</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Vaccination Card</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @php
                                    $unitAmount = $this->getPetFeeAmount();
                                @endphp

                                @foreach ($guestPets as $pet)
                                    <tr>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucwords($pet->breed) }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-center text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            @if ($pet->vaccination_card)
                                                <a href="{{ asset('storage/' . $pet->vaccination_card) }}"
                                                    target="_blank" class="text-blue-600 underline">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 text-center font-semibold dark:text-gray-200 dark:border-gray-500 space-x-2">
                                            <button wire:click="editPet({{ $pet->id }})"
                                                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                title="Edit Pet">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button wire:click="deletePet({{ $pet->id }})"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500"
                                                title="Delete Breed">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic text-center col-span-7 py-3">No pet data found for this transaction.
                    </p>
                @endif

            </div>
            <!---------------------- END OF GUEST PET INFO ----------------------------------->


            <!----------------------------- INVOICE -------------------------------------------->
            <div
                class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Invoice Details') }}
                </h2>

                @if ($invoice)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
                        <!-- Invoice Info -->
                        <div>
                            <div><strong>Invoice ID:</strong></div>
                            <div>{{ $invoice->invoice_number }}</div>
                        </div>

                        <div>
                            <div><strong>Due Date:</strong></div>
                            <div>
                                {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set' }}
                            </div>
                        </div>

                        <!-- COst Summary -->
                        <div>
                            <div><strong>Grand Total:</strong></div>
                            <div class="font-semibold">₱{{ number_format($this->invoice->sub_total, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Required Deposit:</strong></div>
                            <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Amount Paid:</strong></div>
                            <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Balance Due:</strong></div>
                            <div>₱{{ number_format($this->invoice->balance_due, 2) }}</div>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <div><strong>Invoice Status:</strong></div>
                            <div>
                                @if ($invoice->invoice_status === 'pending')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                        Pending
                                    </span>
                                @elseif ($invoice->invoice_status === 'complete' || $invoice->invoice_status === 'completed')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                        Completed
                                    </span>
                                @elseif ($invoice->invoice_status === 'failed')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                        Failed
                                    </span>
                                @elseif ($invoice->invoice_status === 'overdue')
                                    <span
                                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-600">
                                        Overdue
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div>
                            <div><strong>Completed At:</strong></div>
                            <div>
                                {{ $invoice->completed_at
                                    ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y')
                                    : 'Not yet completed' }}
                            </div>
                        </div>
                    </div>

                    <hr class="py-2 mt-4">

                    <!-- Actions Dropdown -->
                    <div class="flex justify-between">
                        <!-- Email Balance Request -->
                        <div>
                            @if ($invoice->balance_due > 0 && !$invoice->requested_remaining_balance)
                                <x-button wire:click="requestRemainingBalance" wire:loading.attr="disabled">
                                    <div class="flex items-center justify-center">
                                        <!-- Spinner -->
                                        <span wire:loading class="mr-2" wire:target="requestRemainingBalance">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                </path>
                                            </svg>
                                        </span>

                                        <i class="fas fa-money-bill-wave mr-2" wire:loading.remove
                                            wire:target="requestRemainingBalance"></i>

                                        <span wire:loading.remove wire:target="requestRemainingBalance">
                                            Email Balance Request
                                        </span>
                                    </div>
                                </x-button>
                            @elseif ($invoice->balance_due > 0 && $invoice->requested_remaining_balance)
                                <p class="text-gray-500 italic">Waiting for guest to pay remaining balance...</p>
                            @endif
                        </div>

                        <div class="relative inline-block text-left" x-data="{ open: false, showPromo: false }">
                            <!-- Main button -->
                            <x-button icon="fas fa-chevron-down" @click="open = !open">
                                Actions
                            </x-button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg z-20 origin-top-right">

                                <div class="py-1">
                                    <!-- Add Activity -->
                                    @if ($transaction->transaction_status != 'done')
                                        <button wire:click="openModal('activity')"
                                            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-calendar-plus mr-2 text-green-600"></i>
                                            Add Activity
                                        </button>

                                        <!-- Add Other Charges -->
                                        <button wire:click="openModal('service')"
                                            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                                            Add Other Charges
                                        </button>

                                        <!-- Add PWD/Senior Discount -->
                                        @if (!$this->discountsApplied)
                                            <div class="border-t border-gray-200 my-1"></div>
                                            <button wire:click="openDiscountModal"
                                                class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-percent mr-2 text-green-600"></i>
                                                Add PWD/Senior Discount
                                            </button>
                                        @endif
                                    @else
                                        <!-- Disabled state for completed transactions -->
                                        <div class="px-4 py-2 text-sm text-gray-400 italic">
                                            <i class="fas fa-lock mr-2"></i>
                                            Actions disabled for completed transactions
                                        </div>
                                    @endif

                                    <div class="border-t border-gray-200 my-1"></div>

                                    <!-- Add Promo Code Section -->
                                    @if ($transaction->transaction_status != 'done')
                                        <button @click="showPromo = !showPromo"
                                            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-ticket mr-2 text-green-600"></i>
                                            Apply Promo Code
                                        </button>

                                        <!-- Input Code-->
                                        <div x-show="showPromo" x-transition
                                            class="px-4 py-3 border-t border-gray-100 bg-gray-50 dark:bg-gray-800">

                                            @if ($transaction->promoCode)
                                                <!-- Already applied -->
                                                <div
                                                    class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded dark:bg-green-900 dark:border-green-700">
                                                    <span
                                                        class="text-sm font-semibold text-green-700 dark:text-green-300">
                                                        Applied: {{ $transaction->promoCode->code }}
                                                    </span>
                                                    <button wire:click="removePromoCode"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs"
                                                        title="Remove Promo Code">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <!-- Input -->
                                                <div class="flex space-x-2">
                                                    <input type="text" wire:model="promoCode"
                                                        placeholder="Enter code"
                                                        class="flex-grow px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                    <x-button wire:click="applyPromoCode">
                                                        Apply
                                                    </x-button>
                                                </div>
                                            @endif

                                            <!-- Messages -->
                                            @if ($discountMessage)
                                                <p class="mt-2 text-xs text-green-600 dark:text-green-400">
                                                    <i class="fas fa-check-circle mr-1"></i> {{ $discountMessage }}
                                                </p>
                                            @endif

                                            @if ($errorMessage)
                                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $errorMessage }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Disabled promo code section -->
                                        <div class="px-4 py-2 text-sm text-gray-400 italic">
                                            <i class="fas fa-ticket mr-2"></i>
                                            Promo codes disabled for completed transactions
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class=" mt-4">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        #</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Item & Description</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Qty</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Days</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Unit Cost</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Amount</th>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Timestamp</th>
                                    {{-- <th
                                                class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                                Status</th> --}}
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                        Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white text-gray-700 dark:bg-gray-600 dark:text-gray-200">

                                @php $rowNumber = 1; @endphp

                                @foreach ($allItems as $item)
                                    <tr>
                                        <td class="border px-4 py-2 dark:border-gray-500">{{ $rowNumber++ }}</td>
                                        <td class="border px-4 py-2 dark:border-gray-500">
                                            @if ($item['type'] === 'service' && $item['service_id'] == 10 && isset($item['property_name']))
                                                {{ $item['name'] }} ({{ $item['property_name'] }})
                                            @else
                                                {{ $item['name'] }}
                                            @endif
                                        </td>

                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            {{ $item['days'] ?? 'N/A' }}
                                        </td>

                                        {{-- Unit Cost --}}
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            @if ($item['type'] === 'service' && $item['service_name'] === 'Extra Hour' && isset($item['property_extra_hour_charge']))
                                                ₱{{ number_format($item['property_extra_hour_charge'], 2) }}
                                            @else
                                                ₱{{ number_format($item['amount'], 2) }}
                                            @endif

                                            @if ($item['type'] === 'service')
                                                ({{ $item['unit'] ?? '' }})
                                            @endif
                                        </td>

                                        {{-- Subtotal --}}
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            ₱{{ number_format($item['total'], 2) }}
                                        </td>

                                        {{-- Timestamp --}}
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            <span title="{{ $item['created_at']->format('F j, Y - g:i A') }}">
                                                {{ $item['created_at']->diffForHumans() }}
                                            </span>
                                        </td>
                                        {{-- Status
                                                <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                    <span
                                                        class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                                {{ $item['payment_status'] === 'partial' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                                {{ $item['payment_status'] === 'unpaid' ? 'bg-red-100 text-red-500' : '' }}
                                                                {{ $item['payment_status'] === 'pain' ? 'bg-green-100 text-green-500' : '' }}">
                                                        {{ ucfirst($item['payment_status']) }}
                                                    </span>
                                                </td> --}}
                                        {{-- Activity Actions --}}
                                        <td class="border px-4 py-2 text-center dark:border-gray-500 space-x-3">

                                            @if ($transaction->transaction_status != 'done')
                                                @if ($item['type'] == 'property')
                                                    <button wire:click="editRoom({{ $item['pivot_id'] }})"
                                                        class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Change Room Button -->
                                                    <button wire:click="openChangeRoomModal({{ $item['pivot_id'] }})"
                                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-500"
                                                        title="Change Room">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                @else
                                                    <button
                                                        wire:click="
                                                                    @if ($item['type'] === 'activity') editActivity({{ $item['pivot_id'] }})
                                                                    @elseif ($item['type'] === 'service' && $item['service_name'] === 'Extra Hour')
                                                                        editExtraHour({{ $item['pivot_id'] }})
                                                                    @elseif ($item['type'] === 'service')
                                                                    editService({{ $item['pivot_id'] }}) @endif
                                                                "
                                                        class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button
                                                        wire:click="
                                                                    @if ($item['type'] === 'activity') deleteActivity({{ $item['pivot_id'] }})
                                                                    @elseif($item['type'] === 'service') deleteService({{ $item['pivot_id'] }}) @endif
                                                                "
                                                        class="text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-600"
                                                        title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500"
                                                    title="Actions disabled for completed transactions">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            @endif

                                        </td>
                                    </tr>

                                    @if ($item['type'] === 'property' && $item['extra_guest'] > 0)
                                        <tr class="bg-gray-50 dark:bg-gray-800 text-sm">
                                            <td class="border px-4 py-2 dark:border-gray-500"></td>
                                            <td
                                                class="border px-4 py-2 dark:border-gray-500 text-gray-600 dark:text-gray-300 italic text-center">
                                                Extra Guest(s)
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                {{ $item['extra_guest'] }}
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                {{ $item['days'] }}
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                ₱{{ number_format($item['extra_charge'], 2) }}
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                ₱{{ number_format($item['extra_charge_total'], 2) }}
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                <span title="{{ $item['created_at']->format('F j, Y - g:i A') }}">
                                                    {{ $item['created_at']->diffForHumans() }}
                                                </span>
                                            </td>
                                            {{-- <td class="border px-4 py-2 text-center dark:border-gray-500">
                                                        <span
                                                            class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                                    {{ $item['payment_status'] === 'partial' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                                    {{ $item['payment_status'] === 'unpaid' ? 'bg-red-100 text-red-500' : '' }}
                                                                    {{ $item['payment_status'] === 'pain' ? 'bg-green-100 text-green-500' : '' }}">
                                                            {{ ucfirst($item['payment_status']) }}
                                                        </span>
                                                    </td> --}}
                                            <td colspan="3"
                                                class="border px-4 py-2 text-center dark:border-gray-500"></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>



                    <!-- Subtotal -->
                    <div class="mt-2 mb-1 flex justify-between font-semibold text-base text-gray-700">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($this->computeBaseSubtotal(), 2) }}</span>
                    </div>


                            <!-- Discounts applied -->
                            @if ($invoice->discounts->count() > 0)
                                <div class="mb-1 text-gray-600 text-sm border-t pt-2">
                                    @php
                                        $baseSubtotal = $invoice->base_subtotal;
                                        $pax = $transaction->pax ?: 1; // fallback to 1 to avoid division by zero
                                    @endphp

                                    @foreach ($invoice->discounts->groupBy('discount_type_id') as $discounts)
                                        @php
                                            $type = $discounts->first()->discountType;
                                            $count = $discounts->sum('quantity');
                                            $totalValue = $discounts->sum('discount_value');

                                            // Get PWD/Senior IDs from transaction
                                            $pwdSeniorIds = $transaction->pwd_senior_ids ?? [];
                                            $idsSummary = collect($pwdSeniorIds)->map(function ($item) {
                                                return $item['name'] . ' (' . $item['id'] . ')';
                                            })->implode(', ');
                                        @endphp

                                        @if (in_array($type->name, ['pwd', 'senior']))
                                            <div class="flex flex-col p-3 font-semibold bg-green-50 border border-green-200 rounded-lg dark:bg-green-900 dark:border-green-700 mb-2">
                                                <!-- Discount Header -->
                                                <div class="flex items-center justify-between mb-2">
                                                    <div>
                                                        <span class="font-semibold text-green-700 dark:text-green-300 text-sm">
                                                            PWD/Senior Discount Applied
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <p class="text-green-600 dark:text-green-400 text-sm font-semibold">
                                                            - ₱{{ number_format($totalValue, 2) }}
                                                        </p>
                                                        @if ($transaction->transaction_status != 'done')
                                                        <button wire:click="removeDiscount({{ $invoice->id }}, {{ $type->id }})"
                                                            class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                                            title="Remove Discount">
                                                            <i class="fas fa-circle-xmark"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- PWD/Senior IDs and Names -->
                                                @if (!empty($idsSummary))
                                                    <div class="mt-2 pt-2 border-t border-green-200 dark:border-green-700">
                                                        <div class="flex items-start">
                                                            <span class="text-xs font-medium text-green-700 dark:text-green-300 mr-2 mt-0.5">
                                                                IDs:
                                                            </span>
                                                            <div class="flex-1">
                                                                <p class="text-xs text-green-600 dark:text-green-400 break-words">
                                                                    {{ $idsSummary }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- For other discount types, show the original format -->
                                            <div class="flex justify-between items-center mb-1">
                                                <span>
                                                    - {{ $count }} {{ strtoupper($type->name) }}
                                                    @if ($transaction->promoCode)
                                                        @if ($type->type === 'percent' && $perPersonAmount)
                                                            ({{ $type->rate }}% of Extra charge per guest ÷ {{ $count }} pax)
                                                        @else
                                                            (Fixed)
                                                        @endif
                                                    @else
                                                        @if ($type->type === 'percent' && $perPersonAmount)
                                                            ({{ $type->rate }}% of Room base rate ÷ {{ $count }} pax)
                                                        @else
                                                            (Fixed)
                                                        @endif
                                                    @endif
                                                </span>
                                                <div class="flex items-center space-x-2">
                                                    <span>- ₱{{ number_format($totalValue, 2) }}</span>
                                                    <button wire:click="removeDiscount({{ $invoice->id }}, {{ $type->id }})"
                                                        class="text-red-500 hover:text-red-700 text-xs" title="Remove discount">
                                                        <i class="fas fa-circle-xmark"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                    <!-- Subtotal with discount -->
                    {{-- <div class="flex justify-between font-semibold text-base text-gray-700">
                                <span>Subtotal with discount:</span>
                                <span>₱{{ number_format($this->computeInvoiceWithDiscount(), 2) }}</span>
                            </div> --}}

                    @if ($transaction->promoCode)
                        <!-- Promo Applied -->
                        <div <!-- Promo Applied -->
                            <div
                                class="flex items-center justify-between p-2 font-semibold  bg-green-50 border border-green-200 rounded-lg dark:bg-green-900 dark:border-green-700">
                                <div>
                                    <span class="font-semibold text-green-700 dark:text-green-300 text-sm">
                                        Promo Applied: {{ $transaction->promoCode->code ?? '' }}
                                        @if ($transaction->promoCode && $transaction->promoCode->discount_type == 'percentage')
                                            ({{ number_format($transaction->promoCode->discount_value, 0) }}%)
                                        @elseif ($transaction->promoCode)
                                            (₱{{ number_format($transaction->promoCode->discount_value, 2) }})
                                        @endif
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <p class="text-green-600 dark:text-green-400 mt-1 text-sm">
                                        - ₱{{ number_format($transaction->promo_discount_amount, 2) }}
                                    </p>
                                    @if ($transaction->transaction_status != 'done')
                                        <button wire:click="removePromoCode"
                                            class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                                            title="Remove Promo Code">
                                            <i class="fas fa-circle-xmark"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Promo Code Messages -->
                        @if ($errorMessage)
                            <p
                                class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded border border-red-200 dark:bg-red-900 dark:text-red-300">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errorMessage }}
                            </p>
                        @endif

                        <!-- Promo Code Note -->
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Promo codes apply to room charges only and cannot be combined with other offers.
                        </p>
                    @endif

                    {{-- @if ($transaction->promoCode)
                            <!-- Sub Total with discount -->
                            <div class="flex justify-between font-semibold text-base text-gray-700">
                                Subtotal after discount:
                                <div>
                                    ₱{{ number_format($this->computeBaseSubtotalAfterDiscount(), 2) }}
                                </div>
                            </div>
                            @endif --}}

                    @if ($this->computeConvenienceFeeTotal() > 0)
                        <!-- Convenience Fee -->
                        <div class="flex justify-between font-semibold text-base mb-2 text-gray-700">
                            Convenience Fee:
                            <div>
                                ₱{{ number_format($this->computeConvenienceFeeTotal(), 2) }}
                            </div>
                        </div>
                    @endif

                    <hr class="my-2">

                    <!-- Grand Total -->
                    <div class="flex justify-between font-bold text-base text-green-700">
                        Grand Total:
                        <div>
                            ₱{{ number_format($this->invoice->sub_total, 2) }}
                        </div>
                    </div>

                    <!-- Amount Paid -->
                    <div
                        class="flex justify-between font-semibold text-base
                                        {{ $this->invoice->amount_paid == $this->invoice->sub_total ? 'text-green-700' : 'text-yellow-500' }}">
                        Amount Paid:
                        <div>
                            ₱{{ number_format($this->invoice->amount_paid, 2) }}
                        </div>
                    </div>

                    @if ($this->invoice->amount_paid > $this->invoice->sub_total)
                        <!-- Overpayment / Change -->
                        <div class="flex justify-between font-semibold text-base text-blue-600">
                            Change / Overpayment:
                            <div>
                                ₱{{ number_format($this->invoice->amount_paid - $this->invoice->sub_total, 2) }}
                            </div>
                        </div>
                    @else
                        <!-- Balance Due -->
                        <div
                            class="flex justify-between font-semibold text-base
                                                {{ $this->invoice->balance_due == 0 ? 'text-green-700' : 'text-red-500' }}">
                            Balance Due:
                            <div>
                                ₱{{ number_format($this->invoice->balance_due, 2) }}
                            </div>
                        </div>
                    @endif

            </div>

            {{-- <!-- Promo Code Section -->
                        <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                            <h3 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">Apply Promo Code
                            </h3>

                            @if ($transaction->promoCode)
                            <!-- Promo already applied -->
                            <div
                                class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900 dark:border-green-700">
                                <div>
                                    <span class="font-semibold text-green-700 dark:text-green-300">
                                        Promo Applied: {{ $transaction->promoCode->code }}
                                    </span>
                                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                        Discount: ₱{{ number_format($transaction->promo_discount_amount, 2) }}
                                    </p>
                                </div>
                                <button wire:click="removePromoCode"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                    title="Remove Promo Code">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @else
                            <!-- Promo code input -->
                            <div class="flex space-x-2">
                                <div class="flex-grow">
                                    <input type="text" wire:model="promoCode" placeholder="Enter promo code"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                        {{ $transaction->promoCode ? 'disabled' : '' }}>
                                </div>
                                <button wire:click="applyPromoCode"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Apply
                                </button>
                            </div>
                            @endif

                            <!-- Promo Code Messages -->
                            @if ($discountMessage)
                            <p
                                class="mt-2 text-sm text-green-600 bg-green-50 p-2 rounded border border-green-200 dark:bg-green-900 dark:text-green-300">
                                <i class="fas fa-check-circle mr-1"></i> {{ $discountMessage }}
                            </p>
                            @endif

                            @if ($errorMessage)
                            <p
                                class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded border border-red-200 dark:bg-red-900 dark:text-red-300">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errorMessage }}
                            </p>
                            @endif

                            <!-- Promo Code Note -->
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Promo codes apply to room charges only and cannot be combined with other offers.
                            </p>
                        </div> --}}
        @else
            <p class="text-gray-600 italic">No invoice found for this transaction.</p>
            @endif
            <!------------------------  END OF INVOICE ----------------------------------------->

            <!----------------------------- PAYMENTS -------------------------------------->
            <section id="payments">
                <div
                    class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                    <div class="justify-between flex items-center">
                        <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                            Payments (₱{{ number_format($this->invoice->amount_paid, 2) }})
                        </h2>
                        <div class="text-left mb-4 flex items-center gap-2">
                            @if ($transaction->transaction_status != 'done')
                                <x-button wire:click="OpenCreatePaymentModal">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create Payment
                                </x-button>
                            @else
                                <x-button disabled class="bg-gray-400 cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Create Payment
                                </x-button>
                            @endif
                        </div>
                    </div>
                    @if ($payments->isNotEmpty())
                        <div class="">
                            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            #</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Invoice ID</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Method</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Amount Paid</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Type</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Reference No.</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Payment Date</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Status</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Notes</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Verified At</th>
                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Uploaded Receipt</th>

                                        <th
                                            class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                            Actions
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-600 ">
                                    @foreach ($payments as $payment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->invoice->invoice_number }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->paymentMethod?->mode_of_payment_name ?? ($payment->mode_of_payment ?? 'N/A') }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 leading-tight">
                                                <div class="font-semibold">
                                                    ₱{{ number_format($payment->amount_paid, 2) }}
                                                </div>
                                                @if ($payment->convenience_fee > 0)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">
                                                        (with ₱{{ number_format($payment->convenience_fee, 2) }}
                                                        convenience fee)
                                                    </div>
                                                @else
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic text-center">
                                                        No convenience fee
                                                    </div>
                                                @endif

                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ ucfirst($payment->payment_type) }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 break-words max-w-[96px]">
                                                {{ $payment->payment_reference_number ?? 'N/A' }}
                                            </td>

                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-500">
                                                <span
                                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                        {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                        {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                        {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->notes ?? 'N/A' }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->verified_at ? $payment->verified_at->format('M d, Y h:i A') : 'To be verified' }}
                                            </td>
                                            <td
                                                class="border px-4 py-2 space-x-2 dark:text-gray-200 dark:border-gray-500">
                                                @if (!$payment->payment_screenshot)
                                                    @if (empty($payment->payment_screenshot))
                                                        <span class="text-gray-500 italic dark:text-gray-200">
                                                            No receipt uploaded
                                                        </span>
                                                    {{-- @elseif ($payment->mode_of_payment_name === 'Cash')
                                                        <span class="text-gray-700 dark:text-gray-200">
                                                            Cash payment received
                                                        </span> --}}
                                                    @else
                                                        <span class="text-gray-700 dark:text-gray-200">
                                                            Completed via secure online payment
                                                        </span>
                                                    @endif

                                                @else
                                                    @if ($payment->payment_status === 'pending')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                            class="inline-block bg-yellow-100 hover:bg-yellow-200 text-yellow-500 font-semibold text-center py-2 px-3 rounded text-xs">
                                                            Verify Receipt
                                                        </a>
                                                    @elseif ($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                            class="inline-block py-1 px-2 rounded-md text-xs font-semibold bg-green-100 text-green-500 text-center hover:bg-green-200 hover:text-green-600">
                                                            View Receipt
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 text-center">
                                            @if ($this->canEditPayment($payment))
                                                <span class="text-gray-400 dark:text-gray-500" title="Not Available - Transaction Completed">
                                                <button wire:click="editPayment({{ $payment->id }})"
                                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-500"
                                                        title="Edit Payment">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500"
                                                    title="{{ $transaction->transaction_status === 'done'
                                                        ? 'Not Available - Transaction Completed'
                                                        : 'Cannot edit online payments' }}">
                                                    <i class="fas fa-lock"></i>
                                                </span>
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
            </section>
            <!-------------------------- END OF PAYMENTS ---------------------------------->
        </div>

    </div>
    <!-- Back Button -->
    <div class="justify-end flex mt-2">
        <x-ghost-button onclick="history.back()" icon="fas fa-arrow-left">
            Back
        </x-ghost-button>
    </div>





    <!---------------------------- MODALS ---------------------------------------->

    <!-- Show Receipt Modal -->
    @if ($showReceiptModal && $receipt)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden dark:bg-gray-800">
                <!-- Header -->
                <div
                    class="bg-green-50 flex justify-between items-center border-b border-gray-200 px-6 py-4 dark:bg-gray-900 dark:border-gray-700">
                    <h2 class="text-xl font-bold tracking-wide text-green-700 dark:text-green-300">
                        Acknowledgment Receipt
                    </h2>
                    <button wire:click="$set('showReceiptModal', false)"
                        class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 hover:bg-red-100 hover:text-red-600 transition duration-200 text-xl"
                        title="Close">
                        &times;
                    </button>
                </div>

                <!-- Session Alert -->
                @if (session('message'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                        class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-md
                                        {{ session('alert-type') === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Receipt Details -->
                <div class="p-6 space-y-4 text-gray-700 text-sm">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="flex justify-between py-2">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Receipt Number</span>
                            <span class="font-semibold text-gray-900">{{ $receipt->receipt_number }}</span>
                        </div>

                        <div class="flex justify-between py-2">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Receipt Date</span>
                            <span class="text-gray-900">{{ $receipt->receipt_date->format('F d, Y') }}</span>
                        </div>

                        <div class="flex justify-between py-2">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Invoice Number</span>
                            <span class="text-gray-900">{{ $invoice->invoice_number }}</span>
                        </div>

                        <div class="flex justify-between py-2">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Guest</span>
                            <span class="text-gray-900">
                                {{ $transaction->transactionUser->first_name ?? 'N/A' }}
                                {{ $transaction->transactionUser->last_name ?? '' }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Summary Table -->
                    <div class="mt-6">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Payment Summary:</h2>
                        <div class="border border-gray-300 rounded-lg overflow-hidden text-sm">
                            <table class="w-full">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="text-left px-4 py-2 font-medium">Description</th>
                                        <th class="text-right px-4 py-2 font-medium">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2">Amount Received</td>
                                        <td class="px-4 py-2 text-right font-semibold text-green-700">
                                            ₱{{ number_format($receipt->amount_received, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                        <span class="block font-medium text-gray-800 dark:text-gray-300 mb-1">Notes</span>
                        <p class="text-gray-600 dark:text-gray-400 italic text-sm">
                            {{ $receipt->notes ?? 'None' }}
                        </p>
                    </div>

                    <!-- Footer Text -->
                    <div class="text-center text-xs text-gray-500 dark:text-gray-400">
                        <p>This receipt acknowledges payment for the specified invoice.</p>
                        <p class="mt-1">Generated on {{ $receipt->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-between gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <!-- Print Receipt -->
                    <x-button wire:click="printOfficialReceipt" wire:loading.attr="disabled">
                        <div class="flex items-center">
                            <span wire:loading wire:target="printOfficialReceipt" class="mr-2">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                </svg>
                            </span>
                            <i class="fas fa-print mr-2" wire:loading.remove wire:target="printOfficialReceipt"></i>
                            <span wire:loading.remove wire:target="printOfficialReceipt">
                                Print Receipt
                            </span>
                        </div>
                    </x-button>

                    <!-- Send Email -->
                    <x-warning-button wire:click="sendReceiptToEmail" wire:loading.attr="disabled">
                        <div class="flex items-center">
                            <span wire:loading wire:target="sendReceiptToEmail" class="mr-2">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                </svg>
                            </span>
                            <i class="fas fa-envelope mr-2" wire:loading.remove wire:target="sendReceiptToEmail"></i>
                            <span wire:loading.remove wire:target="sendReceiptToEmail">
                                Send to Email
                            </span>
                        </div>
                    </x-warning-button>
                </div>
            </div>
        </div>

    @endif

    <!-- Cannot Generate Receipt -->
    @if ($cannotGenerateReceiptModal)
        <div>
            <x-dialog-modal wire:model.live="cannotGenerateReceiptModal" type="ghost">
                <x-slot name="title">
                    {{ __('Cannot Perform Action') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Receipt cannot be generated. Invoice still has balance due.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotGenerateReceiptModal', false)"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    @endif

    <!-- Add Payment Modal -->
    @if ($createPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">

                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Add Payment</h2>

                    <!-- Close Button -->
                    <button wire:click="CloseCreatePaymentModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                <!-- Modal Content -->
                <div>
                    <!-- Amount Paid -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Amount Paid <span
                                class="text-red-500">*</span></label>
                        <input type="number" wire:model.defer="amount_paid" placeholder="Ex. 1,200.00"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600"
                            required>
                        @error('amount_paid')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Payment Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" wire:model.defer="payment_date"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600"
                            required>
                        @error('payment_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Type -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Payment Type <span
                                class="text-red-500">*</span></label>
                        <select wire:model="payment_type"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600"
                            required>
                            <option value="">Select Payment Type</option>
                            {{-- <option value="Room Rent">Room Rent</option>
                            <option value="Security Deposit">Security Deposit</option>
                            <option value="Remaining Balance">Remaining Balance</option>
                            <option value="Merchandise">Merchandise</option> --}}
                            <option value="Accommodation Fully Paid">Accommodation Fully Paid</option>
                            <option value="Accommodation Downpayment">Accommodation Downpayment</option>
                            <option value="Accommodation Balance">Accommodation Balance</option>

                        </select>
                        @error('payment_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Payment Methods --}}
                    <div class="mt-4">
                        <label for="payment_method_id" class="block text-sm text-gray-700 font-semibold">Payment
                            Method <span class="text-red-500">*</span></label>
                        <select wire:model="payment_method_id" id="payment_method_id"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Payment Method</option>
                            @foreach ($payment_methods as $payment_method)
                                <option value="{{ $payment_method->id }}">
                                    {{ $payment_method->mode_of_payment_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Upload Payment Screenshot --}}
                    <div class="sm:col-span-2 mt-4">
                        <label for="payment_screenshot" class="block text-sm text-gray-700 font-semibold mb-1">
                            Proof of Payment </label>

                        <!-- Hidden file input -->
                        <input id="payment_screenshot" type="file" accept="image/*"
                            wire:model="payment_screenshot" class="hidden">

                        @if ($payment_screenshot && method_exists($payment_screenshot, 'temporaryUrl'))
                            <!-- Show image preview -->
                            <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden">
                                <img src="{{ $payment_screenshot->temporaryUrl() }}"
                                    class="w-full h-full object-cover" alt="Payment Screenshot Preview"
                                    onclick="openModal('{{ $payment_screenshot->temporaryUrl() }}')">

                                <label for="payment_screenshot"
                                    class="absolute top-1 right-1 bg-white text-gray-700 rounded-full px-1 text-xs cursor-pointer hover:bg-gray-200 hover:text-gray-800 transition">
                                    Re-Upload File
                                </label>
                            </div>
                            <!-- Image Popup View -->
                            <div id="imageModal"
                                class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                                <div class="flex items-center justify-center min-h-screen">
                                    <div class=" relative modal-content">
                                        <img id="modalImg" src=""
                                            class="max-w-full max-h-[80vh] rounded-md">
                                        <button type="button" onclick="closeModal()"
                                            class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                            <span class="leading-none translate-y-[-3px]">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Show drag and drop box -->
                            <label for="payment_screenshot">
                                <div
                                    class="w-full px-4 py-8 border-2 border-dashed border-gray-300 text-center rounded-md text-gray-500 cursor-pointer hover:border-blue-400">
                                    <div class="mb-2">
                                        <i class="fas fa-upload mr-2"></i>
                                    </div>
                                    <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span>
                                    </p>
                                </div>
                            </label>
                        @endif

                        <!-- Loading Indicator -->
                        <div wire:loading wire:target="payment_screenshot"
                            class="mt-2 text-gray-600 flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                            <span>Uploading...</span>
                        </div>

                        <!-- Error Message -->
                        @error('payment_screenshot')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Notes</label>
                        <input type="text" wire:model="notes" placeholder="Optionally add description of payment"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600">
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-between gap-3 mt-6">
                    <x-ghost-button wire:click="CloseCreatePaymentModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="CreatePayment">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Charge Modal -->
    @if ($activeModal === 'service')
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Add Charge</h2>

                    <!-- Close Button -->
                    <button wire:click="closeModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                @error('cart')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                @foreach ($availableServices as $service)
                    <!-- Service Details -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform mb-4 dark:bg-gray-700 dark:border-gray-600">
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-2">
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-gray-900 mb-1 dark:text-white">
                                    {{ $service->name }}
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed mb-3 dark:text-gray-300">
                                    {{ $service->description ?? 'No description provided for this service.' }}
                                </p>

                                <div class="text-lg font-bold text-green-700 dark:text-green-300">
                                    @if ($service->id == 10)
                                        <span class="text-lg font-bold text-green-700 dark:text-green-300">Depends
                                            on room
                                            rate</span>
                                        <span class="text-base font-normal text-gray-500 dark:text-gray-400">/
                                            {{ $service->unit }}</span>
                                    @else
                                        ₱{{ number_format($service->amount, 2) }}
                                        <span class="text-base font-normal text-gray-500 dark:text-gray-400">/
                                            {{ $service->unit }}</span>
                                    @endif
                                </div>

                                {{-- Show checkboxes only for Extra Hour service --}}
                                @if ($service->id == 10 || strtolower($service->name) == 'extra hour')
                                    <div class="mt-3">
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Select properties for extra hour:
                                        </label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                            @foreach ($transaction->properties as $property)
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox"
                                                        wire:model="extraHourProperties.{{ $service->id }}.{{ $property->id }}"
                                                        value="{{ $property->id }}"
                                                        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring focus:ring-green-300 focus:ring-opacity-50">

                                                    <!-- Display error if no property is selected -->
                                                    @error('extraHourProperties')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror

                                                    <span class="text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $property->name_number }}
                                                        (₱{{ number_format($property->extra_charge_per_hour, 2) }})
                                                    </span>

                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Quantity & Button --}}
                            <div class="flex items-center justify-between mt-2 gap-4">
                                <!-- Quantity Counter -->
                                <div class="flex flex-col">
                                    <label for="quantity-{{ $service->id }}"
                                        class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                                    <!-- Quantity Counter Buttons -->
                                    <div class="flex items-center">
                                        <button wire:click="decrementItemQuantity('service', {{ $service->id }})"
                                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</button>
                                        <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                            {{ $quantity[$service->id] ?? 1 }}
                                        </span>
                                        <button wire:click="incrementItemQuantity('service', {{ $service->id }})"
                                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">+</button>
                                        <!-- Hidden input to bind the quantity -->
                                        <input type="hidden" wire:model="quantity.{{ $service->id }}">
                                    </div>
                                </div>

                                <!-- Add / Remove Button -->
                                @php
                                    $inCart = collect($cart)->contains(function ($item) use ($service) {
                                        return $item['type'] === 'service' && $item['service_id'] == $service->id;
                                    });
                                @endphp
                                <div class="mt-6">
                                    <button
                                        wire:click="{{ $inCart ? 'removeItemFromCart' : 'addItemToCart' }}('service', {{ $service->id }})"
                                        class="px-4 py-2 {{ $inCart ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest disabled:opacity-50 transition ease-in-out duration-150">
                                        {{ $inCart ? 'Remove' : 'Add' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <!-- Actions -->
                <div class="flex justify-between items-center gap-2 mt-7">
                    <x-ghost-button type="button" wire:click="closeModal">
                        Cancel
                    </x-ghost-button>
                    <x-button type="button" wire:click="saveService">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Activity Modal -->
    <div>
        @if ($activeModal === 'activity')
            <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div
                        class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
                        <!-- Title -->
                        <h2 class="text-2xl font-bold text-center">Add Activity</h2>

                        <!-- Close Button -->
                        <button wire:click="closeModal"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                            <span class="-translate-y-[2px]">&times;</span>
                        </button>
                    </div>

                    @error('cart')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    @foreach ($availableActivities as $activity)
                        <div class="flex items-center justify-between bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-4 dark:bg-gray-500 dark:border-gray-400">


                            <!-- Activity Image -->
                            @php
                                // Normalize $activity->images into an array safely
                                $images = [];

                                if (is_string($activity->images)) {
                                    $decoded = json_decode($activity->images, true);
                                    $images = is_array($decoded) ? $decoded : [];
                                } elseif (is_array($activity->images)) {
                                    $images = $activity->images;
                                }

                                // Get first image if available
                                $firstImage = $images[0] ?? null;
                            @endphp

                            <div
                                class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                <img src="{{ asset($firstImage ? 'storage/' . $firstImage : 'images/rms-default.png') }}"
                                    alt="{{ $activity->name }}" class="object-cover w-full h-full">
                            </div>


                            <!-- Middle: Name and Description -->
                            <div class="flex-1 px-8">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-white">
                                    {{ $activity->name }}</h4>
                                {{-- <p class="text-sm text-gray-600 mt-1 dark:text-gray-200 text-justify">
                                     Show more / less when description is long
                                    @if (empty($activity->description))
                                        <span class="italic text-gray-400">No description provided</span>
                                    @elseif ($expandedActivity === $activity->id)
                                        {{ $activity->description }}
                                        <a href="#"
                                            wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                            class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                            less</a>
                                    @else
                                        {{ Str::limit($activity->description, 100, '...') }}
                                        @if (Str::length($activity->description) > 100)
                                            <a href="#"
                                                wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                                class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                                more</a>
                                        @endif
                                    @endif
                                </p> --}}
                                <!-- Preferred Time -->
                                @if ($activity->schedule_type !== 'no_schedule')
                                    <div class="mt-4">
                                        <h3 class="text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">
                                            Preferred Time</h3>

                                        @if ($activity->schedule_type === 'system')
                                            @if (is_array($activity->available_times) && count($activity->available_times))
                                                <div class="grid grid-cols-2 gap-2">
                                                    @foreach ($activity->available_times as $time)
                                                        <label
                                                            class="flex items-center p-2 bg-white dark:bg-gray-700 border border-gray-300 rounded cursor-pointer shadow-sm hover:border-green-500">
                                                            <input type="radio"
                                                                name="selected_time_{{ $activity->id }}"
                                                                wire:model="selectedTimes.{{ $activity->id }}"
                                                                value="{{ $time }}"
                                                                class="form-radio text-green-600 focus:ring-green-500">
                                                            <span
                                                                class="ml-2 text-sm text-gray-800 dark:text-gray-200">
                                                                {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 italic">No system-defined
                                                    schedule for this activity.</p>
                                            @endif
                                        @elseif ($activity->schedule_type === 'guest')
                                            <input type="time"
                                                wire:model.lazy="selectedTimes.{{ $activity->id }}"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                        @endif

                                        @error("selectedTimes.{$activity->id}")
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                <h4 class="text-xl font-semibold text-green-700 dark:text-white mt-2">
                                    ₱{{ number_format($activity->amount, 2) }}</h4>
                            </div>

                            <!-- Right: Price and Quantity -->
                            <div class="flex flex-col md:flex-row md:space-x-6">
                                <div class="flex flex-col">
                                    <div>
                                        <label for="quantity-{{ $activity->id }}"
                                            class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200 text-center">
                                            Quantity:
                                        </label>

                                        <!-- Counter Buttons -->
                                        <div class="flex items-center">
                                            <button type="button"
                                                wire:click.prevent="decrementActivity('{{ $activity->id }}')"
                                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline
                                                            dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                                -
                                            </button>

                                            <span
                                                class="text-center w-16 py-1 bg-white border border-gray-300 rounded ">
                                                {{ $quantity[$activity->id] ?? 1 }}
                                            </span>

                                            @if ($quantity[$activity->id] ?? 1)
                                                <button type="button"
                                                    wire:click.prevent="incrementActivity('{{ $activity->id }}')"
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline
                                                                dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                                    +
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Add/Remove Button --}}
                                    @php
                                        $cartCollection = collect($cart);
                                        $activityInCart = $cartCollection->contains(
                                            fn($item) => $item['type'] === 'activity' &&
                                                $item['activity_id'] == $activity->id,
                                        );
                                    @endphp

                                    <!-- Add Activity Button -->
                                    <div class="mt-8">
                                        <x-button
                                            wire:click="{{ $activityInCart ? 'removeItemFromCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                            class="w-full sm:w-auto px-4 py-2 {{ $activityInCart ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150 uppercase"
                                            wire:loading.attr="disabled">
                                            <div class="flex items-center justify-center">
                                                <span wire:loading
                                                    wire:target="{{ $activityInCart ? 'addItemToCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                                    class="mr-2">
                                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4" />
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                                    </svg>
                                                </span>
                                                <span wire:loading.remove
                                                    wire:target="{{ $activityInCart ? 'addItemToCart' : 'addItemToCart' }}('activity', {{ $activity->id }})">
                                                    {{ $activityInCart ? 'Remove' : 'Add' }}
                                                </span>
                                            </div>
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <!-- Actions -->
                    <div class="flex justify-between items-center gap-2 mt-8">
                        <x-ghost-button type="button" wire:click="closeModal">
                            Cancel
                        </x-ghost-button>
                        <x-button type="button" wire:click="saveActivity">
                            Save Changes
                        </x-button>
                    </div>

                </div>
            </div>
        @endif
    </div>

    <!-- Add Guest Modal -->
    @if ($activeModal === 'guest')
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Enter Guest Details</h2>

                    <!-- Close Button -->
                    <button wire:click="closeModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                <!-- Guest Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">First Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="guest.first_name"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Juan" required>
                        @error('guest.first_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Middle
                            Name</label>
                        <input type="text" wire:model.defer="guest.middle_name"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Mercado">
                        @error('guest.middle_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Last Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="guest.last_name"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            required placeholder="Ex. Dela Cruz">
                        @error('guest.last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Suffix -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                        <input type="text" wire:model.defer="guest.suffix"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Jr., Sr., III">
                        @error('guest.suffix')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Transaction Property -->
                <div class="mt-4">
                    @if ($transactionProperties->count() === 1)
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Room</label>
                        <input type="hidden" wire:model="guest.transaction_property_id"
                            value="{{ $transactionProperties->first()->id }}">
                        <p
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                            {{ $transactionProperties->first()->property->name_number ?? 'Property #' . $transactionProperties->first()->id }}
                        </p>
                    @else
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">
                            Room <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.defer="guest.transaction_property_id"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                            <option value="">Select Room</option>
                            @foreach ($transactionProperties as $property)
                                <option value="{{ $property->id }}">
                                    {{ $property->property->name_number ?? 'Property #' . $property->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('guest.transaction_property_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    @endif
                </div>


                <!-- Guest Type -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">
                        Guest Type <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.defer="guest.guest_type_id"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                            dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Guest Type</option>
                        @foreach ($guestTypes as $type)
                            <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                        @endforeach
                    </select>
                    @error('guest.guest_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Gender -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Gender <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="guest.gender"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Prefer not to say</option>
                    </select>
                    @error('guest.gender')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Residency -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Residency <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="guest.residency"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Residency</option>
                        <option value="local">Local</option>
                        <option value="foreigner">Foreigner</option>
                    </select>
                    @error('guest.residency')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country of Origin -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                            class="text-red-500">*</span></label>
                    <select wire:model="guest.country_of_origin"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="" disabled selected>Select a country</option>
                        @foreach ($countries as $countryOption)
                            <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between mt-4">
                    <x-ghost-button type="button" wire:click="closeModal">
                        Cancel
                    </x-ghost-button>
                    <x-button type="button" wire:click="saveGuest" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center">
                            <!-- Spinner -->
                            <span wire:loading class="mr-2" wire:target="saveGuest">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                            </span>

                            <!-- Button Text -->
                            <span wire:loading.remove wire:target="saveGuest">
                                Add Guest
                            </span>


                        </div>
                    </x-button>
                </div>



            </div>
        </div>
    @endif

    <!-- Add Pet Modal -->
    @if ($activeModal === 'pet')
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                    <h2 class="text-2xl font-bold text-center">Add Pet Info</h2>
                    <button wire:click="closeModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                {{-- Breed Input --}}
                <div class="mb-4">
                    <label class="block text-md font-medium text-gray-700 mb-1 dark:text-gray-300">Breed</label>
                    <input type="text" wire:model="breed"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                    @error('breed')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="closeModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="savePet">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    {{--
    @if ($activeModal === 'discounts')
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div
            class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">
            <!-- Header -->
            <div
                class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                <h2 class="text-2xl font-bold text-center">
                    @if ($transaction->promoCode)
                    Add Discounts (Extra Guests Only)
                    @else
                    Add PWD/Senior Discounts
                    @endif
                </h2>
                <button wire:click="closeModal"
                    class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                    <span class="-translate-y-[2px]">&times;</span>
                </button>
            </div>

            <!-- Promo Code Warning -->
            @if ($transaction->promoCode)
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700 text-sm">
                    <strong>Note:</strong> Discounts are limited to {{ $this->getTotalExtraGuests() }} extra
                    adult guest(s)
                    since a promo code is applied to this reservation.
            </div>
            @else
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700 text-sm">
                    <strong>Note:</strong> Discounts are limited to {{ $transaction->pax }} guest(s)
                    (total number of guests in this reservation).
                </p>
            </div>

            @endif

            <!-- Form Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Number of
                        PWDs</label>
                    <input type="number" min="0" @if ($transaction->promoCode) max="{{
                    $this->getTotalExtraGuests() }}" @endif
                    wire:model="pwdCount" onwheel="this.blur()"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600
                    focus:border-green-600 block p-2.5
                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                    @if ($transaction->promoCode)
                    <p class="text-xs text-gray-500 mt-1">Max: {{ $this->getTotalExtraGuests() }} (extra adult
                        guests only)</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold mb-1">Number of
                        Seniors</label>
                    <input type="number" min="0" @if ($transaction->promoCode) max="{{ max(0,
                    $this->getTotalExtraGuests() - $pwdCount) }}" @endif
                    wire:model="seniorCount" onwheel="this.blur()"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600
                    focus:border-green-600 block p-2.5
                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                    @if ($transaction->promoCode)
                    <p class="text-xs text-gray-500 mt-1">Remaining: {{ max(0, $this->getTotalExtraGuests() -
                        $pwdCount) }}</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between mt-6">
                <x-ghost-button wire:click="closeModal">
                    Cancel
                </x-ghost-button>
                <x-button wire:click="applyDiscounts">
                    Apply Discounts
                </x-button>
            </div>
        </div>
    </div>
    @endif

    --}}

    <!-- Manual PWD/Senior Discount Modal -->
    @if ($showDiscountModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                <!-- Header -->
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                        <h2 class="text-2xl font-bold text-center">
                            {{ $this->discountsApplied ? 'Edit PWD/Senior Discount' : 'Add PWD/Senior Discount' }}
                        </h2>
                    <button wire:click="closeDiscountModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>


        <!-- PWD/Senior ID Management Section -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 dark:text-gray-300">PWD/Senior Information</h3>

            <!-- Add New ID Form -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                    Add PWD/Senior Details
                </label>
                <div class="space-y-3">
                    <!-- Name Field -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1 dark:text-gray-400">Full Name</label>
                        <input type="text" wire:model="editingPwdSeniorName"
                            placeholder="Enter full name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:text-white text-sm">
                        @error('editingPwdSeniorName')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ID Field -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1 dark:text-gray-400">PWD/Senior ID Number</label>
                        <div class="flex space-x-2">
                            <input type="text" wire:model="editingPwdSeniorId"
                                placeholder="Enter PWD or Senior ID number"
                                class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:text-white text-sm">
                            <x-button wire:click="addPwdSeniorId" class="whitespace-nowrap" wire:loading.attr="disabled">
                                Add
                            </x-button>
                        </div>
                        @error('editingPwdSeniorId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Existing IDs List -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                    Current PWD/Senior Records ({{ count($pwdSeniorIds) }})
                </label>
                @if (count($pwdSeniorIds) > 0)
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3 dark:border-gray-600">
                        @foreach ($pwdSeniorIds as $index => $idData)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded border dark:bg-gray-700 dark:border-gray-600">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-medium text-gray-800 dark:text-gray-200 block">{{ $idData['name'] }}</span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">ID: {{ $idData['id'] }}</span>
                                        </div>
                                        @if (isset($idData['added_at']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($idData['added_at'])->format('M j, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="removePwdSeniorId({{ $index }})"
                                    class="ml-3 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                    title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic text-center py-3 dark:text-gray-400 bg-gray-50 rounded border dark:bg-gray-700 dark:border-gray-600">
                        No PWD/Senior records added yet.
                    </p>
                @endif
            </div>
        </div>



                <!-- Simple Amount Input -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-md font-medium text-gray-700 mb-1 dark:text-gray-300">Enter PWD/Senior
                            Discount
                            Amount
                            <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="manualDiscountAmount" min="0"
                            max="{{ $this->computeBaseSubtotal() }}" onwheel="this.blur()"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600"
                            placeholder="Ex. ₱250">
                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                            Maximum allowed: ₱{{ number_format($this->computeBaseSubtotal(), 2) }}
                        </p>
                        @error('manualDiscountAmount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="closeDiscountModal" class="dark:bg-gray-700 dark:text-gray-300">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="applyDiscounts">
                        {{ $this->discountsApplied ? 'Update Discount' : 'Apply Discount' }}
                    </x-button>
                </div>
            </div>
        </div>
    @endif


    {{-- Add Voucher Modal --}}
    @if ($activeModal === 'voucher')
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">

                {{-- Header --}}
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-blue-50 text-blue-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-blue-300">
                    <h2 class="text-2xl font-bold text-center">Add Voucher</h2>
                    <button wire:click="closeModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                {{-- Form Fields --}}
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium mb-1">Voucher Type</label>
                        <select wire:model="voucher_type"
                            class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Select Type --</option>
                            <option value="Food">Food</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('voucher_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Voucher Amount</label>
                        <input type="number" min="0" step="0.01" wire:model="voucher_amount"
                            class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
                        @error('voucher_amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="closeModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="saveVoucher">
                        Save Voucher
                    </x-button>
                </div>
            </div>
        </div>
    @endif



    <!-- Add Request Modal -->
    @if ($activeModal === 'requests')
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">

                <!-- Header -->
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                    <h2 class="text-2xl font-bold text-center">Requests Info</h2>
                    <button wire:click="closeModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                <!-- Guest Request -->
                <div class="mb-4">
                    <strong class="block text-sm text-gray-700 font-semibold">Guest Request:</strong>
                    <p class="mt-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 p-3 rounded-lg">
                        {{ $transaction->requests ?? 'No request submitted.' }}
                    </p>
                </div>

                <!-- Admin Reply -->
                <div class="mb-4">
                    <strong class="block text-sm text-gray-700 font-semibold">Admin Reply:</strong>
                    <textarea wire:model.defer="request_reply"
                        class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm mt-2 p-3" rows="3"
                        placeholder="Write your reply here..."></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="closeModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="saveRequestReply">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Edit Room Modal -->
    @if ($showEditRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md ">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Guest Quantity in Room</h2>
                </div>

                <div class=" items-center justify-center flex flex-col">
                    <div class="mb-4">
                        <label class="block mb-1 text-center">Adults</label>
                        <div class="flex items-center">
                            <button type="button" wire:click="decrementAdults"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">−</button>
                            <input type="number" wire:model="roomTotalAdults" min="0"
                                class="w-16 text-center border rounded px-2 py-1">
                            <button type="button" wire:click="incrementAdults"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">+</button>
                        </div>
                        @error('roomTotalAdults')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-center">Kids</label>
                        <div class="flex items-center">
                            <button type="button" wire:click="decrementKids"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">−</button>
                            <input type="number" wire:model="roomTotalKids" min="0"
                                class="w-16 text-center border rounded px-2 py-1">
                            <button type="button" wire:click="incrementKids"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">+</button>
                        </div>
                        @error('roomTotalKids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showEditRoomModal', false)">
                        Cancel
                        </x-ghostbutton>
                        <x-button wire:click="updateRoom">
                            Update
                        </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Edit Service Modal -->
    @if ($showEditServiceModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Service Quantity</h2>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Quantity</label>
                    <input type="number" wire:model="serviceQuantity" min="1"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600">

                    @error('serviceQuantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showEditServiceModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateService">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Edit Extra Hour Modal -->
    @if ($showEditExtraHourModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Extra Hour Quantity</h2>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Quantity</label>
                    <input type="number" wire:model="extraHourQuantity" min="1"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600">

                    @error('extraHourQuantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showEditExtraHourModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateExtraHour">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Add Extra Guest Modal -->
    @if ($showAddGuestModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Add Extra Guest</h2>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Quantity</label>
                    <input type="number" wire:model="extraGuestQuantity" min="1"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600">

                    @error('extraGuestQuantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showAddGuestModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="saveExtraGuest">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Edit Activity Modal -->
    @if ($showEditActivityModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Activity Details</h2>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Quantity</label>
                    <input type="number" wire:model="activityQuantity" min="1"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600">

                    @error('activityQuantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                @if ($activityScheduleType !== 'no_schedule')
                    <!-- Edit Time Section -->
                    <div class="flex flex-col items-start justify-center">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Preferred Time
                        </label>

                        @if ($activityScheduleType === 'guest')
                            <input type="time" id="activityTime" wire:model.lazy="editingActivityDateTime"
                                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @elseif ($activityScheduleType === 'system' && !empty($availableTimes))
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($availableTimes as $option)
                                    <label
                                        class="inline-flex items-center p-2 bg-white dark:bg-gray-700 border rounded cursor-pointer">
                                        <input type="radio" wire:model="editingActivityDateTime"
                                            value="{{ $option }}" class="form-radio text-green-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($option)->format('h:i A') }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif





                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showEditActivityModal', false)">
                        Cancel
                        </x-ghostbutton>
                        <x-button wire:click="updateActivity">
                            Update
                        </x-button>
                </div>
            </div>
        </div>
    @endif

    <!--  Edit Guest Modal -->
    @if ($showEditGuestModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Guest Details</h2>

                    <!-- Close Button -->
                    <button wire:click="$set('showEditGuestModal', false)"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                <!-- Guest Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">First Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="editingFirstName"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Juan">
                        @error('editingFirstName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Middle
                            Name</label>
                        <input type="text" wire:model="editingMiddleName"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Mercado">
                        @error('editingMiddleName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Last Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="editingLastName"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            required placeholder="Ex. Dela Cruz">
                        @error('editingLastName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Suffix -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                        <input type="text" wire:model="editingSuffix"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                            placeholder="Ex. Jr., Sr., III">
                        @error('editingSuffix')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Transaction Property -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Room <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="editingTransactionPropertyId"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Room</option>
                        @foreach ($transactionProperties as $property)
                            <option value="{{ $property->id }}">
                                {{ $property->property->name_number ?? 'Property #' . $property->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('editingTransactionPropertyId')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Birthdate -->
                {{-- <div class="mb-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Birthdate</label>
                    <input type="date" wire:model="editingBirthDate"
                        class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div> --}}

                <!-- Guest Type -->
                <!-- Optional: Guest Type (can be hidden or locked to a default) -->
                {{-- If you want admin to skip selecting guest type, skip this field --}}
                @if (auth()->user()->role !== 'admin')
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Guest Type
                            <span class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuestTypeId"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                            <option value="">Select Guest Type</option>
                            @foreach ($guestTypes as $type)
                                <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Gender -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Gender <span
                            class="text-red-500">*</span></label>
                    <select wire:model="editingGender"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Prefer not to say</option>
                    </select>
                    @error('editingGender')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Residency -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Residency
                        <span class="text-red-500">*</span></label>
                    <select wire:model="editingResidency"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        <option value="">Select Residency</option>
                        <option value="local">Local</option>
                        <option value="foreigner">Foreigner</option>
                    </select>
                    @error('editingResidency')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country of Origin -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Country of
                        Origin <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="editingCountryOfOrigin"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                        placeholder="Ex. Philippines">
                    @error('editingCountryOfOrigin')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between mt-4">
                    <x-ghost-button type="button" wire:click="$set('showEditGuestModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button type="button" wire:click="updateGuest" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center">
                            <!-- Spinner -->
                            <span wire:loading class="mr-2" wire:target="updateGuest">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                            </span>

                            <!-- Button Text -->
                            <span wire:loading.remove wire:target="updateGuest">
                                Save Changes
                            </span>
                        </div>
                    </x-button>
                </div>
            </div>

        </div>
    @endif

    <!--  Edit Pet Breed Modal -->
    @if ($showEditPetModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Pet Breed</h2>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Breed</label>
                    <input type="text" wire:model="editingBreed"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Enter pet breed">

                    @error('editingBreed')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between mt-3">
                    <x-ghost-button wire:click="$set('showEditPetModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updatePet">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Change Room Modal -->
    @if ($showChangeRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <h2 class="text-2xl font-bold text-center">Change Room</h2>
                </div>

                <!-- Current Room Info -->
                @if ($currentRoomDetails)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Current Room:</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ $currentRoomDetails->property->name_number ?? 'N/A' }} -
                            {{ optional($currentRoomDetails->property->category)->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Rate: ₱{{ number_format($currentRoomDetails->property->amount ?? 0, 2) }}/night
                        </p>
                    </div>
                @endif

                <!-- Available Rooms Dropdown -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Select New Room <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="selectedNewRoomId"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Choose a room...</option>
                        @foreach ($availableRooms as $room)
                            <option value="{{ $room->id }}" {{ $room->is_booked ? 'disabled' : '' }}
                                class="{{ $room->is_booked ? 'text-red-500 bg-red-50' : 'text-gray-900' }}">
                                {{ $room->name_number }} - {{ optional($room->category)->name }}
                                @if ($room->dynamic_rate)
                                    - ₱{{ number_format($room->dynamic_rate, 2) }}/night
                                @else
                                    - ₱{{ number_format($room->amount, 2) }}/night
                                @endif
                                @if ($room->is_booked)
                                    (Booked)
                                @elseif($room->id === $currentRoomDetails->property_id)
                                    (Current Room)
                                @else
                                    (Available)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('selectedNewRoomId')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Room Availability Note -->
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    <p>Only available rooms for your stay dates are shown.</p>
                    <p>Booked rooms are disabled and marked in red.</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="$set('showChangeRoomModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="changeRoom" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center">
                            <span wire:loading class="mr-2" wire:target="changeRoom">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="changeRoom">
                                Change Room
                            </span>
                        </div>
                    </x-button>
                </div>
            </div>
        </div>
    @endif


    <!-- Edit Payment Modal -->
    @if ($showEditPaymentModal && $editingPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div
                class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                <div
                    class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
                    <h2 class="text-2xl font-bold text-center">Edit Payment</h2>
                    <button wire:click="closeEditPaymentModal"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="-translate-y-[2px]">&times;</span>
                    </button>
                </div>

                <!-- Modal Content -->
                <div>
                    <!-- Amount Paid -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Amount Paid <span
                                class="text-red-500">*</span></label>
                        <input type="number" wire:model="edit_amount_paid"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('edit_amount_paid')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Payment Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" wire:model="edit_payment_date"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('edit_payment_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Type -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Payment Type <span
                                class="text-red-500">*</span></label>
                        <select wire:model="edit_payment_type"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Payment Type</option>
                            <option value="Accommodation Fully Paid">Accommodation Fully Paid</option>
                            <option value="Accommodation Downpayment">Accommodation Downpayment</option>
                            <option value="Accommodation Balance">Accommodation Balance</option>
                        </select>
                        @error('edit_payment_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Payment Method <span
                                class="text-red-500">*</span></label>
                        <select wire:model="edit_payment_method_id"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Payment Method</option>
                            @foreach ($payment_methods as $payment_method)
                                <option value="{{ $payment_method->id }}">
                                    {{ $payment_method->mode_of_payment_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('edit_payment_method_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Proof of Payment Upload -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">Proof of Payment</label>

                        <!-- Hidden file input -->
                        <input id="edit_payment_screenshot" type="file" accept="image/*"
                            wire:model="edit_payment_screenshot" class="hidden">

                        <!-- Show existing screenshot -->
                        @if ($existing_payment_screenshot && !$edit_payment_screenshot)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Current proof of payment:</p>
                                <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden border">
                                    <img src="{{ asset('storage/' . $existing_payment_screenshot) }}"
                                        class="w-full h-full object-cover" alt="Current Payment Screenshot"
                                        onclick="openEditModal('{{ asset('storage/' . $existing_payment_screenshot) }}')">

                                    <label for="edit_payment_screenshot"
                                        class="absolute top-2 right-2 bg-white text-gray-700 rounded-full px-3 py-1 text-xs cursor-pointer hover:bg-gray-200 hover:text-gray-800 transition shadow-md">
                                        Change File
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Show new screenshot preview -->
                        @if ($edit_payment_screenshot && method_exists($edit_payment_screenshot, 'temporaryUrl'))
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">New proof of payment:</p>
                                <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden border">
                                    <img src="{{ $edit_payment_screenshot->temporaryUrl() }}"
                                        class="w-full h-full object-cover" alt="New Payment Screenshot Preview"
                                        onclick="openEditModal('{{ $edit_payment_screenshot->temporaryUrl() }}')">

                                    <label for="edit_payment_screenshot"
                                        class="absolute top-2 right-2 bg-white text-gray-700 rounded-full px-3 py-1 text-xs cursor-pointer hover:bg-gray-200 hover:text-gray-800 transition shadow-md">
                                        Re-Upload File
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Show upload area if no screenshot exists -->
                        @if (!$existing_payment_screenshot && !$edit_payment_screenshot)
                            <label for="edit_payment_screenshot">
                                <div
                                    class="w-full px-4 py-8 border-2 border-dashed border-gray-300 text-center rounded-md text-gray-500 cursor-pointer hover:border-blue-400 transition-colors">
                                    <div class="mb-2">
                                        <i class="fas fa-upload text-2xl"></i>
                                    </div>
                                    <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span></p>
                                    <p class="text-xs text-gray-400 mt-1">Max: 2MB (JPEG, PNG, JPG)</p>
                                </div>
                            </label>
                        @endif

                        <!-- Loading Indicator -->
                        <div wire:loading wire:target="edit_payment_screenshot"
                            class="mt-2 text-gray-600 flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2 text-blue-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>

                        <!-- Error Message -->
                        @error('edit_payment_screenshot')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 font-semibold">Notes</label>
                        <textarea wire:model="edit_notes"
                            class="resize-none w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                            rows="3" placeholder="Optional notes about this payment"></textarea>
                        @error('edit_notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-between gap-3 mt-6">
                    <x-ghost-button wire:click="closeEditPaymentModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updatePayment">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal for Edit -->
        <div id="editImageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="relative modal-content">
                    <img id="editModalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                    <button type="button" onclick="closeEditModal()"
                        class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                        <span class="leading-none translate-y-[-3px]">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-------------------------- END OF MODALS ---------------------------------->

</div>

<script>
    function openEditModal(imageSrc) {
        document.getElementById('editModalImg').src = imageSrc;
        document.getElementById('editImageModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editImageModal').classList.add('hidden');
    }

    // Close modal when clicking outside the image
    document.getElementById('editImageModal').addEventListener('click', function(e) {
        if (e.target.id === 'editImageModal') {
            closeEditModal();
        }
    });
</script>


{{-- <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Invoice</h2>

    <div class="mb-4">
        <h3 class="text-lg font-semibold">Accommodations</h3>
        <p><strong>Reservation ID:</strong> 6903442199036</p>
        <p><strong>Guest:</strong> Barbie Jalandoni</p>
        <p><strong>Room Type:</strong> Standard Room (Hot Sale Promo)</p>
    </div>

    <h3 class="text-lg font-semibold mb-2">Reservation Details</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Arrival - Departure</th>
                <th class="border px-4 py-2">Adults</th>
                <th class="border px-4 py-2">Children</th>
                <th class="border px-4 py-2">Nights</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">06/02/2025 - 06/03/2025</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">0</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 2,772.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="4">Total</td>
                <td class="border px-4 py-2">PHP 2,475.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Additional Products</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">Thai Body Massage - 1HR</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="2">Total</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Summary</h3>
    <table class="min-w-full border-collapse text-sm">
        <tbody>
            <tr>
                <td class="border px-4 py-2 font-semibold">Subtotal</td>
                <td class="border px-4 py-2 text-right">PHP 2,475.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Deposit</td>
                <td class="border px-4 py-2 text-right">PHP 1,761.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Additional Products</td>
                <td class="border px-4 py-2 text-right">PHP 750.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">VAT</td>
                <td class="border px-4 py-2 text-right">PHP 297.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2">Amount Paid</td>
                <td class="border px-4 py-2 text-right">PHP 0.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Grand Total</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Balance Due</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
        </tbody>
    </table>

    <p class="text-sm text-gray-600">
        <strong>Status:</strong> <span class="font-medium text-yellow-600">Unpaid</span>
    </p>
</div> --}}



{{-- <h2><strong>PAYMENT DETAILS:</strong></h2>
@if ($payments->isNotEmpty())
<table class="table-auto w-full border-collapse">
    <thead>
        <tr>
            <th class="border px-4 py-2">Payment ID</th>
            <th class="border px-4 py-2">Invoice ID</th>
            <th class="border px-4 py-2">Payment Screenshot</th>
            <th class="border px-4 py-2">Amount Paid</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td class="border px-4 py-2">{{ $payment->id }}</td>
            <td class="border px-4 py-2">{{ $payment->invoice_id }}</td>
            <td class="border px-4 py-2">
                {{-- Display the payment screenshot as an image --}}
{{-- @if ($payment->payment_screenshot)
                <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot"
                    style="max-width: 200px; max-height: 200px;">
                @else
                No payment screenshot available.
                @endif
            </td>
            <td class="border px-4 py-2">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No payments found for this invoice.</p>
@endif --}}



{{--
@if ($activityInCart)

<td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
    <button wire:click="RemoveActivity({{ $activity['activity_id'] }})"
        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
        Remove
    </button>
</td>

@else
<button wire:click="addActivityToCart({{ $activity->id }})"
    class="px-4 py-2 mt-auto flex bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
    wire:loading.attr="disabled">
    <div class="flex items-center justify-center">

        <!-- Spinner -->
        <span wire:loading wire:target="addActivityToCart({{ $activity->id }})" class="mr-2">
            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                </path>
            </svg>
        </span>

        <!-- Button Text -->
        <span wire:loading.remove wire:target="addActivityToCart({{ $activity->id }})">
            Add Activity
        </span>

    </div>
</button>
@endif --}}



<!-- Add Guest Modal -->
{{-- @if ($activeModal === 'guest-info')
<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div
        class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
        <div class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center">Enter Guest Details</h2>

            <!-- Close Button -->
            <button wire:click="closeModal"
                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="-translate-y-[2px]">&times;</span>
            </button>
        </div>

        <!-- Guest Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">First Name
                    <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="guestInfo.first_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" placeholder="Ex. Juan"
                    required>
                @error('guestInfo.first_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Middle Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Middle
                    Name</label>
                <input type="text" wire:model.defer="guestInfo.middle_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                    placeholder="Ex. Mercado">
                @error('guestInfo.middle_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Last Name
                    <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="guestInfo.last_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" required
                    placeholder="Ex. Dela Cruz">
                @error('guestInfo.last_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Suffix -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                <input type="text" wire:model.defer="guestInfo.suffix" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                    placeholder="Ex. Jr., Sr., III">
                @error('guestInfo.suffix')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <!-- Transaction Property -->
        <div class="mt-4">
            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Room <span
                    class="text-red-500">*</span></label>
            <select wire:model.live="guestInfo.transaction_property_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                <option value="">Select Residency</option>
                <option value="local">Local</option>
                <option value="foreigner">Foreigner</option>
            </select>
            @error('editingResidency')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Country of Origin -->
        <div class="mt-4">
            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Country of
                Origin <span class="text-red-500">*</span></label>
            <input type="text" wire:model="editingCountryOfOrigin" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                placeholder="Ex. Philippines">
            @error('editingCountryOfOrigin')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-between mt-4">
            <x-ghost-button type="button" wire:click="$set('showEditGuestModal', false)">
                Cancel
            </x-ghost-button>
            <x-button type="button" wire:click="updateGuest" wire:loading.attr="disabled">
                <div class="flex items-center justify-center">
                    <!-- Spinner -->
                    <span wire:loading class="mr-2" wire:target="updateGuest">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                            </path>
                        </svg>
                    </span>

                    <!-- Button Text -->
                    <span wire:loading.remove wire:target="updateGuest">
                        Save Changes
                    </span>
                </div>
            </x-button>
        </div>

    </div>
</div>
@endif

<!--  Edit Pet Breed Modal -->
@if ($showEditPetModal)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center">Edit Pet Breed</h2>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Breed</label>
            <input type="text" wire:model="editingBreed"
                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600"
                placeholder="Enter pet breed">

            @error('editingBreed')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between mt-3">
            <x-ghost-button wire:click="$set('showEditPetModal', false)">
                Cancel
            </x-ghost-button>
            <x-button wire:click="updatePet">
                Update
            </x-button>
        </div>
    </div>
</div>
@endif





<!-------------------------- END OF MODALS ---------------------------------->


<!-- Back Button -->
<div class="justify-end flex">
    <x-button onclick="history.back()" icon="fas fa-arrow-left"
        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
        Back
    </x-button>
</div>
</div>
</div>
</div>
</div>



{{-- <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Invoice</h2>

    <div class="mb-4">
        <h3 class="text-lg font-semibold">Accommodations</h3>
        <p><strong>Reservation ID:</strong> 6903442199036</p>
        <p><strong>Guest:</strong> Barbie Jalandoni</p>
        <p><strong>Room Type:</strong> Standard Room (Hot Sale Promo)</p>
    </div>

    <h3 class="text-lg font-semibold mb-2">Reservation Details</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Arrival - Departure</th>
                <th class="border px-4 py-2">Adults</th>
                <th class="border px-4 py-2">Children</th>
                <th class="border px-4 py-2">Nights</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">06/02/2025 - 06/03/2025</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">0</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 2,772.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="4">Total</td>
                <td class="border px-4 py-2">PHP 2,475.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Additional Products</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">Thai Body Massage - 1HR</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="2">Total</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Summary</h3>
    <table class="min-w-full border-collapse text-sm">
        <tbody>
            <tr>
                <td class="border px-4 py-2 font-semibold">Subtotal</td>
                <td class="border px-4 py-2 text-right">PHP 2,475.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Deposit</td>
                <td class="border px-4 py-2 text-right">PHP 1,761.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Additional Products</td>
                <td class="border px-4 py-2 text-right">PHP 750.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">VAT</td>
                <td class="border px-4 py-2 text-right">PHP 297.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2">Amount Paid</td>
                <td class="border px-4 py-2 text-right">PHP 0.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Grand Total</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Balance Due</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
        </tbody>
    </table>

    <p class="text-sm text-gray-600">
        <strong>Status:</strong> <span class="font-medium text-yellow-600">Unpaid</span>
    </p>
</div> --}}



{{-- <h2><strong>PAYMENT DETAILS:</strong></h2>
@if ($payments->isNotEmpty())
<table class="table-auto w-full border-collapse">
    <thead>
        <tr>
            <th class="border px-4 py-2">Payment ID</th>
            <th class="border px-4 py-2">Invoice ID</th>
            <th class="border px-4 py-2">Payment Screenshot</th>
            <th class="border px-4 py-2">Amount Paid</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td class="border px-4 py-2">{{ $payment->id }}</td>
            <td class="border px-4 py-2">{{ $payment->invoice_id }}</td>
            <td class="border px-4 py-2">
                {{-- Display the payment screenshot as an image --}}
{{-- @if ($payment->payment_screenshot)
                <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot"
                    style="max-width: 200px; max-height: 200px;">
                @else
                No payment screenshot available.
                @endif
            </td>
            <td class="border px-4 py-2">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No payments found for this invoice.</p>
@endif --}}



{{--
@if ($activityInCart)

<td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
    <button wire:click="RemoveActivity({{ $activity['activity_id'] }})"
        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
        Remove
    </button>
</td>

@else
<button wire:click="addActivityToCart({{ $activity->id }})"
    class="px-4 py-2 mt-auto flex bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
    wire:loading.attr="disabled">
    <div class="flex items-center justify-center">

        <!-- Spinner -->
        <span wire:loading wire:target="addActivityToCart({{ $activity->id }})" class="mr-2">
            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                </path>
            </svg>
        </span>

        <!-- Button Text -->
        <span wire:loading.remove wire:target="addActivityToCart({{ $activity->id }})">
            Add Activity
        </span>

    </div>
</button>
@endif --}}



<!-- Add Guest Modal -->
{{-- @if ($activeModal === 'guest-info')
<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div
        class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
        <div class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
            <!-- Title -->
            <h2 class="text-2xl font-bold text-center">Enter Guest Details</h2>

            <!-- Close Button -->
            <button wire:click="closeModal"
                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="-translate-y-[2px]">&times;</span>
            </button>
        </div>

        <!-- Guest Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">First Name
                    <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="guestInfo.first_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" placeholder="Ex. Juan"
                    required>
                @error('guestInfo.first_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Middle Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Middle
                    Name</label>
                <input type="text" wire:model.defer="guestInfo.middle_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                    placeholder="Ex. Mercado">
                @error('guestInfo.middle_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Last Name
                    <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="guestInfo.last_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" required
                    placeholder="Ex. Dela Cruz">
                @error('guestInfo.last_name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Suffix -->
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                <input type="text" wire:model.defer="guestInfo.suffix" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                    placeholder="Ex. Jr., Sr., III">
                @error('guestInfo.suffix')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <!-- Transaction Property -->
        <div class="mt-4">
            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Room <span
                    class="text-red-500">*</span></label>
            <select wire:model.live="guestInfo.transaction_property_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                <option value="">Select Room</option>
                @foreach ($transactionProperties as $property)
                <option value="{{ $property->id }}">
                    {{ $property->property->name_number ?? 'Property #' . $property->id }} - {{
                    $property->property->extra_person_charge }}
                </option>
                @endforeach
            </select>
            @error('guestInfo.transaction_property_id') <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>


        <!-- Guest Type -->
        <div class="mb-4">
            <label class="block text-sm text-gray-700 dark:text-gray-200">Guest Type</label>
            <select wire:model.defer="guestInfo.guest_type_id"
                class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Select Guest Type</option>
                @foreach ($filteredGuestTypesByAvailability as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                @endforeach
            </select>
            @error('guestInfo.guest_type_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        @if (isset($guest['age'], $guest['category']) && $guest['age'] <= 2 && $guest['category'] === 'Kid-Free') <span
            class="text-sm text-gray-500">(Free of charge)</span>
            @endif


            <!-- Gender -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Gender <span
                        class="text-red-500">*</span></label>
                <select wire:model.defer="guestInfo.gender" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Prefer not to say</option>
                </select>
                @error('guestInfo.gender')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Residency -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Residency <span
                        class="text-red-500">*</span></label>
                <select wire:model.defer="guestInfo.residency" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                    <option value="">Select Residency</option>
                    <option value="local">Local</option>
                    <option value="foreigner">Foreigner</option>
                </select>
                @error('guestInfo.residency')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country of Origin -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Country of
                    Origin <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="guestInfo.country_of_origin" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                    placeholder="Ex. Philippines">
                @error('guestInfo.country_of_origin')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-between mt-4">
                <x-ghost-button type="button" wire:click="closeModal">
                    Cancel
                </x-ghost-button>
                <x-button type="button" wire:click="saveGuestInfoDetails" wire:loading.attr="disabled">
                    <div class="flex items-center justify-center">
                        <!-- Spinner -->
                        <span wire:loading class="mr-2" wire:target="saveGuestInfoDetails">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                        </span>

                        <!-- Button Text -->
                        <span wire:loading.remove wire:target="saveGuestInfoDetails">
                            Save Guest
                        </span>


                    </div>
                </x-button>
            </div>
    </div>
</div>
@endif --}}


{{-- @if ($isFull)
<!-- Fill Reserved Guest Info (non-billable) -->
<x-button wire:click="openModal('guest-info')" icon="fas fa-id-card">
    Fill Guest Info
</x-button>
@endif --}}
