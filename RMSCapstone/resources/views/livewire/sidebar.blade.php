<div class="h-full flex flex-col">

    <div x-data="{ store: $store.sidebar }" class="h-screen mx-auto antialiased flex justify-between">
        <!-- Mobile Menu Toggle -->
        <button @click="$store.sidebar.navOpen = !$store.sidebar.navOpen"
            class="sm:hidden absolute top-5 right-5 focus:outline-none">
            <!-- Menu Icons -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                x-bind:class="$store.sidebar.navOpen ? 'hidden' : ''" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>

            <!-- Close Menu -->
            <svg x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                x-bind:class="$store.sidebar.navOpen ? '' : 'hidden'" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Desktop Menu Container  -->
        <div class="h-screen bg-primary-800 fixed sm:relative flex flex-col w-64 z-40 transition-all duration-300"
            x-bind:class="{
                'w-64': $store.sidebar.full,
                'w-64 sm:w-20': !$store.sidebar.full,
                'top-0 left-0': $store.sidebar.navOpen,
                'top-0 -left-64 sm:left-0': !$store.sidebar.navOpen
            }">

            <!-- Header -->
            <div class="flex items-center space-x-2 px-4 py-3 border-b mb-2">
                <img src="{{ asset('storage/' . $logoPath) }}" alt=" {{ $companyName }}"
                    class="h-8 w-8 object-cover rounded-full">
                <h1 class="text-white font-semibold overflow-hidden whitespace-nowrap transition-all duration-300"
                    x-bind:class="$store.sidebar.full ? 'text-lg w-auto ml-2' : 'w-0 ml-0'">
                    {{ $companyName }}
                </h1>
            </div>

            <div class="px-4 space-y-1 flex flex-col overflow-y-auto max-h-[36rem]">
                <!-- SideBar Toggle -->
                <button @click="$store.sidebar.full = !$store.sidebar.full"
                    class="hidden sm:block focus:outline-none absolute p-1 -right-3 top-10 bg-primary-700 border rounded-full shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transition-all duration-300 text-white transform"
                        x-bind:class="$store.sidebar.full ? 'rotate-90' : '-rotate-90 '" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dashboard -->
                @can('dashboard-view')
                <div>
                    <a href="{{ route('dashboard') }}">
                        <div class="relative flex items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                                    {{ Route::is('dashboard') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                            <i class="fa-solid fa-house"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Dashboard
                            </h1>
                        </div>
                    </a>
                </div>
                @endcan

                <!-- Reservations -->
                <div x-data="dropdown" class="relative">
                    @can('new-reservation-list')
                    <div @click="toggle('reservations')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                            {{ Route::is('admin.reservations-list*') || Route::is('admin.activities*') || Route::is('admin.view-promo-codes*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-calendar"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Reservations
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Reservations Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        <!-- Reservations -->
                        @can('new-reservation-list')
                        <a href="{{ route('admin.reservations-list') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.reservations-list') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Reservations</h1>
                        </a>
                        @endcan

                        <!-- Activities -->
                        @can('activity-list')
                        <a href="{{ route('admin.activities') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.activities') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Activities</h1>
                        </a>
                        @endcan

                        @can('promo-code-list')
                        <!-- Promo Codes -->
                        <a href="{{ route('admin.view-promo-codes') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.view-promo-codes') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Promo Codes</h1>
                        </a>
                        @endcan

                        @can('service-list')
                        <!-- Services -->
                        <a href="{{ route('admin.services') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.services') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Services</h1>
                        </a>
                        @endcan

                    </div>
                </div>

                <!-- Rooms Menu -->
                <div x-data="dropdown" class="relative">
                    @can('room-list')
                    <div @click="toggle('rooms')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                        {{ Route::is('admin.rooms*') || Route::is('admin.room-categories*') || Route::is('admin.room-rates*') || Route::is('admin.amenities*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-bed"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Rooms
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Rooms Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        @can('room-list')
                        <a href="{{ route('admin.rooms') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.rooms') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Rooms</h1>
                        </a>
                        @endcan

                        @can('room-category-list')
                        <a href="{{ route('admin.room-categories') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.room-categories') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Room Categories</h1>
                        </a>
                        @endcan

                        {{-- @can('room-rate-list')
                        <a href="{{ route('admin.room-rates') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.room-rates') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Room Rates</h1>
                        </a>
                        @endcan --}}

                        @can('amenity-list')
                        <a href="{{ route('admin.amenities') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.amenities') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Amenities</h1>
                        </a>
                        @endcan
                    </div>
                </div>


                <!-------------------- Rentals ------------------------->


                <!-- Houses Menu -->
                <div x-data="dropdown" class="relative">
                    @can('house-list')
                    <div @click="toggle('houses')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                        {{ Route::is('admin.properties*') || Route::is('admin.leases*') || Route::is('admin.tenants*') || Route::is('admin.features*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-building"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Properties
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Houses Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        {{-- Features List --}}
                        @can('leases-list')
                        <a href="{{ route('admin.leases') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.leases') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Leases</h1>
                        </a>
                        @endcan


                        {{-- @can('house-category-list')
                        <a href="{{ route('admin.house-categories') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('#') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">House Categories</h1>
                        </a>
                        @endcan --}}

                        @can('tenant-list')
                        <a href="{{ route('admin.tenants') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.tenants') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Tenants</h1>
                        </a>
                        @endcan

                        @can('house-list')
                        <a href="{{ route('admin.properties') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.properties') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Properties</h1>
                        </a>
                        @endcan

                        {{-- Features List --}}
                        @can('house-features-list')
                        <a href="{{ route('admin.features') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.features') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Features</h1>
                        </a>
                        @endcan

                    </div>
                </div>

                <!-- Events Menu -->
                <div x-data="dropdown" class="relative">
                    @can('event-list')
                    <div @click="toggle('events')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                            {{ Route::is('admin.events*') || Route::is('admin.event-halls*') || Route::is('admin.event-categories*') || Route::is('admin.inclusions*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-calendar-plus"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Events
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Events Dropdown Content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        @can('event-list')
                        <a href="{{ route('admin.events') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.events') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Events</h1>
                        </a>
                        @endcan


                        @can('event-hall-list')
                        <a href="{{ route('admin.event-halls') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.event-halls') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Event Halls</h1>
                        </a>
                        @endcan

                        @can('event-category-list')
                        <a href="{{ route('admin.event-categories') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.event-categories') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Event Categories</h1>
                        </a>
                        @endcan

                        @can('event-inclusions-list')
                        <a href="{{ route('admin.inclusions') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.inclusions') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Inclusions</h1>
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Payments Menu -->
                <div x-data="dropdown" class="relative">
                    @can('house-list')
                    <div @click="toggle('payments')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                                {{ Route::is('admin.payments-list*') || Route::is('admin.invoice-list*') || Route::is('admin.payments*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-money-bill"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Billing & Payments
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Payments Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        <!-- Payments -->
                        @can('payments-list')
                        <a href="{{ route('admin.payments-list') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.payments-list') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Payments</h1>
                        </a>
                        @endcan


                        <!-- Invoice -->
                        @can('invoices-list')
                        <a href="{{ route('admin.invoice-list') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.invoice-list') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Invoices</h1>
                        </a>
                        @endcan

                        <!-- Payment Methods -->
                        @can('payment-method-list')
                        <a href="{{ route('admin.payments') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.payments') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Payment Methods</h1>
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Maintenance -->
                @can('maintenance-list')
                <div>
                    <a href="{{ route('admin.maintenances') }}" wire:navigate>
                        <div
                            class="relative flex items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                                    {{ Route::is('admin.maintenances') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                            <i class="fa-solid fa-broom"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">Maintenance</h1>
                        </div>
                    </a>
                </div>
                @endcan

                <!-- Reports Menu -->
                <div x-data="dropdown" class="relative">
                    @can('reports')
                    <div @click="toggle('report')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                                {{ Route::is('admin.reservation-reports*') || Route::is('event-reports*') || Route::is('admin.feedback*') || Route::is('admin.lease-reports*') || Route::is('admin.invoice-reports*') || Route::is('admin.payment-reports*') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-file-invoice"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Reports
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Reports Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">

                        <!-- Reservation Report -->
                        @can('reservation-reports')
                        <a href="{{ route('admin.reservation-reports') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.reservation-reports') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Reservations</h1>
                        </a>
                        @endcan

                        <!-- Event Report -->
                        @can('event-reports')
                        <a href="{{ route('admin.event-reports') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('event-reports') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Events</h1>
                        </a>
                        @endcan

                        <!-- Lease Report -->
                        @can('lease-reports')
                        <a href="{{ route('admin.lease-reports') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.lease-reports') ? 'underline text-gray-200' : 'hover:text-gray-200 hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Lease</h1>
                        </a>
                        @endcan

                        <!-- Feedback -->
                        @can('feedback')
                        <a href="{{ route('admin.feedback') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.feedback') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Feedback</h1>
                        </a>
                        @endcan

                        <!-- Invoice Report -->
                        <a href="{{ route('admin.invoice-reports') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.invoice-reports') ? 'underline text-gray-200' : 'hover:text-gray-200 hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Invoice</h1>
                        </a>

                        <!-- Payment Report -->
                        <a href="{{ route('admin.payment-reports') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.payment-reports') ? 'underline text-gray-200' : 'hover:text-gray-200 hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Payments</h1>
                        </a>

                    </div>
                </div>

                <!-- Settings Menu -->
                <div x-data="dropdown" class="relative">
                    @can('branding-view')
                    <div @click="toggle('settings')"
                        class="flex justify-between items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                                {{ Route::is('admin.manage-users') || Route::is('admin.branding') || Route::is('admin.appearance') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-cogs"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">
                                Settings
                            </h1>
                        </div>
                        <svg x-cloak x-bind:class="$store.sidebar.full ? '' : 'sm:hidden'"
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endcan

                    <!-- Settings Dropdown content -->
                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-bind:class="$store.sidebar.full ? expandedClass : shrinkedClass"
                        class="text-white bg-primary-600 rounded-lg shadow-sm mt-2">


                        @can('user-list')
                        <a href="{{ route('admin.manage-users') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.manage-users') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">User Management</h1>
                        </a>
                        @endcan

                        @can('branding-view')
                        <a href="{{ route('admin.branding') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.payments') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Branding</h1>
                        </a>
                        @endcan

                        @can('appearance-view')
                        <a href="{{ route('admin.appearance') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.appearance') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Appearance</h1>
                        </a>
                        @endcan

                        @can('activity-logs-view')
                        <a href="{{ route('admin.activity-logs') }}" wire:navigate
                            class="block px-3 py-2 {{ Route::is('admin.activity-logs') ? 'underline text-white' : 'hover:text-white hover:underline' }} rounded-lg transition">
                            <h1 class="cursor-pointer">Activity Logs</h1>
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Account Management -->
                <div class="relative">
                    <a href="{{ route('profile.show') }}" wire:navigate>
                        <div class="flex items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                            {{ Route::is('profile.show') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                            <i class="fa-solid fa-user"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">Profile</h1>
                        </div>
                    </a>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}" wire:navigate>
                        <div
                            class="flex items-center space-x-2 rounded-md p-2 cursor-pointer hover:text-white hover:bg-primary-700
                            {{ Route::is('api-tokens.index') ? 'text-white bg-primary-600' : 'text-gray-400' }}">
                            <i class="fa-solid fa-key"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">API Tokens</h1>
                        </div>
                    </a>
                    @endif
                </div>

                <!-- Logout -->
                <div class="absolute inset-x-0 bottom-2 px-4">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit"
                            class="relative flex w-full items-center justify-center text-white border hover:text-white hover:bg-red-700 space-x-2 rounded-md p-2 cursor-pointer">
                            <h1 ax-cloak x-show="$store.sidebar.full">Logout</h1>
                            <i class="fa-solid fa-sign-out"></i>
                        </button>
                    </form>

                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-600 mt-3"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" wire:navigate>
                        <div @click="$store.sidebar.active = 'team-settings'"
                            class="relative flex items-center hover:text-white hover:bg-primary-700 space-x-2 rounded-md p-2 cursor-pointer"
                            x-bind:class="{
                                    'text-white bg-primary-600': $store.sidebar.active == 'team-settings',
                                    'text-gray-400 ': $store.sidebar.active != 'team-settings'
                                }">
                            <i class="fa-solid fa-users"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">Team Settings</h1>
                        </div>
                    </a>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                    <a href="{{ route('teams.create') }}" wire:navigate>
                        <div @click="$store.sidebar.active = 'create-team'"
                            class="relative flex items-center hover:text-white hover:bg-primary-700 space-x-2 rounded-md p-2 cursor-pointer"
                            x-bind:class="{
                                        'text-white bg-primary-600': $store.sidebar.active == 'create-team',
                                        'text-gray-400 ': $store.sidebar.active != 'create-team'
                                    }">
                            <i class="fa-solid fa-plus"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">Create New Team</h1>
                        </div>
                    </a>
                    @endcan

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                    <a href="{{ route('teams.create') }}" wire:navigate>
                        <div @click="$store.sidebar.active = 'create-team'"
                            class="relative flex items-center hover:text-white hover:bg-primary-700 space-x-2 rounded-md p-2 cursor-pointer"
                            x-bind:class="{
                                        'text-white bg-primary-600': $store.sidebar.active == 'create-team',
                                        'text-gray-400 ': $store.sidebar.active != 'create-team'
                                    }">
                            <i class="fa-solid fa-plus"></i>
                            <h1 x-cloak x-show="$store.sidebar.full">Create New Team</h1>
                        </div>
                    </a>
                    @endcan

                    @if (Auth::user()->allTeams()->count() > 1)
                    <div class="border-t border-gray-600 mt-3"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                    <x-switchable-team :team="$team" component="sidebar-link" />
                    @endforeach
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
