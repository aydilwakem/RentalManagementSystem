<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Tenant') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Tenants', 'url' => route('admin.tenants')],
            ['label' => 'View Tenant', 'url' => route('admin.view-tenant', ['tenant' => $tenant->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-6">
                <!-- Room Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Tenant: {{ $tenant->first_name }}
                    {{ $tenant->last_name }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.tenants') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Personal Information</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Full Name:</strong> {{ $tenant->first_name }} {{ $tenant->middle_name }}
                        {{ $tenant->last_name }} {{ $tenant->suffix }}
                    </div>
                    <div><strong>Email:</strong> {{ $tenant->email }}</div>
                    <div><strong>Phone Number:</strong> {{ $tenant->contact_number }}</div>
                    <div><strong>Company Name:</strong>
                        {!! $tenant->company_name ?? '<span class="text-gray-600 italic">No company provided.</span>'
                        !!}
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Address</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-gray-600 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div><strong>Assigned Property:</strong> @php $hasProperty = false; @endphp
                    @foreach ($tenant->transactions as $transaction)
                    @foreach ($transaction->properties as $property)
                    {{ $property->name_number ?? 'N/A' }}<br>
                    @php $hasProperty = true; @endphp
                    @endforeach
                    @endforeach
                    @if (!$hasProperty)
                    <span class="italic text-gray-600 dark:text-gray-200">No property assigned</span>
                    @endif
                </div>
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
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-tenant', ['tenant' => $tenant->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $tenant->id }})">
                    Delete
                    </x-daanger-button>
            </div>

            {{-- Confirm Delete Modal --}}
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
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

                    <x-danger-button class="ms-3" wire:click="deleteTenant({{ $tenant->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Tenant') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
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
    </div>
</div>
