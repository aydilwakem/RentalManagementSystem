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


            <!---------------------------- EXPORT DETAILS ---------------------------------------->
            <x-button wire:click="exportReservationDetails">
                <!-- Spinner -->
                <span wire:loading wire:target="exportReservationDetails" class="mr-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
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
                    <x-button wire:click="openModal('guest')" icon="fas fa-user-plus">
                        Add Guest
                    </x-button>
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
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucfirst($guestDetail->guestType->name) ?? 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucfirst($guestDetail->gender) ?? 'N/A' }}
                                </td>
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucfirst($guestDetail->residency) }}</td>
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucfirst($guestDetail->country_of_origin) }}</td>
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{
                                    ucfirst(optional(optional($guestDetail->transactionProperty)->property)->name_number)
                                    }}
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
                    <div class="col-span-full">
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
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                            </span>
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
                        </div>
                    </div>
                    <div>
                        <strong>Transaction ID:</strong>
                        <div>{{ $transaction->transaction_number }}</div>
                    </div>
                    <div>
                        <strong>Reservation Created At:</strong>
                        <div>{{ $transaction->created_at->format('F j, Y') }} at
                            {{ $transaction->created_at->format('g:i A') }}</div>
                    </div>
                    <div>
                        <strong>Heard From:</strong>
                        <div>{{ $transaction->heard_from }}</div>
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
                        <strong>Convenience Fee (3%):</strong>
                        <div>₱{{ number_format($transaction->convenience_fee, 2) }}</div>
                    </div>

                    <div>
                        <strong>Promo Applied:</strong>
                        <div>
                            {{ $transaction->promoCode->code ?? '' }}

                            @if ($transaction->promoCode)
                            <div>
                                <strong>Promo Code:</strong>
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
                </div>

        
                <div class="mt-4">
                    <strong>Requests:</strong>

                    @if(!empty($transaction->requests))
                        <div class="mt-2">
                            <span class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg shadow-sm">
                                {{ Str::limit($transaction->requests, 50) }}
                            </span>

                                 <x-button wire:click="openModal('requests')" icon="fas fa-comment-dots">
                                    View & Reply
                                </x-button>

                        </div>
                    @else
                        <div class="text-gray-500 italic mt-2">
                            No requests.
                        </div>
                    @endif
                </div>

            </div>
            <!------------------------- END OF TRANSACTION DETAILS ----------------------------->



            <!---------------------------- ROOM DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
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
                                    Total Guest
                                </th>
                                <th
                                    class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                    Ideal Guests</th>
                                <th
                                    class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                    Extra Guests</th>
                                <th
                                    class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                    Extra Guest Charge
                                </th>
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
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucfirst($property->name_number) }}</td>
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
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
                                    {{ $property->pivot->pax ?? 'N/A' }}</td>
                                <td
                                    class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                    {{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                                <td
                                    class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                    ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                <td
                                    class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                    {{ $property->pivot->days ?? 'N/A' }} day(s)</td>
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
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
                                    class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">
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
                                    class="border px-4 py-2 text-gray-700 text-left dark:text-gray-200 dark:border-gray-500">
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
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
                                    @if ($service->type == "addon")
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-2 dark:text-green-300">
                        {{ __('Guest Pet Information') }}
                    </h2>
                    <!-- Add Pet Details -->
                    <x-button wire:click="openModal('pet')" icon="fas fa-paw">
                        Add Pet
                    </x-button>
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
                                <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    {{ ucwords($pet->breed) }}</td>
                                <td
                                    class="border px-4 py-2 text-center text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                    @if ($pet->vaccination_card)
                                    <a href="{{ asset('storage/' . $pet->vaccination_card) }}" target="_blank"
                                        class="text-blue-600 underline">View</a>
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
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
                            {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not
                            yet set' }}
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
                            @elseif ($invoice->invoice_status === 'complete' || $invoice->invoice_status ===
                            'completed')
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
                            {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y')
                            : 'Not yet completed' }}
                        </div>
                    </div>
                </div>
                <hr class="py-2 mt-4">
                <!-- Items Table -->
                <div class="flex justify-between">
                    <x-button wire:click="openModal('activity')" icon="fas fa-plus">
                        Add Activity
                    </x-button>

                    <x-button wire:click="openModal('service')" icon="fas fa-plus">
                        Add Other Charges
                    </x-button>
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
                                    {{ $item['quantity'] }}</td>
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
                                    @if ($item['payment_status'] !== 'paid' && $item['payment_status'] !== 'partial')
                                    @if ($item['type'] == 'property')
                                    <button wire:click="editRoom({{ $property->pivot->id }})"
                                        class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @else
                                    <button wire:click="
                                                        @if ($item['type'] === 'activity') editActivity({{ $item['pivot_id'] }})
                                                        @elseif($item['type'] === 'service') editService({{ $item['pivot_id'] }}) @endif
                                                    "
                                        class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="
                                                            @if ($item['type'] === 'activity') deleteActivity({{ $item['pivot_id'] }})
                                                            @elseif($item['type'] === 'service') deleteService({{ $item['pivot_id'] }}) @endif
                                                        "
                                        class="text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-600"
                                        title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                    @else
                                    <span class="text-gray-400 italic"><i class="fas fa-lock mr-1"></i></span>
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
                                    {{ $item['days'] }} </td>
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
                                <td colspan="3" class="border px-4 py-2 text-center dark:border-gray-500"></td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                  

                    <!-- Subtotal -->
                    <div class="mt-2 mb-1 flex justify-between font-semibold text-base text-gray-700">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($this->computeBaseSubtotal(), 2) }}</span>
                    </div>


                    <!-- Discounts applied -->
