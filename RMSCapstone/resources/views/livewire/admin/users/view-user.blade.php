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
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">{{ $user->name }} {{ $user->last_name }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.manage-users') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Profile Photo -->
            <div class="mb-6 flex justify-center">
                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/default-profile-photo.png') }}"
                    alt="{{ $user->name }}" class="w-32 h-32 object-cover rounded-full shadow-md">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Left Column -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                    <p class=" text-gray-500 mb-3">{{ $user->email }}</p>

                    <h3 class="text-lg font-semibold text-gray-900">Role</h3>
                    <ul>
                        @forelse($userRoles as $role)
                            <li>{{ $role }}</li>
                        @empty
                            <li class="text-gray-500">No roles assigned.</li>
                        @endforelse
                    </ul>

                    <div class="mt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Email Verified At</h3>
                        <p class=" text-gray-500">{{ $user->email_verified_at ?? 'Not Verified' }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider">
                                        Created At
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Updated At
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->created_at }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->updated_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                            $filtered = collect($userPermissions)
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

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Permissions</h3>

                    <div class="border rounded bg-gray-50 max-h-full overflow-y-auto divide-y divide-gray-200">
                        @if (empty($groupedUserPermissions))
                            <div class="p-4 text-gray-500">No permissions assigned.</div>
                        @else
                            @foreach ($groupedUserPermissions as $category => $subgroups)
                                <div x-data="{ open: false }" class="px-4 py-2">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex justify-between items-center font-semibold text-gray-700 hover:text-green-600 focus:outline-none">
                                        {{ $category }}
                                        <svg :class="{ 'rotate-180': open }" class="h-5 w-5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition
                                        class="mt-2 py-2 space-y-3 pl-4 pr-4 border-l border-gray-300 bg-white shadow">
                                        @foreach ($subgroups as $subLabel => $perms)
                                            <div x-data="{ open: false }">
                                                <button type="button" @click="open = !open"
                                                    class="w-full flex justify-between items-center text-gray-600 hover:text-green-500 focus:outline-none font-medium">
                                                    {{ $subLabel }}
                                                    <svg :class="{ 'rotate-180': open }"
                                                        class="h-4 w-4 transition-transform" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <ul x-show="open" x-transition
                                                    class="mt-1 pl-4 list-disc list-inside space-y-1 text-gray-700 border p-2 rounded ">
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
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6 mb-3">
                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $user->id }})">
                    Delete
                </x-danger-button>
            </div>

            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete User') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this user?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteUser({{ $user->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete User') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
