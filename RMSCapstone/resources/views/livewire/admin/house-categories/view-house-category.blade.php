<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View House Category') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto max-w-2xl lg:py-2 flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center p-8">
            House Category: {{ $houseCategory->name }}
        </h2>

        <p class="text-gray-700 text-center mb-6">Description: @if (!empty($houseCategory->description))
            {{ $houseCategory->description }}
            @else
            <em class="text-gray-600 leading-relaxed">No description provided.</em>
            @endif
        </p>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-house-category', ['houseCategory' => $houseCategory->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $houseCategory->id }})">
                Delete
            </x-button>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete House Category') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteHouseCategory({{ $houseCategory->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete House Category') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This category is currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>