@if($invoice->discounts->count() > 0)
    <div class="mb-1 text-gray-600 text-sm border-t pt-2">
        @php
            $baseSubtotal = $invoice->base_subtotal;
            $pax = $transaction->pax ?: 1; // fallback to 1 to avoid division by zero
        @endphp

        @foreach($invoice->discounts->groupBy('discount_type_id') as $discounts)
            @php
                $type = $discounts->first()->discountType;
                $count = $discounts->sum('quantity'); 
                $totalValue = $discounts->sum('discount_value'); 
                $perPersonAmount = $type->type === 'percent' ? ($baseSubtotal / $pax) * ($type->rate / 100) : null;
            @endphp

            <div class="flex justify-between items-center mb-1">
                <span>
                    - {{ strtoupper($type->name) }} x {{ $count }}
                    @if($type->type === 'percent' && $perPersonAmount)
                        ({{ $type->rate }}% of Subtotal ÷ {{ $pax }} pax)
                    @else
                        (Fixed)
                    @endif
                </span>

                <div class="flex items-center space-x-2">
                    <span>- ₱{{ number_format($totalValue, 2) }}</span>

                    <!-- Optional: tooltip to show exact formula -->
                    {{-- @if($type->type === 'percent' && $perPersonAmount)
                        <span class="text-xs text-gray-400" title="Calculation: (Subtotal ÷ Pax) × Rate × Qty">
                            (~₱{{ number_format($perPersonAmount * $count, 2) }})
                        </span>
                    @endif --}}

                    <button 
                        wire:click="removeDiscount({{ $invoice->id }}, {{ $type->id }})" 
                        class="text-red-500 hover:text-red-700 text-xs"
                        title="Remove discount"
                    >
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
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
                    <div class="flex justify-between font-semibold text-base mt-2 text-gray-700">
                        Promo Applied:
                        <div>
                            {{ $transaction->promoCode->code ?? '' }}

                            @if ($transaction->promoCode && $transaction->promoCode->discount_type == 'percentage')
                            ({{ number_format($transaction->promoCode->discount_value, 0) }}%)
                            @elseif ($transaction->promoCode)
                            {{-- Flat discount --}}
                            (₱{{ number_format($transaction->promoCode->discount_value, 2) }})
                            @endif

                            - ₱{{ number_format($transaction->promo_discount_amount, 2) }}
                        </div>
                    </div>
                    @endif



                    @if($transaction->promoCode)
                    <!-- Sub Total with discount -->
                    <div class="flex justify-between font-semibold text-base mt-2 text-gray-700">
                        Subtotal after discount:
                        <div>
                            ₱{{ number_format($this->computeBaseSubtotalAfterDiscount(), 2) }}
                        </div>
                    </div>
                    @endif
                    
                    
                     @if ($this->computeConvenienceFeeTotal() > 0)
                    <!-- Convenience Fee -->
                    <div class="flex justify-between font-semibold text-base mb-2 text-gray-700">
                        Convenience Fee:
                        <div>
                            ₱{{ number_format($this->computeConvenienceFeeTotal(), 2) }}
                        </div>
                    </div>
                    @endif



                
                    <hr>



                    <!-- Grand Total -->
                    <div class="flex justify-between font-bold text-base mt-2 text-green-700">
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

                    <!-- Balance Due -->
                    <div
                        class="flex justify-between font-semibold text-base
                            {{ $this->invoice->amount_paid == $this->invoice->sub_total ? 'text-green-700' : 'text-red-500' }}">
                        Balance Due:
                        <div>
                            ₱{{ number_format($this->invoice->balance_due, 2) }}
                        </div>
                    </div>
                </div>

                {{-- Add Item Button Row --}}


                <!------------------------  REQUEST REMAINING BALANCE ------------------------------------->
                <div class="flex justify-center">
                    @if ($invoice->balance_due > 0 && !$invoice->requested_remaining_balance)
                    <x-button wire:click="requestRemainingBalance" wire:loading.attr="disabled" class="mt-6">
                        <div class="flex items-center justify-center">
                            <!-- Spinner -->
                            <span wire:loading class="mr-2" wire:target="requestRemainingBalance">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
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
                <!--------------------  END OF REQUEST REMAINING BALANCE ---------------------------------->

                <!------------------------  ADD PWD/SENIOR DISCOUNT ------------------------------------->
