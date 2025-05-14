<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg shadow-md flex flex-col">
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Tenant') }}
            </h2>
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-xl focus:outline-none">
                <span class="leading-none translate-y-[-1px]">&times;</span>
            </button>
        </div>
        <hr class="border-gray-300">
    </div>

    <h3 class="text-lg font-semibold text-gray-900 mb-3">Personal Information</h3>
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
            <div><strong>Full Name:</strong> {{ $tenant->first_name }} {{ $tenant->middle_name }}
                {{ $tenant->last_name }} {{ $tenant->suffix }}
            </div>
            <div><strong>Email:</strong> {{ $tenant->email }}</div>
            <div><strong>Phone Number:</strong> {{ $tenant->contact_number }}</div>
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 mb-3">Address</h3>
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        @if (empty($tenant->house_number) &&
        empty($tenant->street) &&
        empty($tenant->barangay) &&
        empty($tenant->city_municipality) &&
        empty($tenant->province) &&
        empty($tenant->region) &&
        empty($tenant->postal_code) &&
        empty($tenant->country))
        <span>No address available</span>
        @else
        @if ($tenant->house_number)
        {{ $tenant->house_number }},
        @endif
        @if ($tenant->street)
        {{ $tenant->street }},
        @endif
        @if ($tenant->barangay)
        {{ $tenant->barangay }},
        @endif
        @if ($tenant->city_municipality)
        {{ $tenant->city_municipality }},
        @endif
        @if ($tenant->province)
        {{ $tenant->province }},
        @endif
        @if ($tenant->region)
        {{ $tenant->region }},
        @endif
        @if ($tenant->postal_code)
        {{ $tenant->postal_code }},
        @endif
        @if ($tenant->country)
        {{ $tenant->country }}
        @endif
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
        <!-- Edit -->
        <x-button type="button" icon="fas fa-pen-to-square"
            class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:navigate href="{{ route('admin.edit-tenant', ['tenant' => $tenant->id]) }}">
            Edit
        </x-button>

        <!-- Delete -->
        <x-button type="button" icon="fas fa-trash"
            class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:click="confirmDelete({{ $tenant->id }})">
            Delete
        </x-button>
    </div>

    {{-- Confirm Delete Modal --}}
    <x-dialog-modal wire:model.live="confirmItemDelete">
        <x-slot name="title">
            {{ __('Delete Tenant') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this tenant?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTenant({{ $tenant->id }})" wire:loading.attr="disabled">
                {{ __('Delete Tenant') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem">
        <x-slot name="title">
            {{ __('Unable to Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('This tenant has an active lease and cannot be deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>