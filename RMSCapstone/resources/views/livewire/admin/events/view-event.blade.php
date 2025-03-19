<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Event') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <!-- Event Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $event->name }}
        </h2>

        <!-- Event Details -->
        <div class="mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Event Details</h3>

            <div class="grid grid-cols-2 gap-6 py-3">
                <!-- First Row -->
                <div>
                    <ul class="list-disc pl-5 text-gray-600">
                        <li><strong>Category:</strong> {{ $event->category->name }}</li>
                        <li><strong>Company Name:</strong> {{ $event->company_name }}</li>
                        <li><strong>Email:</strong> {{ $event->email }}</li>
                        <li><strong>Event Date Start:</strong>
                            {{ $event->event_date_start->format('F j, Y') }}</li>
                        <li><strong>Event Time:</strong>
                            {{$event->event_time->format('h:i A') }}</li>
                    </ul>
                </div>

                <div>
                    <ul class="list-disc pl-5 text-gray-600">
                        <li><strong>Event Hall:</strong> {{ $event->eventHall->name }}</li>
                        <li><strong>Contact Person:</strong> {{ $event->contact_person }}</li>
                        <li><strong>Capacity:</strong> {{ $event->capacity }}</li>
                        <li><strong>Event Date End:</strong>
                            {{ $event->event_date_start->format('F j, Y') }}</li>
                        <li><strong>Total Amount:</strong> {{ $event->total_amount }}</li>
                    </ul>
                </div>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-2 gap-6 py-3">
                <div>
                    <ul class="list-disc pl-5 text-gray-600">
                        <li><strong>Status:</strong> {{ ucfirst($event->status) }}</li>
                    </ul>
                </div>

                <div>
                    <ul class="list-disc pl-5 text-gray-600">
                        <li><strong>Requests:</strong> {{ $event->requests }}</li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-5 mb-3">
            <!-- Edit Button -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-event', ['event' => $event->id]) }}">
                Edit
            </x-button>

            <!-- Delete Button -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $event->id }})" wire:loading.attr="disabled">
                Delete
            </x-button>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmItemDelete">
        <x-slot name="title">
            {{ __('Delete Event') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteEventItem({{ $event->id }})" wire:loading.attr="disabled">
                {{ __('Delete Event') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

</div>