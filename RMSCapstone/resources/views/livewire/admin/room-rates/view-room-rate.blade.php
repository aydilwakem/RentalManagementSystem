<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Room Rate') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-3xl border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto max-w-2xl lg:py-2 flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- Room Rate Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            Room Rate: {{ $roomRate->name }}
        </h2>

        <!-- Room Information -->
        <div class="mb-2 mt-3 flex items-center gap-2">
            <h3 class="text-lg font-semibold text-gray-900 leading-none">Assigned Room:</h3>
            <p class="font-semibold text-gray-600 leading-none">
                {{ $roomRate->property->name_number ?? 'No Room Assigned' }}
            </p>
        </div>

        <!-- Rate Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Rate Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Start Date:</strong> {{ $roomRate->start_date }}</li>
                <li><strong>End Date:</strong> {{ $roomRate->end_date }}</li>
                <li><strong>Amount:</strong> ₱{{ number_format($roomRate->amount, 2) }}</li>
                <li><strong>Extra Person Charge:</strong> ₱{{ number_format($roomRate->extra_person_charge, 2) }}</li>
                <li><strong>Extended Stay Charge Per Hour:</strong>
                    ₱{{ number_format($roomRate->extended_stay_charge_per_hr, 2) }}</li>
            </ul>
        </div>

        <!-- Rate Type & Description -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Rate Type</h3>
            <p class="font-light text-gray-500">
                @if ($roomRate->rate_type === 'Weekdays')
                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                        Weekdays
                    </span>
                @elseif($roomRate->rate_type === 'Weekend')
                    <span
                        class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                        Weekend
                    </span>
                @elseif($roomRate->rate_type === 'Holiday')
                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-600">
                        Holiday
                    </span>
                @elseif($roomRate->rate_type === 'Peak')
                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                        Peak
                    </span>
                @endif
            </p>
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Description</h3>
            <p class="font-light text-gray-500">{{ $roomRate->description ?? 'No description provided' }}</p>
        </div>

        <!-- Additional Information -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Freebies:</strong>
                    @if ($roomRate->freebies)
                        <p class="flex">Free Breakfast Included <i class="fa-solid fa-check pl-2"></i></p>
                    @endif
                </li>
                <li><strong>Priority:</strong> {{ $roomRate->priority ? 'Yes' : 'No' }}</li>
                <li><strong>Room Rate Status:</strong> {{ $roomRate->is_active ? 'Active' : 'Inactive' }}</li>
                <li><strong>Minimum Stay:</strong> {{ $roomRate->min_stay_nights ?? 'Not Specified' }} night(s)</li>
                <li><strong>Maximum Stay:</strong> {{ $roomRate->max_stay_nights ?? 'Not Specified' }} night(s)</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">
            <!-- Edit -->
            <x-ghost-button type="button" icon="fas fa-pen-to-square"
                wire:navigate href="{{ route('admin.edit-individual-rate', ['roomRate' => $roomRate->id]) }}">
                Edit
            </x-ghost-button>

            <!-- Delete -->
            <x-danger-button type="button" icon="fas fa-trash"
                wire:click="confirmDelete({{ $roomRate->id }})">
                Delete
            </x-danger-button>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
        <x-slot name="title">
            {{ __('Delete Room Rate') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteRoomRate({{ $roomRate->id }})"
                wire:loading.attr="disabled">
                {{ __('Delete Room Rate') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
        <x-slot name="title">
            {{ __('Unable to Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('This room rate is currently in use and cannot be deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
