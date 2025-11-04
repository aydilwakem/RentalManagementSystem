<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Event Details') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Events', 'url' => route('admin.events')],
            ['label' => 'View Event', 'url' => route('admin.view-event', ['event' => $event->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <x-button icon="fa-solid fa-file" wire:click="exportEventDetails" class="w-40">
                    Export PDF
                </x-button>

                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Event ID:
                    {{ $event->invoice->transaction->transaction_number ?? 'N/A' }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.events') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Guest Details -->
            <h3 class="text-lg font-bold text-green-700 mb-3 dark:text-green-300">Booking Contact Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Event Booked By:</strong> {{ $event->transactionUser->first_name }}
                        {{ $event->transactionUser->middle_name }} {{ $event->transactionUser->last_name }}
                    </div>
                    <div><strong>Email:</strong> {{ $event->transactionUser->email }}</div>
                    <div><strong>Contact Number:</strong> {{ $event->transactionUser->contact_number }}</div>
                    <div><strong>Company Name:</strong> {{ $event->transactionUser->company_name }}</div>
                    <div class="md:col-span-2"><strong>Location:</strong>
                        {{-- If there is municipality and country, it will show both. --}}
                        @if ($event->transactionUser->city_municipality && $event->transactionUser->country)
                            {{ Str::title(strtolower($event->transactionUser->city_municipality)) }},
                            {{ $event->transactionUser->country }}
                            {{-- If there is only municipality, it will show municipality only. --}}
                        @elseif($event->transactionUser->city_municipality)
                            {{ Str::title(strtolower($event->transactionUser->city_municipality)) }}
                            {{-- If there is only country, it will show country only. --}}
                        @elseif($event->transactionUser->country)
                            {{ $event->transactionUser->country }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <h3 class="text-lg font-bold text-green-700 mb-3 dark:text-green-300">Event Schedule</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div>
                        <strong>Total Agreed Amount
                        </strong>₱{{ number_format($event->total_amount, 2) }}
                    </div>
                    <div class="overflow-x-auto col-span-2">
                        <table
                            class="min-w-full divide-y divide-gray-200 border dark:border-gray-500 dark:divide-gray-500">
                            <thead class="bg-green-50 dark:bg-green-200">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider">
                                        Event Start Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Event End Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Actual Start Date and Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-500">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $event->start_datetime->format('F j, Y') }} <br>
                                        {{ $event->start_datetime->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $event->end_datetime->format('F j, Y') }} <br>
                                        {{ $event->end_datetime->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if ($event->actual_start_datetime)
                                            {{ $event->actual_start_datetime->format('F j, Y') }}
                                        @else
                                            Not yet started
                                        @endif
                                        <br>
                                        @if ($event->actual_start_datetime)
                                            {{ $event->actual_start_datetime->format('h:i A') }}
                                        @else
                                            Time not yet indicated
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Event Details --}}
            @php
                $statusLabels = [
                    'pending' => 'Pending',
                    'reserved' => 'Reserved',
                    'receipt_verified' => 'Receipt Verified',
                    'confirmed' => 'Confirmed',
                    'ongoing' => 'Ongoing',
                    'done' => 'Completed',
                    'no_show' => 'No Show',
                    'terminated' => 'Terminated',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ];

                $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                    'reserved' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                    'receipt_verified' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                    'ongoing' => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
                    'done' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                    'no_show' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                    'terminated' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                    'expired' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                ];

                $status = $event->transaction_status;
            @endphp
            <h3 class="text-lg font-bold text-green-700 mb-3 dark:text-green-300">Event Details</h3>

            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                    <!-- Left: Event Details -->
                    <div class="space-y-2 text-gray-700 dark:text-gray-200">
                        <div>
                            <strong>Event Status:</strong>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                            </span>
                        </div>
                        <div>
                            <strong>Event Hall/s:</strong>
                            <p>
                                @foreach ($event->properties->where('property_type_id', 3) as $property)
                                    {{ $property->name_number ?? 'No Event Hall Booked' }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>

                            @if ($event->properties->where('property_type_id', 1)->count() > 0)
                                <p class="mt-1 text-sm">
                                    <strong>Rooms included:</strong>
                                    @foreach ($event->properties->where('property_type_id', 1) as $property)
                                        {{ $property->name_number ?? 'No Room Booked' }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            @endif
                        </div>
                        <div><strong>Event Type:</strong> {{ $event->event_type->name ?? 'N/A' }}</div>
                        <div><strong>Total Pax:</strong> {{ $event->pax }}</div>
                        <div><strong>Total Adults:</strong> {{ $event->total_adults }}</div>
                        <div><strong>Total Kids:</strong> {{ $event->total_kids ?? 'No Kids' }}</div>

                        @if ($event->dishes && is_array($event->dishes) && count($event->dishes) > 0)
                            <div>
                                <strong>Catering Menu:</strong>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach ($event->dishes as $dish)
                                        <span
                                            class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full dark:bg-green-900 dark:text-green-200">
                                            {{ trim($dish) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($event->dishes)
                            <div><strong>Dishes:</strong> {{ $event->dishes }}</div>
                        @endif
                    </div>

                    <!-- Right: Additional Items Collapsible -->
                    @if (count($selectedRooms) > 0 || count($selectedActivities) > 0 || count($selectedServices) > 0)
                        <div x-data="{ open: false }"
                            class="inline-block bg-white dark:bg-gray-700 rounded-lg border shadow-sm dark:border-gray-600">

                            <!-- Header -->
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-3 text-left text-base font-semibold text-green-700 dark:text-green-300 focus:outline-none">
                                <span>Additional Items</span>
                                <svg class="w-5 h-5 transform transition-transform duration-300"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Body -->
                            <div x-show="open" x-collapse class="px-4 pb-4 text-gray-700 dark:text-gray-200">
                                <!-- Rooms -->
                                @if (count($selectedRooms) > 0)
                                    <div class="mb-4">
                                        <h4 class="font-semibold mb-2 border-b pb-1">Rooms</h4>
                                        <div class="space-y-2">
                                            @foreach ($selectedRooms as $room)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-md border dark:bg-gray-600 dark:border-gray-500">
                                                    <span>{{ $room['room_name'] }}</span>
                                                    <span class="text-xs text-gray-500">{{ $room['ideal_guest'] }}
                                                        guests</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Activities -->
                                @if (count($selectedActivities) > 0)
                                    <div class="mb-4">
                                        <h4 class="font-semibold mb-2 border-b pb-1">Activities</h4>
                                        <div class="space-y-2">
                                            @foreach ($selectedActivities as $activity)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-md border dark:bg-gray-600 dark:border-gray-500">
                                                    <span>{{ $activity['activity_name'] }}</span>
                                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                                        <span>Qty: {{ $activity['quantity'] }}</span>
                                                        @if ($activity['activity_datetime'])
                                                            <span>{{ \Carbon\Carbon::parse($activity['activity_datetime'])->format('M j, g:i A') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Services -->
                                @if (count($selectedServices) > 0)
                                    <div>
                                        <h4 class="font-semibold mb-2 border-b pb-1">Services</h4>
                                        <div class="space-y-2">
                                            @foreach ($selectedServices as $service)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-md border dark:bg-gray-600 dark:border-gray-500">
                                                    <span>{{ $service['service_name'] }}</span>
                                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                                        <span>Qty: {{ $service['quantity'] }}</span>
                                                        <span>{{ $service['service_unit'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>



            <!-- Additional Items Section -->
            {{-- @if (count($selectedRooms) > 0 || count($selectedActivities) > 0 || count($selectedServices) > 0)
                <h3 class="text-lg font-bold text-green-700 mb-3 dark:text-green-300">Additional Items</h3>
                <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">

                    <!-- Rooms -->
                    @if (count($selectedRooms) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Rooms:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($selectedRooms as $room)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-white rounded border dark:bg-gray-500 dark:border-gray-400">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $room['room_name'] }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $room['ideal_guest'] }} guests
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Activities -->
                    @if (count($selectedActivities) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Activities:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($selectedActivities as $activity)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-white rounded border dark:bg-gray-500 dark:border-gray-400">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $activity['activity_name'] }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Qty: {{ $activity['quantity'] }}
                                            </span>
                                            @if ($activity['activity_datetime'])
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($activity['activity_datetime'])->format('M j, g:i A') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Services -->
                    @if (count($selectedServices) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Services:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($selectedServices as $service)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-white rounded border dark:bg-gray-500 dark:border-gray-400">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $service['service_name'] }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Qty: {{ $service['quantity'] }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $service['service_unit'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif --}}

        <!-- Event Invoice -->
        <h3 class="text-lg font-bold text-green-700 mb-3 dark:text-green-300">Event Invoice</h3>
        <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                <div><strong>Transaction ID:</strong>
                    {{ $event->invoice->transaction->transaction_number ?? 'N/A' }}
                </div>
                <div><strong>Invoice Number:</strong> {{ $event->invoice->invoice_number }}</div>
                <div><strong>Due Date:</strong> {{ $event->invoice->due_date->format('F j, Y') }}</div>
                <div><strong>Invoice Status:</strong> {{ ucfirst($event->invoice->invoice_status) }}</div>
                <div class="text-green-600"><strong>Total:</strong>
                    ₱{{ number_format($event->invoice->sub_total, 2) }}</div>
                <div class="text-green-600"><strong>Amount Paid:</strong>
                    ₱{{ number_format($event->invoice->amount_paid, 2) }}</div>
                
                @if ($event->invoice->amount_paid > $event->invoice->sub_total)
                    <!-- Overpayment / Change -->
                    <div class="text-blue-600">
                        <strong>Change / Overpayment:</strong>
                        ₱{{ number_format($event->invoice->amount_paid - $event->invoice->sub_total, 2) }}
                    </div>
                    <!-- Balance Due (should be 0 when overpaid) -->
                    <div class="text-green-600">
                        <strong>Balance Due:</strong> ₱{{ number_format($event->invoice->balance_due, 2) }}
                    </div>
                @else
                    <!-- Balance Due (normal cases) -->
                    @if ($event->invoice->balance_due == $event->invoice->sub_total)
                        {{-- Unpaid --}}
                        <div class="text-red-600">
                            <strong>Balance Due:</strong> ₱{{ number_format($event->invoice->balance_due, 2) }}
                        </div>
                    @elseif ($event->invoice->balance_due > 0)
                        {{-- Partially paid --}}
                        <div class="text-yellow-500">
                            <strong>Balance Due:</strong> ₱{{ number_format($event->invoice->balance_due, 2) }}
                        </div>
                    @else
                        {{-- Fully paid --}}
                        <div class="text-green-600">
                            <strong>Balance Due:</strong> ₱{{ number_format($event->invoice->balance_due, 2) }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

            <!----------------------------- PAYMENTS -------------------------------------->
            <div class="flex items-center justify-between  mb-3">
                <h3 class="text-lg font-bold text-green-700 dark:text-green-300">Payments
                    (₱{{ number_format($this->invoice->amount_paid, 2) }})</h3>
                            @if ($event->transaction_status != 'done')
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
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                @if ($payments->isNotEmpty())
                    <div class="">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-green-50 dark:bg-gray-800">
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

                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                    Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600 ">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $loop->iteration }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $payment->invoice->invoice_number }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $payment->paymentMethod?->mode_of_payment_name ?? 'N/A' }}</td>
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
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">
                                                    (cash payment, no convenience fee)
                                                </div>
                                            @endif

                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($payment->payment_type) }}</td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 break-words max-w-[96px]">
                                            {{ $payment->payment_reference_number ?? 'N/A' }}
                                        </td>

                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
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
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $payment->notes ?? '-' }}
                                        </td>
                                        <td
                                            class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $payment->verified_at ?? 'To be verified' }}</td>
                                        <td class="border px-4 py-2 space-x-2 dark:text-gray-200 dark:border-gray-500">
                                            @if (!$payment->payment_screenshot)
                                                @if ($payment->mode_of_payment === 'cash')
                                                    <span class="text-gray-500 italic dark:text-gray-200">Cash
                                                        Payment (no receipt uploaded)</span>
                                                @else
                                                    <span class="text-gray-500 italic dark:text-gray-200">Completed
                                                        via secure
                                                        online payment</span>
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
                                            @if ($event->transaction_status === 'done')
                                                <span class="text-gray-400 dark:text-gray-500" title="Not Available - Transaction Completed">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            @else 
                                                <button wire:click="editPayment({{ $payment->id }})" 
                                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-500"
                                                        title="Edit Payment">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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
            <!-------------------------- END OF PAYMENTS ---------------------------------->




            <!---------------------------- MODALS ---------------------------------------->
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
                                    <option value="Room Rent">Room Rent</option>
                                    <option value="House Rent">House Rent</option>
                                    <option value="Activity Fee">Activity Fee</option>
                                    <option value="Event Hall">Event Hall</option>
                                    <option value="Event Package">Event Package</option>
                                    <option value="Security Deposit">Security Deposit</option>
                                    <option value="Remaining Balance">Remaining Balance</option>
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
                                <label for="payment_method_id"
                                    class="block text-sm text-gray-700 font-semibold">Payment
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
                                <label for="payment_screenshot"
                                    class="block text-sm text-gray-700 font-semibold mb-1">Proof
                                    of
                                    Payment <span class="text-red-500"></span></label>

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
                                            <p>Drag & drop a file or <span
                                                    class="text-blue-500 underline">browse</span></p>
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
                                <input type="text" wire:model="notes"
                                    placeholder="Optionally add description of payment"
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

            <!-- Edit Payment Modal -->
            @if ($showEditPaymentModal && $editingPayment)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                        <div class="relative -mt-6 -mx-6 mb-6 bg-blue-50 text-blue-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
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
                                <label class="block text-sm text-gray-700 font-semibold">Amount Paid <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="edit_amount_paid" 
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                                    required>
                                @error('edit_amount_paid')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold">Payment Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="edit_payment_date"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                                    required>
                                @error('edit_payment_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Type -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold">Payment Type <span class="text-red-500">*</span></label>
                                <select wire:model="edit_payment_type"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                                    required>
                                    <option value="">Select Payment Type</option>
                                    <option value="Room Rent">Room Rent</option>
                                    <option value="House Rent">House Rent</option>
                                    <option value="Activity Fee">Activity Fee</option>
                                    <option value="Event Hall">Event Hall</option>
                                    <option value="Event Package">Event Package</option>
                                    <option value="Security Deposit">Security Deposit</option>
                                    <option value="Remaining Balance">Remaining Balance</option>
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
                                <label class="block text-sm text-gray-700 font-semibold">Payment Method <span class="text-red-500">*</span></label>
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
                                <label class="block text-sm text-gray-700 font-semibold">Proof of Payment</label>

                                <!-- Hidden file input -->
                                <input id="edit_payment_screenshot" type="file" accept="image/*" wire:model="edit_payment_screenshot"
                                    class="hidden">

                                <!-- Show existing screenshot -->
                                @if ($existing_payment_screenshot && !$edit_payment_screenshot)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Current proof of payment:</p>
                                        <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden border">
                                            <img src="{{ asset('storage/' . $existing_payment_screenshot) }}" 
                                                class="w-full h-full object-cover"
                                                alt="Current Payment Screenshot"
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
                                                class="w-full h-full object-cover"
                                                alt="New Payment Screenshot Preview"
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
                                        <div class="w-full px-4 py-8 border-2 border-dashed border-gray-300 text-center rounded-md text-gray-500 cursor-pointer hover:border-blue-400 transition-colors">
                                            <div class="mb-2">
                                                <i class="fas fa-upload text-2xl"></i>
                                            </div>
                                            <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span></p>
                                            <p class="text-xs text-gray-400 mt-1">Max: 2MB (JPEG, PNG, JPG)</p>
                                        </div>
                                    </label>
                                @endif

                                <!-- Loading Indicator -->
                                <div wire:loading wire:target="edit_payment_screenshot" class="mt-2 text-gray-600 flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2 text-blue-700" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
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
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                                    rows="3"
                                    placeholder="Optional notes about this payment"></textarea>
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
                            <x-button wire:click="updatePayment" class="bg-blue-600 hover:bg-blue-700">
                                Update Payment
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

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-12 mb-3">
                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $event->id }})">
                    Delete
                </x-danger-button>

                <div class="flex space-x-2">
                    <!-- Edit -->
                    <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                        href="{{ route('admin.edit-event', ['event' => $event->id]) }}">
                        Edit
                    </x-ghost-button>

                </div>
            </div>

            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Event') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this event?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteEventItem({{ $event->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Event') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This event is confirmed or on-going and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
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