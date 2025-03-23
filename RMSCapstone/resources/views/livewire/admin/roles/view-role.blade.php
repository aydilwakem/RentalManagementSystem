<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Role') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto max-w-2xl flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- Role Name -->
        <div class="flex justify-center items-center space-x-2 mb-4">
            <h3 class="flex text-xl font-semibold text-gray-900">Role:</h3>
            <h3 class="flex text-xl font-semibold text-gray-900">{{ $role->name }}</h3>
        </div>


        @php
        $groups = [
        'Room' => 'room-',
        'Room Rate' => 'room-rate-',
        'Room Category' => 'room-category-',
        'Event' => 'event-',
        'Event Hall' => 'event-hall-',
        'Event Category' => 'event-category-',
        'Activity' => 'activity-',
        'Maintenance' => 'maintenance-',
        'Role' => 'role-',
        'Payment Method' => 'payment-method-',
        'User' => 'user-',
        'Dashboard' => 'dashboard-',
        ];

        $groupedPermissions = [];

        foreach ($groups as $label => $prefix) {
        $groupedPermissions[$label] = $role->permissions->filter(function ($permission) use ($prefix) {
        // Only match exact prefix and not submodules
        $subPrefixes = [
        'room-' => ['room-rate-', 'room-category-'],
        'event-' => ['event-hall-', 'event-category-'],
        ];

        // If prefix has exclusions
        if (array_key_exists($prefix, $subPrefixes)) {
        foreach ($subPrefixes[$prefix] as $exclude) {
        if (str_starts_with($permission->name, $exclude)) {
        return false;
        }
        }
        }

        return str_starts_with($permission->name, $prefix);
        });
        }
        @endphp



        <div class="space-y-4">
            <h1 class="flex font-semibold text-gray-800">Permissions:</h1>
            @foreach ($groupedPermissions as $group => $permissions)
            @if ($permissions->count())
            <div class="border p-4 rounded-lg">
                <h4 class="text-md font-semibold text-gray-700 mb-2">{{ $group }}</h4>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    @foreach ($permissions as $perm)
                    <li>{{ ucfirst(str_replace('-', ' ', $perm->name)) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @endforeach
        </div>




        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-5 mb-3">
            <!-- Edit Button -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-role', ['role' => $role->id]) }}">
                Edit
            </x-button>

            <!-- Delete Button -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $role->id }})">
                Delete
            </x-button>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Role') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this role?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteRole({{ $role->id }})" wire:loading.attr="disabled">
                    {{ __('Delete Role') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>