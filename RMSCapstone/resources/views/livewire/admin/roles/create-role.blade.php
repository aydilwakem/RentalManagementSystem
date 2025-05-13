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
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter Role" required>
                </div>

                <!-- Permissions List -->
                <div class="sm:col-span-2">
                    <label for="permissions" class="block mb-2 text-sm font-medium text-gray-900">Permissions</label>
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Rooms -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Rooms
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => preg_match('/^room-(?!rate|category)/', $p->name)) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Room Categories -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Room Categories
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'room-category')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Room Rates -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Room Rates
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'room-rate')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Amenities -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Amenities
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'amenity')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Events -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Events
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'event-') && !str_starts_with($p->name, 'event-category') && !str_starts_with($p->name, 'event-hall')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Event Category -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Event Categories
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'event-category')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Event Hall -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Event Halls
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'event-hall')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Activity -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Activities
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'activity')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Maintenance -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Maintenance
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'maintenance')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>



                            <!-- Payment Methods -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Payment Methods
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'payment-method')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Roles -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Roles
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'role')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Users -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Users
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'user')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- House -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Houses
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'house-') && !str_starts_with($p->name, 'house-category')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>


                            <!-- Leases -->
                            {{-- <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Leases
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'lease-')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div> --}}



                            <!-- Tenants -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Tenants
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'tenant')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Dashboard -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Dashboard
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'dashboard')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- New Reservations -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    New Reservations
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'new-reservation-')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Confirmed Reservations -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Confirmed Reservations
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'confirmed-reservation')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- On-going Bookings -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    On-going Bookings
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'on-going')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Old Bookings -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Old Bookings
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'old')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Settings -->
                            <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Settings
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'appearance-view')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Payments -->
                            {{-- <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Payments
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'payments')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div> --}}

                            <!-- Invoices -->
                            {{-- <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                    Invoices
                                    <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 space-y-2">
                                    @foreach ($permissions->filter(fn($p) => str_starts_with($p->name, 'invoice')) as $permission)
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" wire:model="selectedPermissions"
                                                value="{{ $permission->id }}" class="rounded border-gray-300">
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div> --}}

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
        </form>
    </div>
