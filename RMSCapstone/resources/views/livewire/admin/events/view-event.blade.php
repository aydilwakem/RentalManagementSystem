<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg shadow-md flex flex-col">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Event') }}
            </h2>
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-xl focus:outline-none">
                <span class="leading-none translate-y-[-1px]">&times;</span>
            </button>
        </div>
        <hr class="border-gray-300">
    </div>

    <h3 class="text-lg font-semibold text-gray-900 mb-3">Event Details</h3>
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Guest Details</h3>
                <div><strong>Event Booked By: </strong> {{ $event->transactionUser->first_name}} {{
                    $event->transactionUser->middle_name}} {{ $event->transactionUser->last_name}}</div>
                <div><strong>Email: </strong> {{ $event->transactionUser->email}}</div>
                <div><strong>Contact Number: </strong> {{ $event->transactionUser->contact_number}}</div>
                <div><strong>Company Name: </strong> {{ $event->transactionUser->company_name}}</div>
                <div><strong>Company Name: </strong> {{ $event->transactionUser->city_municipality}}, {{
                    $event->transactionUser->country}}</div>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Event Details</h3>
                <div><strong>Event Hall: </strong> @foreach ($event->properties as $property)
                    {{ $property->name_number ?? 'No Event Hall Booked' }}<br>
                    @endforeach
                </div>
                <div><strong>Event Type: </strong>
                    {{ $event->event_type->name ?? 'N/A'}}
                </div>
                <div><strong>Event Start Date: </strong> {{ $event->start_datetime->format('F j, Y')}}</div>
                <div><strong>Event End Date: </strong> {{ $event->end_datetime->format('F j, Y')}}</div>
                <div><strong>Total Adults: </strong> {{ $event->total_adults}}</div>
                <div><strong>Total Kids: </strong> {{ $event->total_kids}}</div>
                <div><strong>Total People: </strong> {{ $event->pax}}</div>
                <div><strong>Event Status: </strong>{{ ucfirst($event->transaction_status) }}</div>
                <div><strong>Total Agreed Amount: </strong>₱{{ number_format($event->total_amount, 2) }}</div>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Event Invoice</h3>
                <div><strong>Transaction Id: </strong>
                    {{ $event->invoice->transaction_id ?? 'N/A'}}
                </div>
                <div><strong>Invoice Number: </strong> {{ $event->invoice->invoice_number}}</div>
                <div><strong>Sub Total: </strong> ₱{{ number_format($event->invoice->subtotal, 2) }}</div>
                <div><strong>Total Adults: </strong>₱{{ number_format($event->invoice->balance_due, 2) }}</div>
                <div><strong>Total Kids: </strong> {{ $event->invoice->due_date->format('F j, Y')}}</div>
                <div><strong>Total People: </strong> {{ ucfirst($event->invoice->invoice_status)}}</div>
            </div>
        </div>
    </div>



    <!-- Action Buttons -->
    <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
        <!-- Edit -->
        <x-button type="button" icon="fas fa-pen-to-square"
            class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:navigate href="{{ route('admin.edit-event', ['event' => $event->id]) }}">
            Edit
        </x-button>

        <!-- Delete -->
        <x-button type="button" icon="fas fa-trash"
            class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:click="confirmDelete({{ $event->id }})">
            Delete
        </x-button>
    </div>

    <x-dialog-modal wire:model.live="confirmItemDelete">
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

            <x-danger-button class="ms-3" wire:click="deleteEventItem({{ $event->id }})" wire:loading.attr="disabled">
                {{ __('Delete Lease') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>