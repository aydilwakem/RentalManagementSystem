<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Property') }}
        </h2>
    </x-slot>

    <div class="py-6 px-10 mx-auto max-w-3xl border rounded-xl bg-white shadow-lg mt-6 mb-6 space-y-6">
        <!-- Back Button -->
        <div class="mx-auto max-w-2xl flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- Property Name -->
        <h2 class="text-2xl md:text-3xl font-bold leading-tight text-gray-800 text-center">
            {{ $property->name }}
        </h2>

        <!-- House Category Name -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">House Category</h3>
            <p class="text-gray-600">{{($property->category->name) }}</p>
        </div>

        <!-- Description -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Description</h3>
                <p class="text-gray-600 leading-relaxed">{{ $property->description }}</p>
            </div>
        </div>

        <!-- Monthly Rent -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Monthly Rent</h3>
            <p class="text-gray-600">₱{{ number_format($property->monthly_rent, 2) }}</p>
        </div>

        <!-- Availability -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Availability</h3>
            <p class="text-gray-600">{{ ucfirst($property->availability) }}</p>
        </div>

        <!-- Address -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Address</h3>
            <p class="text-gray-600">
                {{ $property->house_number }}, {{ $property->street }},
                {{ $property->barangay }}, {{ $property->city_municipality }},
                {{ $property->province }}, {{ $property->region }},
                {{ $property->postal_code }}, {{ $property->country }}
            </p>
        </div>


        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 pt-2">
            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:navigate href="{{ route('admin.edit-property', ['property' => $property->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:click="confirmDelete({{ $property->id }})" wire:loading.attr="disabled">
                Delete
            </x-button>
        </div>

    </div>
    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmItemDelete">
        <x-slot name="title">
            {{ __('Delete Property') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deletePropertyItem({{ $property->id }})"
                wire:loading.attr="disabled">
                {{ __('Delete Property') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem">
        <x-slot name="title">
            {{ __('Unable to Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('This house is currently in use and cannot be deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>