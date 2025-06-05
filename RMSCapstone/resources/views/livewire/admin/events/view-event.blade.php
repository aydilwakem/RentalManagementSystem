<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Event Details') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Event ID:
                    {{ $event->invoice->transaction->transaction_number ?? 'N/A' }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.events') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Guest Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3">Booking Contact Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
                    <div><strong>Event Booked By:</strong> {{ $event->transactionUser->first_name }}
                        {{ $event->transactionUser->middle_name }} {{ $event->transactionUser->last_name }}
                    </div>
                    <div><strong>Email:</strong> {{ $event->transactionUser->email }}</div>
                    <div><strong>Contact Number:</strong> {{ $event->transactionUser->contact_number }}</div>
                    <div><strong>Company Name:</strong> {{ $event->transactionUser->company_name }}</div>
                    <div class="md:col-span-2"><strong>Location:</strong>
                        {{ $event->transactionUser->city_municipality }},
                        {{ $event->transactionUser->country }}
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3">Event Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
                    <div class="overflow-x-auto col-span-2">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-green-50">
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
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $event->start_datetime->format('F j, Y') }} <br>
                                        {{ $event->start_datetime->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $event->end_datetime->format('F j, Y') }} <br>
                                        {{ $event->end_datetime->format('h:i A') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div><strong>Event Hall:</strong>
                        @foreach ($event->properties as $property)
                            {{ $property->name_number ?? 'No Event Hall Booked' }}<br>
                        @endforeach
                    </div>
                    <div><strong>Event Type:</strong> {{ $event->event_type->name ?? 'N/A' }}</div>
                    <div><strong>Total Adults:</strong> {{ $event->total_adults }}</div>
                    <div><strong>Total Kids:</strong> {{ $event->total_kids }}</div>
                    <div><strong>Total People:</strong> {{ $event->pax }}</div>
                    <div><strong>Event Status:</strong> {{ ucfirst($event->transaction_status) }}</div>
                    <div class="md:col-span-2 mt-4"><strong>Total Agreed Amount:</strong>
                        ₱{{ number_format($event->total_amount, 2) }}</div>
                </div>
            </div>

            <!-- Event Invoice -->
            <h3 class="text-lg font-bold text-green-800 mb-3">Event Invoice</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
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





            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $event->id }})">
                    Delete
                </x-danger-button>

                <div class="flex space-x-2">
                    <!-- Edit -->
                    <x-ghost-button type="button" icon="fas fa-pen-to-square"
                        wire:navigate href="{{ route('admin.edit-event', ['event' => $event->id]) }}">
                        Edit
                    </x-ghost-button>

                    <x-button icon="fa-solid fa-file"
                        wire:click="exportEventDetails">
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
