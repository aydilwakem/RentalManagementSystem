<nav x-data="{ open: false }" class="bg-primary-800 border-b border-gray-300 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <a href="{{ route('guest.homepage') }}" class="shrink-0 flex items-center hover:opacity-90 transition">
                <img src="{{ asset('image/' . $logoPath) }}"
                     alt="{{ $companyName }}"
                     class="block h-9 w-auto rounded-full" />
                <span class="ml-2 text-xl font-semibold text-yellow-50">
                    {{ $companyName }}
                </span>
            </a>


            <!-- Navigation Links -->
            <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link href="{{ route('guest.homepage') }}" :active="request()->routeIs('guest.homepage')" wire:navigate>
                    {{ __('Home') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.reservation-form') }}" :active="request()->routeIs('guest.reservation-form')" wire:navigate>
                    {{ __('Rooms') }}
                </x-nav-link>

                {{-- <div x-data="{ open: false }" class="relative">
                    <!-- Toggle -->
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center text-gray-800 hover:text-green-700 font-semibold transition">
                        <span>Activities</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4 ml-1 transition-transform duration-200"
                            :class="{ 'rotate-180': open }">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" x-transition.origin.top
                        class="absolute left-0 mt-3 w-64 bg-white border border-green-100 rounded-2xl shadow-xl p-4 z-50">

                        <div class="space-y-2">
                            <a href="{{ route('guest.day-tour-reservation') }}" wire:navigate
                                class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-700 transition">
                                <span>Day Tour</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ route('guest.activities') }}" wire:navigate
                                class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-700 transition">
                                <span>Free Activities</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div> --}}

                <x-nav-link href="{{ route('guest.activities') }}" :active="request()->routeIs('guest.activities')"
                    wire:navigate>
                    {{ __('Activities') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.day-tour-reservation') }}" :active="request()->routeIs('guest.day-tour-reservation')"
                    wire:navigate>
                    {{ __('Day Tour') }}
                </x-nav-link>

                {{-- <x-nav-link href="{{ route('guest.houses') }}" :active="request()->routeIs('guest.houses')"
                    wire:navigate>
                    {{ __('Spaces') }}
                </x-nav-link> --}}

                <x-nav-link href="{{ route('guest.event-halls') }}" :active="request()->routeIs('guest.event-halls')" wire:navigate>
                    {{ __('Event Halls') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.feedback-form') }}" :active="request()->routeIs('guest.feedback-form')" wire:navigate>
                    {{ __('Feedback Form') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.about-us') }}" :active="request()->routeIs('guest.about-us')" wire:navigate>
                    {{ __('About Us') }}
                </x-nav-link>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('guest.homepage') }}" :active="request()->routeIs('guest.homepage')" wire:navigate>
                {{ __('Home') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.reservation-form') }}" :active="request()->routeIs('guest.reservation-form')" wire:navigate>
                {{ __('Rooms') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.activities') }}" :active="request()->routeIs('guest.activities')" wire:navigate>
                {{ __('Activities') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.day-tour-reservation') }}" :active="request()->routeIs('guest.day-tour-reservation')" wire:navigate>
                {{ __('Day Tour') }}
            </x-responsive-nav-link>

            {{-- <x-responsive-nav-link href="{{ route('guest.houses') }}" :active="request()->routeIs('guest.houses')"
                wire:navigate>
                {{ __('Spaces') }}
            </x-responsive-nav-link> --}}

            <x-responsive-nav-link href="{{ route('guest.event-halls') }}" :active="request()->routeIs('guest.event-halls')" wire:navigate>
                {{ __('Event Halls') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.feedback-form') }}" :active="request()->routeIs('guest.feedback-form')" wire:navigate>
                {{ __('Event Halls') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.about-us') }}" :active="request()->routeIs('guest.about-us')" wire:navigate>
                {{ __('About Us') }}
            </x-responsive-nav-link>
        </div>

    </div>
</nav>
