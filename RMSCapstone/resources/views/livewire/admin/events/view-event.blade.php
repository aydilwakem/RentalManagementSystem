<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Event Details') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Events', 'url' => route('admin.events')],
            ['label' => 'View Event', 'url' => route('admin.view-event', ['event'=> $event->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
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
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Booking Contact Details</h3>
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
                        @if($event->transactionUser->city_municipality && $event->transactionUser->country)
                        {{ Str::title(strtolower($event->transactionUser->city_municipality)) }},
                        {{$event->transactionUser->country }}
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
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Event Schedule</h3>
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
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Event Details --}}
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Full Event Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Event Hall:</strong>
                        @foreach ($event->properties as $property)
                        {{ $property->name_number ?? 'No Event Hall Booked' }}<br>
                        @endforeach

                    </div>
                    <div><strong>Event Type:</strong> {{ $event->event_type->name ?? 'N/A' }}</div>
                    <div><strong>Total Adults:</strong> {{ $event->total_adults }}</div>
                    <div><strong>Total Kids:</strong> {{ $event->total_kids ?? 'No Kids' }}</div>
                    <div><strong>Total People:</strong> {{ $event->pax }}</div>
                    <div><strong>Event Status:</strong>
                        {{ ucfirst($event->transaction_status) }}
                    </div>
                </div>
            </div>

            <!-- Event Invoice -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Event Invoice</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Transaction ID:</strong>
                        {{ $event->invoice->transaction->transaction_number ?? 'N/A' }}
                    </div>
                    <div><strong>Invoice Number:</strong> {{ $event->invoice->invoice_number }}</div>
                    <div><strong>Sub Total:</strong> ₱{{ number_format($event->invoice->sub_total, 2) }}</div>
                    <div><strong>Balance Due:</strong> ₱{{ number_format($event->invoice->balance_due, 2) }}</div>
                    <div><strong>Due Date:</strong> {{ $event->invoice->due_date->format('F j, Y') }}</div>
                    <div><strong>Invoice Status:</strong> {{ ucfirst($event->invoice->invoice_status) }}</div>
                </div>
            </div>

            <!-- Payments  -->
            <section id="payments">
                <div class="text-left mb-3 flex items-center gap-2 mt-8">
                    <!-- Info Icon with Tooltip -->
                    <div class="relative group inline-block">
                        <x-button wire:click="OpenCreatePaymentModal">
                            <i class="fas fa-plus mr-2"></i>
                            Create Payment
                        </x-button>
                        <i class="fas fa-info-circle text-gray-500 text-sm cursor-pointer dark:text-white"></i>
                        <!-- Tooltip -->
                        <div
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-sm text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                            Create Payment is for cash payments only.
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg border border-gray-200 p-6 dark:bg-gray-600 dark:border-gray-500">
                    <h2 class="font-bold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                        {{ __('Payments') }}
                    </h2>
                    @if ($payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm dark:text-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                        Payment ID</th>
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
                                    {{-- <th class="border px-4 py-2 font-medium text-gray-900">Verified At</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-500 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-500">
                                @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-400">
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->id }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->invoice->invoice_number }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ ucfirst($payment->mode_of_payment) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        ₱{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ ucfirst($payment->payment_type) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->payment_date ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-600">
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->notes ?? '-' }}
                                    </td>
                                    {{-- <td class="border px-4 py-2 text-gray-700">
                                        {{ $payment->verified_at ?? 'To be verified' }}</td>
                                    <td class="border px-4 py-2 space-x-2">
                                        @if ($payment->payment_status === 'pending')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                            Verify Receipt
                                        </a>
                                        @elseif($payment->payment_status === 'completed' || $payment->payment_status ===
                                        'failed')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block bg-green-500 hover:bg-green-700 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                            View Receipt
                                        </a>
                                        @endif
                                    </td> --}}
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


            <!---------------------------- MODALS ---------------------------------------->
            <div>
                @if ($createPaymentModal)
                <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div
                        class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-green-700 dark:text-green-300">Add Payment</h2>

                        <!-- Amount Paid -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Amount Paid <span
                                    class="text-red-500">*</span></label>
                            <input type="number" wire:model="amount_paid" placeholder="Ex. 10,000.00" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                required>
                            @error('amount_paid')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Payment Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" wire:model="payment_date" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                required>
                            @error('payment_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Type -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Payment Type <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="payment_type" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                required>
                                <option value="">Select Payment Type</option>
                                <option value="Room Rent">Room Rent</option>
                                <option value="House Rent">House Rent</option>
                                <option value="Activity Fee">Activity Fee</option>
                                <option value="Event Hall">Event Hall</option>
                                <option value="Event Package">Event Package</option>
                                <option value="Security Deposit">Security Deposit</option>
                                <option value="Remaining Balance">Remaining Balance</option>
                            </select>
                            @error('payment_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Notes</label>
                            <input type="text" wire:model="notes" placeholder="Optional notes about the payment" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center gap-2 mt-6">
                            <x-button type="button" wire:click="CloseCreatePaymentModal"
                                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                Cancel
                            </x-button>
                            <x-button type="button" wire:click="CreatePayment">
                                Save Changes
                            </x-button>
                        </div>

                    </div>
                </div>
                @endif
            </div>

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

                    <x-button icon="fa-solid fa-file" wire:click="exportEventDetails">
                        Export PDF
                    </x-button>
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