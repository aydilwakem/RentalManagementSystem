<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Role: {{ $role->name }}</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>


            @php
                $permissionGroups = [
                    'Room Management' => [
                        'Rooms' => fn($p) => preg_match('/^room-(?!rate|category)/', $p->name),
                        'Room Categories' => fn($p) => str_starts_with($p->name, 'room-category'),
                        'Room Rates' => fn($p) => str_starts_with($p->name, 'room-rate'),
                        'Amenities' => fn($p) => str_starts_with($p->name, 'amenity'),
                    ],

                    'Booking & Reservations' => [
                        'New Reservations' => fn($p) => str_starts_with($p->name, 'new-reservation-'),
                        'Confirmed Reservations' => fn($p) => str_starts_with($p->name, 'confirmed-reservation'),
                        'On-going Bookings' => fn($p) => str_starts_with($p->name, 'on-going'),
                        'Old Bookings' => fn($p) => str_starts_with($p->name, 'old'),
                    ],

                    'Event Management' => [
                        'Events' => fn($p) => str_starts_with($p->name, 'event-') &&
                            !str_starts_with($p->name, 'event-category') &&
                            !str_starts_with($p->name, 'event-hall'),
                        'Event Categories' => fn($p) => str_starts_with($p->name, 'event-category'),
                        'Event Halls' => fn($p) => str_starts_with($p->name, 'event-hall'),
                    ],

                    'Property Management' => [
                        'Houses' => fn($p) => str_starts_with($p->name, 'house-') &&
                            !str_starts_with($p->name, 'house-category'),
                        'Tenants' => fn($p) => str_starts_with($p->name, 'tenant'),
                        'Maintenance' => fn($p) => str_starts_with($p->name, 'maintenance'),
                        'Leases' => fn($p) => str_starts_with($p->name, 'leases'),
                    ],

                    'Billing & Payments' => [
                        'Payment Methods' => fn($p) => str_starts_with($p->name, 'payment-method'),
                        'Payments' => fn($p) => str_starts_with($p->name, 'payments-list'),
                        'Invoices' => fn($p) => str_starts_with($p->name, 'invoices-list'),
                    ],

                    'System Settings' => [
                        'Settings' => fn($p) => str_starts_with($p->name, 'appearance-view'),
                        'Dashboard' => fn($p) => str_starts_with($p->name, 'dashboard'),
                    ],

                    'User Management' => [
                        'Roles' => fn($p) => str_starts_with($p->name, 'role'),
                        'Users' => fn($p) => str_starts_with($p->name, 'user'),
                    ],

                    'Activities' => [
                        'Activities' => fn($p) => str_starts_with($p->name, 'activity'),
                    ],
                    'Reports' => [
                        'Reports' => fn($p) => str_starts_with($p->name, 'reports'),
                    ],
                ];

                $groupedUserPermissions = [];
                foreach ($permissionGroups as $category => $subgroups) {
                    foreach ($subgroups as $subLabel => $callback) {
                        // Filter expects string $p here
                        $filtered = collect($rolePermissions)
                            ->filter(function ($p) use ($callback) {
                                // We wrap string in an object with a 'name' prop to satisfy your callbacks
                                // OR modify callbacks to accept string instead of object
                                return $callback((object) ['name' => $p]);
                            })
                            ->values();

                        if ($filtered->isNotEmpty()) {
                            $groupedUserPermissions[$category][$subLabel] = $filtered;
                        }
                    }
                }
            @endphp

            <div class="space-y-3">
                <h3 class="text-xl font-bold text-green-800">Permissions</h3>

                <div>
                    @if (empty($groupedUserPermissions))
                        <div class="text-gray-500 text-center py-8">No permissions assigned.</div>
                    @else
                        @foreach ($groupedUserPermissions as $category => $subgroups)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                                <h4 class="text-lg font-bold text-green-800 mb-4">{{ $category }}</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($subgroups as $subLabel => $perms)
                                        <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
                                            <h5 class="text-gray-800 font-medium mb-2">{{ $subLabel }}</h5>
                                            <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                                @foreach ($perms as $perm)
                                                    <li>{{ ucfirst(str_replace('-', ' ', $perm)) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    @endif
                </div>
            </div>



            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-5 mb-3">
                <!-- Edit Button -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-role', ['role' => $role->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete Button -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $role->id }})">
                    Delete
                </x-danger-button>
            </div>
        </div>




        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
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

                <x-danger-button class="ms-3" wire:click="deleteRole({{ $role->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Role') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>
