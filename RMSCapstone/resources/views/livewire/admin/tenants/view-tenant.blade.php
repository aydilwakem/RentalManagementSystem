<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Tenant') }}
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

        <!-- Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $tenant->name }}
        </h2>

        <!-- House -->
        <div class="mb-2 mt-3 flex items-center gap-2">
            <h3 class="text-lg font-semibold text-gray-900 leading-none">House:</h3>
            <p class="font-semibold text-gray-600 leading-none">
                {{ $tenant->house->name ?? 'N/A'}}
            </p>
        </div>


        <!-- Room Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Room Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Name:</strong> {{ $tenant->first_name }} {{ $tenant->middle_name }} {{
                    $tenant->last_name }} {{ $tenant->suffix }}</li>
                <li><strong>Email:</strong> {{ $tenant->email }}</li>
                <li><strong>Phone Number:</strong> {{ $tenant->phone }}</li>
                <li><strong>Birthdate:</strong> {{ $tenant->birthdate }} hours</li>
                <li><strong>Gender:</strong> {{ $tenant->gender }}</li>
                <li><strong>Occupation:</strong> {{ $tenant->occupation }}</li>
                <li><strong>Notes:</strong> {{ $tenant->notes }}</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

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
        <!-- Delete Confirmation Modal -->
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

    </div>


</div>