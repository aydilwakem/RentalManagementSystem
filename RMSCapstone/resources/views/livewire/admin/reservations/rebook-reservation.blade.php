<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Rebook Reservation') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Reservations', 'url' => route('admin.reservations-list')],
            ['label' => 'View Reservation', 'url' => route('admin.view-reservation', ['transaction' => $transaction->id])],
            ['label' => 'Rebook Reservation', 'url' => route('admin.rebook-reservation', ['transaction' => $transaction->id])],
        ]" />
    </x-slot>
@php
    Log::info('Rebook view rendering', [
        'has_activities' => isset($activities) ? $activities->count() : 'not set',
        'has_services' => isset($services) ? $services->count() : 'not set',
        'has_allItems' => isset($allItems) ? count($allItems) : 'not set',
    ]);
@endphp
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 space-y-6">

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Rebooking Header with Date Selection -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Select New Dates') }}
                </h2>

                <div class="flex flex-col md:flex-row items-end justify-center gap-4 mb-3">
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">New Check-In Date</label>
                        <input type="date" wire:model.live="new_check_in_date" min="{{ $min_date }}" max="{{ $max_date }}"
                            class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        @error('new_check_in_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>


                    <h1 class="mt-4 dark:text-white"><i class="fas fa-arrow-right"></i></h1>

                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">New Check-Out Date</label>
                        <input type="text" value="{{ $new_check_out_date ? \Carbon\Carbon::parse($new_check_out_date)->format('F j, Y') : 'N/A' }}"
                            readonly
                            class="w-full md:w-auto px-4 py-2 border rounded shadow-sm bg-gray-100 dark:bg-gray-600 dark:border-gray-500 dark:text-white cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Auto-calculated ({{ $stay_duration }} night stay)</p>
                    </div>

                    <div class="flex items-center ml-4">
                        @if($new_check_in_date && !$errors->has('availability'))
                            <span class="text-green-600 font-semibold">
                                <i class="fas fa-check-circle mr-1"></i> Available
                            </span>
                        @endif
                    </div>
                </div>

                @error('availability')
                    <div class="text-red-500 text-sm text-center mt-2">{{ $message }}</div>
                @enderror

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-4">
                    <x-ghost-button onclick="history.back()" icon="fas fa-arrow-left">
                        Back
                    </x-ghost-button>
                    
                    @if(!$showRebookSummary)
                        <x-button wire:click="confirmRebooking" :disabled="!$new_check_in_date || $errors->has('availability')">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Continue to Rebooking
                        </x-button>
                    @endif
                </div>
            </div>

            @if($showRebookSummary)
                <!-- Rebooking Summary Modal -->
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg dark:bg-gray-800">
                        <!-- Header -->
                        <div class="relative -mt-6 -mx-6 mb-4 bg-yellow-50 text-yellow-700 py-3 px-6 rounded-t-lg shadow-sm border-b dark:bg-yellow-900 dark:text-yellow-300">
                            <h2 class="text-2xl font-bold text-center">Confirm Rebooking</h2>
                            <button wire:click="cancelRebooking"
                                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="-translate-y-[2px]">&times;</span>
                            </button>
                        </div>

                        <!-- Summary Content -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                                <h3 class="font-semibold text-gray-800 mb-3 dark:text-white">Date Changes</h3>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Original:</span>
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($original_check_in)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($original_check_out)->format('M j, Y') }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">New:</span>
                                        <div class="font-medium text-green-600">{{ \Carbon\Carbon::parse($new_check_in_date)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($new_check_out_date)->format('M j, Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 p-4 rounded-lg dark:bg-yellow-900/20">
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Room rates will be recalculated based on the new dates. You can also change rooms below before confirming.
                                </p>
                            </div>

                            <div class="flex justify-between space-x-3">
                                <x-ghost-button wire:click="cancelRebooking">
                                    Cancel
                                </x-ghost-button>
                                <div class="space-x-2">
                                    <x-button wire:click="$set('showRebookSummary', false)">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Back
                                    </x-button>
                                    <x-button wire:click="executeRebooking" class="bg-yellow-600 hover:bg-yellow-700">
                                        <i class="fas fa-check mr-2"></i>
                                        Confirm Rebooking
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Guest Details -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Guest Details') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">
                    <div><strong>Guest Name:</strong> {{ $transactionUser->first_name }} {{ $transactionUser->middle_name }} {{ $transactionUser->last_name }} {{ $transactionUser->suffix }}</div>
                    <div><strong>Email:</strong> {{ $transactionUser->email }}</div>
                    <div><strong>Contact Number:</strong> {{ $transactionUser->contact_number }}</div>
                    <div><strong>Company Name:</strong> {{ $transactionUser->company_name ?? 'Not provided' }}</div>
                    <div><strong>Country:</strong> {{ $transactionUser->country ?? 'Not provided' }}</div>
                    <div><strong>Facebook Link:</strong> {{ $transactionUser->facebook_link ?? 'Not provided' }}</div>
                </div>
            </div>

            <!-- Additional Guests Details -->
            @if($guestDetails->isNotEmpty())
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Additional Guests Details') }}
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500 text-left">Full Name</th>
                                <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Type</th>
                                <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Gender</th>
                                <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Citizenship</th>
                                <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Country</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-600">
                            @foreach ($guestDetails as $guestDetail)
                                <tr>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 text-left">
                                        {{ $guestDetail->first_name }} {{ $guestDetail->middle_name }} {{ $guestDetail->last_name }} {{ $guestDetail->suffix }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ ucfirst($guestDetail->guestType->name) ?? 'N/A' }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ ucfirst($guestDetail->gender) ?? 'N/A' }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ ucfirst($guestDetail->residency) }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        {{ ucfirst($guestDetail->country_of_origin) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Transaction Details -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Transaction Details') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
                    <div class="col-span-full">
                        <strong>Transaction Status:</strong>
                        <div class="mt-1">
                            @if ($transaction->transaction_status === 'pending')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting Payment</span>
                            @elseif ($transaction->transaction_status === 'reserved')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending Verification</span>
                            @elseif ($transaction->transaction_status === 'receipt_verified')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment Verified</span>
                            @elseif ($transaction->transaction_status === 'confirmed')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                            @elseif ($transaction->transaction_status === 'ongoing')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going</span>
                            @elseif ($transaction->transaction_status === 'done')
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                            @endif
                            
                            @if ($transaction->is_rebooked)
                                <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-600 border border-purple-200 ml-2">
                                    <i class="fas fa-calendar-repeat mr-1"></i> Rebooked Reservation
                                </span>
                            @endif
                        </div>
                    </div>

                    <div><strong>Transaction ID:</strong> {{ $transaction->transaction_number }}</div>
                    <div><strong>Original Check-in:</strong> {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</div>
                    <div><strong>Original Check-out:</strong> {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</div>
                    <div><strong>Duration:</strong> {{ $stay_duration }} night(s)</div>
                    <div><strong>Total Guests:</strong> {{ $transaction->pax }}</div>
                    <div><strong>Total Pets:</strong> {{ $transaction->guestPets->sum('pet_count') }}</div>
                    
                    @if($transaction->promoCode)
                    <div><strong>Promo Applied:</strong> {{ $transaction->promoCode->code }}</div>
                    @endif
                </div>
            </div>

            <!-- Rooms Section with Change Room Buttons -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Rooms') }}
                </h2>
                
                @if($properties->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Room</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Total Guests</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Extra Guests</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Stay Duration</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Room Rate</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Total</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($properties as $property)
                                    @php
                                        $pivot = $property->pivot;
                                    @endphp
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->name_number }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $pivot->adults ?? 0 }} Adult(s)
                                            @if($pivot && $pivot->kids > 0), {{ $pivot->kids }} Kid(s)@endif
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $pivot->extra_guest ?? 0 }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $pivot->days ?? $stay_duration }} night(s)
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-right dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($pivot->amount ?? 0, 2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($pivot->total_amount ?? 0, 2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            @if($pivot && $pivot->id)
                                                <button wire:click="openChangeRoomModal({{ $pivot->id }})"
                                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-500"
                                                    title="Change Room">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Change
                                                </button>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed" title="Room data not available">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Change
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

<!-- Activities Section -->
@if(isset($activities) && $activities->isNotEmpty())
<div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
        {{ __('Activities') }}
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">Activity Name</th>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Scheduled Time</th>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Quantity</th>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-600">
                @foreach ($activities as $activity)
                    @php
                        // Safely access pivot data
                        $pivot = $activity->pivot ?? null;
                        $quantity = $pivot->quantity ?? 0;
                        $amount = $pivot->amount ?? 0;
                        $activityDatetime = $pivot->activity_datetime ?? null;
                    @endphp
                    <tr>
                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $activity->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                            @if($activityDatetime)
                                {{ \Carbon\Carbon::parse($activityDatetime)->format('g:i A') }}
                            @else
                                No schedule
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">{{ $quantity }}</td>
                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                            ₱{{ number_format($amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Services Section -->
@if(isset($services) && $services->isNotEmpty())
<div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
        {{ __('Additional Charges') }}
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">Charge Name</th>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Quantity</th>
                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-600">
                @foreach ($services as $service)
                    @php
                        $pivot = $service->pivot ?? null;
                        $quantity = $pivot->quantity ?? 0;
                        $amount = $pivot->amount ?? 0;
                    @endphp
                    <tr>
                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $service->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">{{ $quantity }}</td>
                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                            ₱{{ number_format($amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

            <!-- Guest Pets Section -->
            @if($guestPets->isNotEmpty())
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Guest Pet Information') }}
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">Pet Breed</th>
                                <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Vaccination Card</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-600">
                            @foreach ($guestPets as $pet)
                                <tr>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ ucwords($pet->breed) }}</td>
                                    <td class="border px-4 py-2 text-center text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                        @if ($pet->vaccination_card)
                                            <a href="{{ asset('storage/' . $pet->vaccination_card) }}" target="_blank" class="text-blue-600 underline">View</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

<!-- Invoice Summary -->
<div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
        {{ __('Invoice Summary') }}
    </h2>

    @if(isset($invoice) && $invoice)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
            <div><strong>Invoice ID:</strong> {{ $invoice->invoice_number ?? 'N/A' }}</div>
            <div><strong>Grand Total:</strong> ₱{{ number_format($invoice->sub_total ?? 0, 2) }}</div>
            <div><strong>Amount Paid:</strong> ₱{{ number_format($invoice->amount_paid ?? 0, 2) }}</div>
            <div><strong>Balance Due:</strong> 
                <span class="{{ ($invoice->balance_due ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                    ₱{{ number_format($invoice->balance_due ?? 0, 2) }}
                </span>
            </div>
            <div><strong>Invoice Status:</strong>
                @if (isset($invoice->invoice_status))
                    @if ($invoice->invoice_status === 'pending')
                        <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">Pending</span>
                    @elseif ($invoice->invoice_status === 'complete' || $invoice->invoice_status === 'completed')
                        <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">Completed</span>
                    @endif
                @endif
            </div>
        </div>
    @else
        <p class="text-gray-600 italic dark:text-gray-300">No invoice found for this transaction.</p>
    @endif
</div>

            <!-- Back Button -->
            <div class="justify-end flex mt-2">
                <x-ghost-button onclick="history.back()" icon="fas fa-arrow-left">
                    Back
                </x-ghost-button>
            </div>
        </div>
    </div>

    <!-- Change Room Modal (Same as ViewReservation) -->
    @if ($showChangeRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b dark:bg-green-900 dark:text-green-300">
                    <h2 class="text-2xl font-bold text-center">Change Room</h2>
                </div>

                @if ($currentRoomDetails)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Current Room:</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ optional($currentRoomDetails->property)->name_number ?? 'N/A' }}
                        </p>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Select New Room <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="selectedNewRoomId"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Choose a room...</option>
                        @foreach ($availableRooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room->name_number }}
                                @if($room->dynamic_rate)
                                    - ₱{{ number_format($room->dynamic_rate, 2) }}/night
                                @endif
                                @if(optional($currentRoomDetails)->property_id == $room->id)
                                    (Current Room)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('selectedNewRoomId')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between mt-6">
                    <x-ghost-button wire:click="resetChangeRoomModal">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="changeRoom" wire:loading.attr="disabled">
                        <div class="flex items-center justify-center">
                            <span wire:loading class="mr-2" wire:target="changeRoom">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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
</div>