@if (!$this->discountsApplied)
    <x-button wire:click="openModal('discounts')" icon="fas fa-percent">
        Add PWD/SENIOR DISCOUNT
    </x-button>
@endif
<!--------------------  END OF PWD/SENIOR DISCOUNT ---------------------------------->

            </div>
            @else
            <p class="text-gray-600 italic">No invoice found for this transaction.</p>
            @endif
            <!------------------------  END OF INVOICE ----------------------------------------->




            <!------------------------- GENERATE RECEIPT ---------------------------------->
            @if ($transaction->transaction_status == 'done')
            <div>
                @if (is_null($transaction->invoice->receipt))
                <!-- Show this if receipt does NOT exist -->
                <x-button wire:click="GenerateReceipt" wire:loading.attr="disabled" wire:target="GenerateReceipt">

                    <!-- Show spinner and text while loading -->
                    <span wire:loading wire:target="GenerateReceipt" class=" items-center gap-2">
                        <span>Generating...</span>
                    </span>

                    <!-- Show default text when not loading -->
                    <span wire:loading.remove wire:target="GenerateReceipt">
                        <i class="fas fa-receipt"></i>
                        Generate Official Receipt
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
            <!---------------------- END OF GENERATE RECEIPT ------------------------------>


            <!----------------------------- PAYMENTS -------------------------------------->
            <section id="payments">
                <div
                    class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                    <div class="justify-between flex items-center">
                        <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                            Payments (₱{{ number_format($this->invoice->amount_paid, 2) }})
                        </h2>
                        <div class="text-left mb-4 flex items-center gap-2">
                         
                            <x-button wire:click="OpenCreatePaymentModal">
                                <i class="fas fa-plus mr-2"></i>
                                Create Payment
                            </x-button>
                        </div>
                    </div>
                    @if ($payments->isNotEmpty())
                    <div class="">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        ID</th>
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
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600 ">
                                @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->id }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->invoice->invoice_number }}</td>
                                         <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->paymentMethod?->mode_of_payment_name ?? 'N/A' }}</td>
                                    <td
                                        class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 leading-tight">
                                        <div class="font-semibold">
                                            ₱{{ number_format($payment->amount_paid, 2) }}
                                        </div>
                                        @if ($payment->convenience_fee)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">
                                            (with ₱{{ number_format($payment->convenience_fee, 2) }} convenience fee)
                                        </div>
                                        @endif

                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ ucfirst($payment->payment_type) }}</td>
                                    <td
                                        class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 break-words max-w-[96px]">
                                        {{ $payment->payment_reference_number ?? 'N/A' }}
                                    </td>

                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->payment_date ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-500">
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->notes ?? '-' }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ $payment->verified_at ?? 'To be verified' }}</td>
                                    <td class="border px-4 py-2 space-x-2 dark:text-gray-200 dark:border-gray-500">
                                        @if (!$payment->payment_screenshot)
                                        @if ($payment->mode_of_payment === 'cash')
                                        <span class="text-gray-500 italic dark:text-gray-200">Cash
                                            Payment (no receipt uploaded)</span>
                                        @else
                                        <span class="text-gray-500 italic dark:text-gray-200">Completed via secure
                                            online payment</span>
                                        @endif
                                        @else
                                        @if ($payment->payment_status === 'pending')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block bg-yellow-100 hover:bg-yellow-200 text-yellow-500 font-semibold text-center py-2 px-3 rounded text-xs">
                                            Verify Receipt
                                        </a>
                                        @elseif ($payment->payment_status === 'completed' || $payment->payment_status
                                        === 'failed')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block py-1 px-2 rounded-md text-xs font-semibold bg-green-100 text-green-500 text-center hover:bg-green-200 hover:text-green-600">
                                            View Receipt
                                        </a>
                                        @endif
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





            <!---------------------------- MODALS ---------------------------------------->

            <!-- Show Receipt Modal -->
            @if ($showReceiptModal && $receipt)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Acknowledgement Receipt</h2>
                        <button wire:click="$set('showReceiptModal', false)"
                            class="flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 text-gray-600 hover:bg-red-100 hover:text-red-600 transition duration-200 text-2xl ">
                            <span class="leading-none translate-y-[-3px]">&times;</span>
                        </button>

                    </div>

                    {{-- Display Session Message --}}
                    @if (session('message'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                        {{ session('message') }}
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="px-6 py-5 space-y-4 text-gray-700 text-sm">
                        <div class="flex justify-between">
                            <span class="font-semibold">Receipt Number:</span>
                            <span class="text-gray-900">{{ $receipt->receipt_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Receipt Date:</span>
                            <span class="text-gray-900">{{ $receipt->receipt_date->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Invoice Number:</span>
                            <span class="text-gray-900">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Guest:</span>
                            <span class="text-gray-900">
                                {{ $transaction->transactionUser->first_name ?? 'N/A' }}
                                {{ $transaction->transactionUser->last_name ?? '' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Amount Received:</span>
                            <span class="text-green-600 font-semibold">₱{{ number_format($receipt->amount_received, 2)
                                }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Notes:</span>
                            <p class="mt-1 text-gray-600 italic">{{ $receipt->notes ?? 'None' }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-gray-200">

                        <!-- Print Receipt Button -->
                        <x-button wire:click="printOfficialReceipt" wire:loading.attr="disabled">
                            <div class="flex items-center justify-center">
                                <!-- Spinner -->
                                <span wire:loading class="mr-2" wire:target="printOfficialReceipt">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                </span>

                                <i class="fas fa-print mr-2" wire:loading.remove wire:target="printOfficialReceipt"></i>

                                <!-- Button Text -->
                                <span wire:loading.remove wire:target="printOfficialReceipt">
                                    Print Receipt
                                </span>
                            </div>
                        </x-button>

                        <!-- Send to Email Button -->
                        <x-warning-button wire:click="sendReceiptToEmail" wire:loading.attr="disabled">
                            <div class="flex items-center justify-center">
                                <!-- Spinner -->
                                <span wire:loading class="mr-2" wire:target="sendReceiptToEmail">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                </span>

                                <i class="fas fa-envelope mr-2" wire:loading.remove
                                    wire:target="sendReceiptToEmail"></i>

                                <!-- Button Text -->
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
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Add Payment</h2>

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
                                <option value="Room Rent">Room Rent</option>
                                <option value="Security Deposit">Security Deposit</option>
                                <option value="Remaining Balance">Remaining Balance</option>
                                <option value="Merchandise">Merchandise</option>
                            </select>
                            @error('payment_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                          {{-- Payment Methods --}}
                        <div>
                            <label for="payment_method_id" class="block mb-2 text-sm font-medium text-gray-900">Payment
                                Method <span class="text-red-500">*</span></label>
                            <select wire:model="payment_method_id" id="payment_method_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <option value="">Select Payment Method</option>
                                @foreach ($payment_methods as $payment_method)
                                    <option value="{{ $payment_method->id }}">{{ $payment_method->mode_of_payment_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>



                          {{-- Upload Payment Screenshot --}}
            <div class="sm:col-span-2">
                <label for="payment_screenshot" class="block mb-2 text-sm font-medium text-gray-900">Proof of
                    Payment <span class="text-red-500">*</span></label>

                <!-- Hidden file input -->
                <input id="payment_screenshot" type="file" accept="image/*" wire:model="payment_screenshot"
                    class="hidden">

                @if ($payment_screenshot && method_exists($payment_screenshot, 'temporaryUrl'))
                <!-- Show image preview -->
                <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden">
                    <img src="{{ $payment_screenshot->temporaryUrl() }}" class="w-full h-full object-cover"
                        alt="Payment Screenshot Preview"
                        onclick="openModal('{{ $payment_screenshot->temporaryUrl() }}')">

                    <label for="payment_screenshot"
                        class="absolute top-1 right-1 bg-white text-gray-700 rounded-full px-1 text-xs cursor-pointer hover:bg-gray-200 hover:text-gray-800 transition">
                        Re-Upload File
                    </label>
                </div>
                <!-- Image Popup View -->
                <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class=" relative modal-content">
                            <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
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
                        <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span></p>
                    </div>
                </label>
                @endif

                <!-- Loading Indicator -->
                <div wire:loading wire:target="payment_screenshot" class="mt-2 text-gray-600 flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
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
                                        {{ $service->name }}</h3>
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3 dark:text-gray-300">
                                        {{ $service->description ?? 'No description provided for this service.' }}
                                    </p>

                                    <div class="text-lg font-bold text-green-700 dark:text-green-300">
                                        @if($service->id == 10)
                                                <span class="text-lg font-bold text-green-700 dark:text-green-300">Depends on room rate</span>
                                                <span class="text-base font-normal text-gray-500 dark:text-gray-400">/ {{ $service->unit }}</span>
                                            @else
                                                ₱{{ number_format($service->amount, 2) }}
                                                <span class="text-base font-normal text-gray-500 dark:text-gray-400">/ {{ $service->unit }}</span>
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
                                                            {{ $property->name_number }} (₱{{ number_format($property->extra_charge_per_hour, 2) }})
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
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200 mb-5 flex flex-col md:flex-row
                                        dark:bg-gray-700 dark:border-gray-600">

                            <!-- Activity Image -->
                            @php
                            $firstImage = is_array(json_decode($activity->image))
                            ? json_decode($activity->image)[0] ?? null
                            : null;
                            @endphp

                            <div class="md:w-1/2">
                                <img src="{{ asset($firstImage ? 'storage/' . $firstImage : 'images/rms-default.png') }}"
                                    alt="{{ $activity->name }}" class="object-cover w-62 h-62">
                            </div>

                            <div class="md:w-1/2 p-4 flex flex-col justify-between">
                                <div>
                                    {{-- Activity Name --}}
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                                        {{ $activity->name }}</h2>

                                    {{-- Activity Description --}}
                                    <p class="text-gray-600 text-sm mb-2 text-justify dark:text-gray-200">
                                        {{-- Show more / less when description is long --}}
                                        @if (empty($activity->description))
                                        <span class="italic text-gray-400">No description provided</span>
                                        @elseif ($expandedActivity === $activity->id)
                                        {{ $activity->description }}
                                        <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                            class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                            less</a>
                                        @else
                                        {{ Str::limit($activity->description, 100, '...') }}
                                        @if (Str::length($activity->description) > 100)
                                        <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                            class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                            more</a>
                                        @endif
                                        @endif
                                    </p>

                                    {{-- Activity Amount --}}
                                    <div class="text-lg font-semibold text-green-600 dark:text-green-300">
                                        @if ($activity->amount == 0)
                                        <span class="text-green-600 font-semibold">FREE</span>
                                        @else
                                        ₱{{ number_format($activity->amount, 2) }}
                                        @endif
                                    </div>
                                </div>


                                {{-- ------------ QUANTITY COUNTER AND ADD/REMOVE ACTIVITY BUTTONS ------------- --}}
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                                    <!-- Quantity Counter -->
                                    <div class="flex flex-col">
                                        <label for="quantity-{{ $activity->id }}"
                                            class="text-sm font-medium text-gray-700 mb-1">
                                            Quantity:
                                        </label>
                                        <div class="flex items-center">
                                            <button wire:click="decrementItemQuantity('activity', {{ $activity->id }})"
                                                class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</button>

                                            <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                                {{ $quantity[$activity->id] ?? 1 }}
                                            </span>

                                            <button wire:click="incrementItemQuantity('activity', {{ $activity->id }})"
                                                class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">+</button>

                                            <input type="hidden" wire:model="quantity.{{ $activity->id }}">
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

                                    <div class="sm:mt-6">
                                        <x-button
                                            wire:click="{{ $activityInCart ? 'removeItemFromCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                            class="w-full sm:w-auto px-4 py-2 {{ $activityInCart ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150 uppercase"
                                            wire:loading.attr="disabled">
                                            <div class="flex items-center justify-center">
                                                <span wire:loading
                                                    wire:target="{{ $activityInCart ? 'addItemToCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                                    class="mr-2">
                                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4" />
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

                                <!-- Preferred Time -->
                                @if ($activity->schedule_type !== 'no_schedule')
                                <div class="mt-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">Preferred Time
                                    </h3>

                                    @if ($activity->schedule_type === 'system')
                                    @if (is_array($activity->available_times) && count($activity->available_times))
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach ($activity->available_times as $time)
                                        <label
                                            class="flex items-center p-2 bg-white dark:bg-gray-700 border border-gray-300 rounded cursor-pointer shadow-sm hover:border-green-500">
                                            <input type="radio" name="selected_time_{{ $activity->id }}"
                                                wire:model="selectedTimes.{{ $activity->id }}" value="{{ $time }}"
                                                class="form-radio text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm text-gray-800 dark:text-gray-200">
                                                {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-sm text-gray-500 italic">No system-defined schedule for this
                                        activity.</p>
                                    @endif

                                    @elseif ($activity->schedule_type === 'guest')
                                    <input type="time" wire:model.lazy="selectedTimes.{{ $activity->id }}"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                    @endif

                                    @error("selectedTimes.{$activity->id}")
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

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
                            <input type="text" wire:model.defer="guest.first_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                            <input type="text" wire:model.defer="guest.middle_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                            <input type="text" wire:model.defer="guest.last_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" required
                                placeholder="Ex. Dela Cruz">
                            @error('guest.last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                            <input type="text" wire:model.defer="guest.suffix" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500"
                                placeholder="Ex. Jr., Sr., III">
                            @error('guest.suffix')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Transaction Property -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Room <span
                                class="text-red-500">*</span></label>
                        <select wire:model.defer="guest.transaction_property_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                    </div>

                    <!-- Birthdate -->
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Birthdate</label>
                        <input type="date" wire:model.live="guest.birthdate" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                        dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                        @error('guest.birthdate')
                        <span class="text-red-500 text-sm">
                            {{ $message == 'The guest.birthdate field is required.' ? 'Please enter the birthdate.' :
                            $message }}
                        </span>
                        @enderror
                    </div>


                    <!-- Guest Type -->
                    <!-- Optional: Guest Type (can be hidden or locked to a default) -->
                    {{-- If you want admin to skip selecting guest type, skip this field --}}
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Guest
                            Type</label>
                        <select wire:model.defer="guest.guest_type_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                            <option value="">Select Guest Type</option>
                            @foreach ($filteredGuestTypes as $type)
                            <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        @error('guest.guest_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if (isset($guest['age'], $guest['category']) && $guest['age'] <= 2 &&
                        $guest['category']==='Kid-Free' ) <span class="text-sm text-gray-500">(Free of charge)</span>
                        @endif


                        <!-- Gender -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Gender <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.defer="guest.gender" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                            <select wire:model.defer="guest.residency" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                            <select wire:model="guest.country_of_origin" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
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
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
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


            {{-- Add Discounts Modal --}}
            @if ($activeModal === 'discounts')
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div
                    class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                    
                    {{-- Header --}}
                    <div
                        class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                        <h2 class="text-2xl font-bold text-center">Add PWD/Senior Discounts</h2>
                        <button wire:click="closeModal"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                            <span class="-translate-y-[2px]">&times;</span>
                        </button>
                    </div>

                    {{-- Form Fields --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium mb-1">Number of PWDs</label>
                            <input type="number" min="0" wire:model="pwdCount"
                                class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Number of Seniors</label>
                            <input type="number" min="0" wire:model="seniorCount"
                                class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-between mt-6">
                        <x-ghost-button wire:click="closeModal">
                            Cancel
                        </x-ghost-button>
                        <x-button wire:click="applyDiscounts">
                            Save Changes
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
                        <strong>Guest Request:</strong>
                        <p class="mt-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 p-3 rounded-lg">
                            {{ $transaction->requests ?? 'No request submitted.' }}
                        </p>
                    </div>

                    <!-- Admin Reply -->
                    <div class="mb-4">
                        <strong>Admin Reply:</strong>
                        <textarea wire:model.defer="request_reply"
                            class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm mt-2"
                            rows="3"
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
                                <input type="radio" wire:model="editingActivityDateTime" value="{{ $option }}"
                                    class="form-radio text-green-600">
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
                            <input type="text" wire:model="editingFirstName" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" placeholder="Ex. Juan">
                            @error('editingFirstName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Middle
                                Name</label>
                            <input type="text" wire:model="editingMiddleName" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                            <input type="text" wire:model="editingLastName" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
                                    dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500" required
                                placeholder="Ex. Dela Cruz">
                            @error('editingLastName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Suffix</label>
                            <input type="text" wire:model="editingSuffix" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                        <select wire:model.defer="editingTransactionPropertyId" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200">Birthdate</label>
                        <input type="date" wire:model="editingBirthDate"
                            class="w-full border px-3 py-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Guest Type -->
                    <!-- Optional: Guest Type (can be hidden or locked to a default) -->
                    {{-- If you want admin to skip selecting guest type, skip this field --}}
                    @if (auth()->user()->role !== 'admin')
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Guest Type
                            <span class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuestTypeId" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                        <select wire:model="editingGender" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                        <label class="block text-sm text-gray-700 dark:text-gray-200 font-semibold">Residency <span
                                class="text-red-500">*</span></label>
                        <select wire:model="editingResidency" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 block p-2.5
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
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
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

        @if (isset($guest['age'], $guest['category']) && $guest['age'] <= 2 && $guest['category']==='Kid-Free' ) <span
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
