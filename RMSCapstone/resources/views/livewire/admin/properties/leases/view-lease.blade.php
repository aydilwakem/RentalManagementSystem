<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Lease') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Lease:
                    {{ $transaction->invoice->transaction->transaction_number ?? 'N/A' }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.leases') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Guest Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3">Occupany Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
                    <div><strong>Property:</strong>
                        @foreach ($transaction->properties as $property)
                            {{ $property->name_number ?? 'N/A' }}<br>
                        @endforeach
                    </div>
                    <div>
                        <strong>Tenant:</strong>
                        {{ $transaction->transactionUser->first_name }}
                        {{ $transaction->transactionUser->last_name }}
                    </div>
                    <div><strong>Total Occupants:</strong> {{ $transaction->pax }}</div>
                    <div><strong>Phone Number:</strong>
                        {{ $transaction->transactionUser->contact_number ?? 'No contact number provided' }}</div>

                </div>
            </div>

            <!-- Rent Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3">Rent Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
                    <div>
                        {{-- <strong>Monthly Rent: </strong>₱{{ number_format($this->getMonthlyRent($transaction), 2) }} --}}
                    </div>
                    <div>
                        <strong>Total Rent for Lease Term:
                        </strong>₱{{ number_format($transaction->total_amount, 2) }}
                    </div>
                    <div class="overflow-x-auto col-span-2">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-green-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider">
                                        Lease Start Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Lease End Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Lease Duration
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->start_datetime->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->end_datetime->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $this->getMonthCount($transaction->start_datetime, $transaction->end_datetime) }}
                                        Months
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invoice ? -->
            <div class="bg-gray-50 rounded-lg p-6 text-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Invoice Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                    <div><strong>Transaction ID: </strong>
                        {{ $transaction->transaction_number ?? 'N/A' }}
                    </div>
                    <div><strong>Invoice Number: </strong> {{ $transaction->invoice->invoice_number ?? 'N/A' }}
                    </div>
                    <div><strong>Sub Total: </strong>
                        ₱{{ optional($transaction->invoice)->sub_total !== null
                            ? number_format(optional($transaction->invoice)->sub_total, 2)
                            : 'N/A' }}
                    </div>
                    <div><strong>Balance Due:
                        </strong>₱{{ optional($transaction->invoice)->sub_total !== null
                            ? number_format(optional($transaction->invoice)->balance_due, 2)
                            : 'N/A' }}
                    </div>
                    <div><strong>Due Date: </strong>
                        {{ optional(optional($transaction->invoice)->due_date)->format('F j, Y') ?? 'N/A' }}
                    </div>
                    <div><strong>Invoice Status: </strong>
                        {{ ucfirst($transaction->invoice->invoice_status ?? 'N/A') }}
                    </div>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6 mb-3">
                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $transaction->id }})">
                    Delete
                </x-danger-button>

                <div class="space-x-3">
                    <!-- Edit -->
                    <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                        href="{{ route('admin.edit-lease', ['transaction' => $transaction->id]) }}">
                        Edit
                    </x-ghost-button>

                    <!-- Export PDF -->
                    <x-button icon="fa-solid fa-file"
                        wire:click="exportLeaseDetails">
                        Export PDF
                    </x-button>
                </div>
                <!-- Edit -->
            </div>

            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Lease') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this lease?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteLease({{ $transaction->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Lease') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This is an active or on-going lease and cannot be deleted.') }}
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
