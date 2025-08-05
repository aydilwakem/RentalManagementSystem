<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Role') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Roles', 'url' => route('admin.manage-users')],
            ['label' => 'Create Role', 'url' => route('admin.create-role')],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-1">
        <div
            class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Role</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.manage-users') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form container -->
            <form wire:submit.prevent="saveRole">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name of Role -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-md font-medium text-gray-900 dark:text-gray-200">Role
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Super Admin" required>
                    </div>

                    <!-- Permissions List -->
                    <div class="sm:col-span-2">
                        <label for="permissions"
                            class="block mb-2 text-xl font-bold text-green-800 dark:text-green-300">Permissions</label>
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
                                'New Reservations' => fn($p) => str_starts_with(
                                $p->name,
                                'new-reservation-',
                                ),
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
                                !str_starts_with($p->name, 'event-hall') &&
                                !str_starts_with($p->name, 'event-inclusions'),
                                'Event Categories' => fn($p) => str_starts_with($p->name, 'event-category'),
                                'Event Halls' => fn($p) => str_starts_with($p->name, 'event-hall'),
                                'Event Inclusions' => fn($p) => str_starts_with($p->name, 'event-inclusions'),
                                ],

                                'Property Management' => [
                                'Houses' => fn($p) => str_starts_with($p->name, 'house-') &&
                                !str_starts_with($p->name, 'house-category'),
                                'Tenants' => fn($p) => str_starts_with($p->name, 'tenant'),
                                'Maintenance' => fn($p) => str_starts_with($p->name, 'maintenance'),
                                'Leases' => fn($p) => str_starts_with($p->name, 'leases'),
                                'House Features' => fn($p) => str_starts_with($p->name, 'house-features'),
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
                                'Services' => [
                                'Service' => fn($p) => str_starts_with($p->name, 'service'),
                                ],
                                'Activity Logs' => [
                                'Activity Logs' => fn($p) => str_starts_with($p->name, 'activity-logs'),
                                ],
                                'Promo Codes' => [
                                'Promo Codes' => fn($p) => str_starts_with($p->name, 'promo-code'),
                                ],
                                'Reports' => [
                                'Reports' => fn($p) => str_starts_with($p->name, 'reports') || str_ends_with($p->name,
                                'reports'),
                                'Feedback' => fn($p) => str_starts_with($p->name, 'feedback'),
                                ],
                                ];
                                @endphp

                                @foreach ($permissionGroups as $category => $group)
                                <div class="mb-6">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-2 dark:text-gray-200">{{ $category
                                        }}</h2>

                                    @foreach ($group as $groupLabel => $filter)
                                    <div x-data="{ open: false }"
                                        class="border rounded-lg bg-gray-50 mb-2 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                        <button type="button" @click="open = !open"
                                            class="w-full text-left px-4 py-2 flex justify-between items-center">
                                            {{ $groupLabel }}
                                            <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition class="p-4 space-y-2">
                                            @foreach ($permissions->filter($filter) as $permission)
                                            <label class="flex items-center space-x-2 text-sm">
                                                <input type="checkbox" wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}" class="rounded border-gray-300">
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
                            <x-ghost-button onclick="history.back()" type="button">
                                Cancel
                            </x-ghost-button>
                            <x-button type="submit" class="mt-6" wire:loading.attr="disabled" wire:target="image">
                                Create Role
                            </x-button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>