<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <div class=" rounded-lg p-6 mx-auto mb-6 mt-6">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add new role</h2>

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
        @endif

        <form wire:submit.prevent="saveRole">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Role -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Role Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter Role" required>
                </div>

                <!-- Permissions List -->
                <div class="sm:col-span-2">
                    <label for="permissions" class="block mb-2 text-xl font-medium text-gray-900">Permissions</label>
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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
                                        'Confirmed Reservations' => fn($p) => str_starts_with(
                                            $p->name,
                                            'confirmed-reservation',
                                        ),
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

                                       'Feedback' => [
                                        'Feedback' => fn($p) => str_starts_with($p->name, 'feedback'),
                                    ],
                                ];
                            @endphp

                            @foreach ($permissionGroups as $category => $group)
                                <div class="mb-6">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $category }}</h2>

                                    @foreach ($group as $groupLabel => $filter)
                                        <div x-data="{ open: false }" class="border rounded-lg bg-gray-50 mb-2">
                                            <button type="button" @click="open = !open"
                                                class="w-full text-left px-4 py-2 flex justify-between items-center">
                                                {{ $groupLabel }}
                                                <svg :class="{ 'rotate-180': open }"
                                                    class="h-4 w-4 transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition class="p-4 space-y-2">
                                                @foreach ($permissions->filter($filter) as $permission)
                                                    <label class="flex items-center space-x-2 text-sm">
                                                        <input type="checkbox" wire:model="selectedPermissions"
                                                            value="{{ $permission->id }}"
                                                            class="rounded border-gray-300">
                                                        <span>{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach


                        </div>
                    </div>
                    <div class="flex justify-between items-center space-y-2 mt-8">
                        <x-button onclick="history.back()" type="button"
                            class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                            Cancel
                        </x-button>
                        <x-button type="submit" class="mt-6" wire:loading.attr="disabled" wire:target="image">
                            Add Role
                        </x-button>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
