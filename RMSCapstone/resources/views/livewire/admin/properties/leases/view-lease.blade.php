<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg shadow-md flex flex-col">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Lease') }}
            </h2>
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-xl focus:outline-none">
                <span class="leading-none translate-y-[-1px]">&times;</span>
            </button>
        </div>
        <hr class="border-gray-300">
    </div>

    <div class="flex flex-col space-y-6 mb-6">

        <div class="bg-gray-50 rounded-lg p-6 text-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Guest Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
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
                <div><strong>Total People:</strong> {{ $transaction->pax }}</div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 text-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Rent Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                <div><strong>Lease Start Date:</strong> {{ $transaction->start_datetime->format('F j, Y') }}</div>
                <div><strong>Lease End Date:</strong> {{ $transaction->end_datetime->format('F j, Y') }}</div>
                <div><strong>Total Months:</strong>
                    {{ $this->getMonthCount($transaction->start_datetime, $transaction->end_datetime) }} Months
                </div>
                <div><strong>Monthly Rent:</strong>₱ {{ number_format($this->getMonthlyRent($transaction), 2) }}</div>
                <div><strong>Total Rent in Duration:</strong>₱ {{ number_format($transaction->total_amount, 2) }}</div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 text-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Invoice Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                <div><strong>Transaction ID: </strong>
                    {{ $transaction->invoice->transaction->transaction_number ?? 'N/A' }}
                </div>
                <div><strong>Invoice Number: </strong> {{ $transaction->invoice->invoice_number ?? 'N/A' }}</div>
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
                <div><strong>Invoice Status: </strong> {{ ucfirst($transaction->invoice->invoice_status ?? 'N/A') }}
                </div>
            </div>
        </div>
    </div>


    <!-- Action Buttons -->
    <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
        <!-- Edit -->
        <x-button type="button" icon="fas fa-pen-to-square"
            class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:navigate href="{{ route('admin.edit-lease', ['transaction' => $transaction->id]) }}">
            Edit
        </x-button>

        <!-- Delete -->
        <x-button type="button" icon="fas fa-trash"
            class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:click="confirmDelete({{ $transaction->id }})">
            Delete
        </x-button>
    </div>

    <x-dialog-modal wire:model.live="confirmItemDelete">
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

            <x-danger-button class="ms-3" wire:click="deleteLease({{ $transaction->id }})" wire:loading.attr="disabled">
                {{ __('Delete Lease') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem">
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