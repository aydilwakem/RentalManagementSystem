<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     class="w-full z-50 transition-all duration-300 {{ request()->routeIs('guest.homepage') ? 'fixed top-0 border-transparent' : 'sticky top-0 border-gray-300 bg-primary-800' }}"
     :class="{
         @if(request()->routeIs('guest.homepage'))
             'bg-gradient-to-b from-black/60 to-transparent': !scrolled && !open,
             'bg-primary-800 border-gray-300 shadow-md': scrolled || open
         @endif
     }">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <a href="{{ route('guest.homepage') }}" class="shrink-0 flex items-center hover:opacity-90 transition">
                <img src="{{ asset('storage/' . $logoPath) }}"
                     alt="{{ $companyName }}"
                     class="block h-9 w-auto rounded-full" />
                <span class="ml-2 text-xl font-semibold text-white">
                    {{ $companyName }}
                </span>
            </a>


            <!-- Navigation Links -->
            <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link href="{{ route('guest.homepage') }}" :active="request()->routeIs('guest.homepage')" wire:navigate>
                    {{ __('Home') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.reservation-form') }}" :active="request()->routeIs('guest.reservation-form')" wire:navigate >
                    {{ __('Rooms') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.activities') }}" :active="request()->routeIs('guest.activities')" wire:navigate>
                    {{ __('Activities') }}
                </x-nav-link>

                <x-nav-link href="{{ route('guest.day-tour-reservation') }}" :active="request()->routeIs('guest.day-tour-reservation')" wire:navigate>
                    {{ __('Day Tour') }}
                </x-nav-link>

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
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-primary-800 border-t border-gray-700">
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

            <x-responsive-nav-link href="{{ route('guest.event-halls') }}" :active="request()->routeIs('guest.event-halls')" wire:navigate>
                {{ __('Event Halls') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.feedback-form') }}" :active="request()->routeIs('guest.feedback-form')" wire:navigate>
                {{ __('Feedback Form') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('guest.about-us') }}" :active="request()->routeIs('guest.about-us')" wire:navigate>
                {{ __('About Us') }}
            </x-responsive-nav-link>
        </div>

    </div>
</nav>
