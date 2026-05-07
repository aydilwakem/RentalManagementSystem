<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1 dark:text-white">
            {{ __('Create Day Tour Reservation') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Reservations', 'url' => route('admin.daytour-reservations-list')],
            ['label' => 'Create Day Tour Reservation', 'url' => route('admin.create-day-tour-reservation')],
        ]" />
    </x-slot>

    <div class="relative flex items-center mb-3 mt-3">
        <h2 class="text-xl font-bold text-green-700 w-full text-center dark:text-green-300">
            Day Tour Reservation
        </h2>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!------------------------- TOUR DATE SECTION -------------------------->
        <div class="justify-center items-center text-center">
            <div class="flex flex-col items-center justify-center gap-4 mb-3">
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Tour Date</label>
                    <input type="date" wire:model.live="tour_date"
                        min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}"
                        class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>
        </div>

        <!------------------------- TOUR SELECTION SECTION -------------------------->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                Select Day Tour
            </h2>

            @if ($tour_date)
                <p class="text-sm text-gray-500 mb-4 dark:text-gray-300">
                    Showing rates for: {{ \Carbon\Carbon::parse($tour_date)->format('M d, Y') }}
                    ({{ $this->getDayType($tour_date) }})
                </p>
            @endif


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($availableTours as $tour)
                    <div class="relative flex">
                        <div class="border-2 rounded-lg overflow-hidden transition-all duration-200 cursor-pointer flex flex-col w-full
                        {{ $selectedTour && $selectedTour->id == $tour->id ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-200 hover:border-gray-300' }}"
                            wire:click="selectTour({{ $tour->id }})">

                            @if ($selectedTour && $selectedTour->id == $tour->id)
                                <div
                                    class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold z-10">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </div>
                            @endif

                            @php
                                $firstImage = isset($tour->images[0]) ? $tour->images[0] : null;
                            @endphp

                            <!-- image -->
                            <div class="flex-shrink-0">
                                <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('images/daytour-default.png') }}"
                                    alt="{{ $tour->name }}" class="w-full h-40 object-cover">
                            </div>

                            <div class="p-4 flex flex-col flex-grow">
                                <h3
                                    class="text-lg font-semibold mb-2 flex-shrink-0
                                    {{ $selectedTour && $selectedTour->id == $tour->id
                                        ? 'text-gray-800 dark:text-gray-900'
                                        : 'text-gray-800 dark:text-white' }}">
                                    {{ $tour->name }}

                                    @if ($tour->activeRates && count($tour->activeRates) > 0)
                                        @php $firstRate = $tour->activeRates->first(); @endphp
                                        - <span class="text-green-700 dark:text-green-400 font-semibold">
                                            ₱{{ number_format($firstRate->adult_rate, 2) }}
                                        </span>
                                    @endif
                                </h3>


                                <!-- Show rates with availability status -->
                                @php
                                    $hasRates = $tour->activeRates && count($tour->activeRates) > 0;
                                    $hasRatesForSelectedDate = false;
                                    $availableRatesForDate = [];

                                    if ($hasRates) {
                                        foreach ($tour->activeRates as $rate) {
                                            $isAvailableForDate = $this->tour_date
                                                ? $rate->day_type === $this->getDayType($this->tour_date)
                                                : true;
                                            if ($isAvailableForDate) {
                                                $hasRatesForSelectedDate = true;
                                                $availableRatesForDate[] = $rate;
                                            }
                                        }
                                    }
                                @endphp

                                <div class="space-y-2 flex-grow flex flex-col justify-end">
                                    @if (!$hasRates)
                                        <!-- No rates configured at all -->
                                        <div
                                            class="text-center p-2 bg-yellow-50 border border-yellow-200 rounded text-yellow-700 text-sm dark:bg-yellow-900 dark:border-yellow-800 dark:text-yellow-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            No rates configured
                                        </div>
                                    @elseif($hasRatesForSelectedDate)
                                        <!-- Has rates for the selected date -->
                                        @foreach ($availableRatesForDate as $rate)
                                            @php
                                                $isSelected =
                                                    $selectedTour &&
                                                    $selectedTour->id == $tour->eid &&
                                                    $selectedRate &&
                                                    $selectedRate->id == $rate->id;
                                            @endphp
                                            <div
                                                class="text-sm {{ $isSelected ? 'text-green-600 font-semibold' : 'text-gray-600 dark:text-gray-500' }} flex-shrink-0">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                        {{ $rate->rate_type === 'with_room' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                                        {{ $rate->rate_name }}
                                                    </span>
                                                    <span
                                                        class="font-semibold font-xs">{{ $tour->package_type === 'with_room' ? 'With Room' : 'Without Room' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Has rates but none for selected date -->
                                        <div
                                            class="text-center p-2 bg-gray-50 border border-gray-200 rounded text-gray-600 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-calendar-times mr-1"></i>
                                            No rates for selected date
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($availableTours->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-300">No day tours available for the selected date.</p>
                </div>
            @endif
        </div>

        <!------------------------- GUEST COUNT SECTION -------------------------->
        @if ($selectedTour && $selectedRate)
            <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    Guest Count
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Adult Selector -->
                    <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg border border-gray-200 dark:border-gray-500">
                        <div class="text-center mb-3">
                            <h4 class="font-semibold text-gray-800 dark:text-white">Adults</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">12 years old and above</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button" wire:click="decrementAdult"
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-500 flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-400 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $adultCount <= 1 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 text-gray-700 dark:text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>

                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-700 dark:text-green-500">{{ $adultCount }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                    ₱{{ number_format($selectedRate->adult_rate, 2) }} / Per Pax
                                </div>
                            </div>

                            <button type="button" wire:click="incrementAdult"
                                class="w-10 h-10 rounded-full dark:bg-green-500 dark:hover:bg-green-600 bg-green-700 flex items-center justify-center hover:bg-green-800 transition text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Child Selector -->
                    <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg border border-gray-200 dark:border-gray-500">
                        <div class="text-center mb-3">
                            <h4 class="font-semibold text-gray-800 dark:text-white">Children</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">3-11 years old</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button" wire:click="decrementKid"
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-500 flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-400 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $kidCount <= 0 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 text-gray-700 dark:text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>

                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-700 dark:text-green-500">{{ $kidCount }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                    ₱{{ number_format($selectedRate->kid_rate, 2) }} / Per Pax
                                </div>
                            </div>

                            <button type="button" wire:click="incrementKid"
                                class="w-10 h-10 rounded-full dark:bg-green-500 dark:hover:bg-green-600 bg-green-700 flex items-center justify-center hover:bg-green-800 transition text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Total Guests Display -->
                <div class="mt-4 text-center">
                    <p class="text-lg font-semibold text-gray-700 dark:text-white">
                        Total Guests: <span class="text-green-600">{{ $totalGuests }}</span>
                    </p>
                    {{-- @if ($selectedRate && $totalGuests > $selectedRate->max_guests)
                    <p class="text-red-500 text-sm mt-1">
                        Maximum guests for this rate is {{ $selectedRate->max_guests }}
                    </p>
                    @endif --}}
                </div>
            </div>
        @endif

        <!------------------------- PRICE SUMMARY SECTION -------------------------->
        @if ($selectedTour && $selectedRate)
            <div class="bg-white shadow-md rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                <h2
                    class="font-bold text-2xl text-green-700 text-center leading-snug mb-2 bg-green-50 py-3 rounded-t-lg shadow-sm dark:bg-green-200">
                    Reservation Summary
                </h2>
                <div class="px-6 py-4">
                    <!-- Tour Details -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Tour Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-base text-gray-700 dark:text-gray-300">
                            <div class="space-y-1">
                                <p><span class="font-medium text-gray-800 dark:text-white">Package:</span>
                                    {{ $selectedTour->name }}</p>
                                <p><span class="font-medium text-gray-800 dark:text-white">Rate:</span>
                                    {{ $selectedRate->rate_name }}</p>
                                <p><span class="font-medium text-gray-800 dark:text-white">Date:</span>
                                    {{ \Carbon\Carbon::parse($tour_date)->format('M d, Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <p><span class="font-medium text-gray-800 dark:text-white">Adults:</span>
                                    {{ $adultCount }}
                                    × ₱{{ number_format($selectedRate->adult_rate, 2) }}</p>
                                @if ($kidCount > 0)
                                    <p><span class="font-medium text-gray-800 dark:text-white">Children:</span>
                                        {{ $kidCount }}
                                        × ₱{{ number_format($selectedRate->kid_rate, 2) }}</p>
                                @endif
                                <p><span class="font-medium text-gray-800 dark:text-white">Total Guests:</span>
                                    {{ $totalGuests }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-600">

                    <!-- Price Breakdown -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                            <span>Adults ({{ $adultCount }}):</span>
                            <span
                                class="font-medium">₱{{ number_format($adultCount * $selectedRate->adult_rate, 2) }}</span>
                        </div>
                        @if ($kidCount > 0)
                            <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                                <span>Children ({{ $kidCount }}):</span>
                                <span
                                    class="font-medium">₱{{ number_format($kidCount * $selectedRate->kid_rate, 2) }}</span>
                            </div>
                        @endif
                        <div
                            class="flex justify-between font-semibold text-gray-900 dark:text-white border-t pt-2 mt-2">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        {{-- <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <span>Convenience Fee (3%):</span>
                                <div class="relative group inline-block">
                                    <i class="fas fa-info-circle text-gray-500 text-xs cursor-pointer"></i>
                                    <div
                                        class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-xs text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                        A processing fee is applied for secure online payments.
                                    </div>
                                </div>
                            </div>
                            <span>₱{{ number_format($convenience_fee, 2) }}</span>
                        </div> --}}
                        <div
                            class="flex justify-between text-xl font-semibold text-gray-800 dark:text-white border-t pt-3 mt-3">
                            <span>Total Amount:</span>
                            <span class="text-green-700 dark:text-green-500">₱{{ number_format($total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!------------------------- GUEST DETAIL SECTION -------------------------->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-300">
                Guest Details
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4 mb-4">
                <!-- First Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">First Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="first_name" placeholder="Ex. Juan"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Middle Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Middle Name</label>
                    <input type="text" wire:model="middle_name" placeholder="Ex. Mercado"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('middle_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Last Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" placeholder="Ex. juan.delacruz@example.com"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Contact
                        Number <span class="text-red-500">*</span></label>
                    <input type="tel" inputmode="numeric" maxlength="11" wire:model="contact_number"
                        placeholder="Ex. 09123456789"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('contact_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Country <span
                            class="text-red-500">*</span></label>
                    <select wire:model="country"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="" disabled selected>Select a country</option>
                        @foreach ($countries as $countryOption)
                            <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Company Name</label>
                    <input type="text" wire:model="company_name" placeholder="Ex. ABC Corporation"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('company_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <!-- Source of Hearing -->
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Heard From <span
                            class="text-red-500">*</span></label>
                    <select wire:model="heard_from"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select an option</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Tiktok">Tiktok</option>
                        <option value="Youtube">Youtube</option>
                        <option value="Google">Google</option>
                    </select>
                    @error('heard_from')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <!-- Source of Booking -->
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Reservation
                        Source <span class="text-red-500">*</span></label>
                    <select wire:model="reservation_source"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select an option</option>
                        <option value="AirBnb">AirBnb</option>
                        <option value="Website">Website</option>
                        <option value="Facebook Messenger">Facebook Messenger</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Walk-In">Walk-In</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('reservation_source')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Guests Section -->
        {{-- <div
            class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex flex-col space-y-2 w-full">
                <div class="flex justify-between">
                    <div class="font-semibold text-xl text-green-700 dark:text-green-200">
                        Additional Guests (Optional)
                    </div>
                    <div class="mt-4">
                        <x-button type="button" wire:click="openGuestModal" icon="fas fa-plus">
                            Add Guest
                        </x-button>
                    </div>
                </div>

                @error('guests')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror

                <!-- Displaying Added Guests -->
                <div class="mt-6">
                    @if (count($guests) > 0)
                    <ol
                        class="space-y-3 list-decimal pl-6 text-gray-700 mb-3 p-3 bg-white rounded-2xl border border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                        @foreach ($guests as $guest)
                        <li class="me-2 dark:text-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-700 flex dark:text-gray-200">
                                    {{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}
                                </div>
                                <div class="space-x-5 flex items-center">
                                    <button wire:click="editGuest({{ $loop->index }})"
                                        class="inline-flex text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500 hover:underline font-sm transition duration-150">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>

                                    <button wire:click="deleteGuest({{ $loop->index }})" class="inline-flex items-center text-red-500 hover:text-red-700 hover:underline font-sm transition duration-150
                                                dark:text-red-400 dark:hover:text-red-600">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <p class="text-center text-gray-500 mt-6 mb-6 dark:text-gray-200">No additional guests added yet.
                    </p>
                    @endif
                </div>
            </div>
        </div> --}}

        <!------------------------- TERMS AND SUBMIT SECTION -------------------------->

        {{-- <div class="flex items-center mb-4">
            <input type="checkbox" wire:model="terms" class="mr-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">
                I agree to the terms and conditions
            </label>
        </div>
        @error('terms')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror --}}

        <div class="flex justify-between items-center space-y-2">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>

            <x-button type="button" wire:click="createDayTourReservation" wire:loading.attr="disabled">
                <div class="flex items-center justify-center">
                    <!-- Spinner -->
                    <span wire:loading class="mr-2" wire:target="createDayTourReservation">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                            </path>
                        </svg>
                    </span>
                    <!-- Button Text -->
                    <span wire:loading.remove wire:target="createDayTourReservation">
                        Create Day Tour Reservation
                    </span>
                </div>
            </x-button>
        </div>
    </div>

    <!------------------------- MODALS SECTION ------------------------->

    <!-- Add Guest Modal -->
    @if ($guestModal)
        <div id="guestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden dark:bg-gray-700">

                <!-- Header -->
                <div
                    class="flex justify-between items-center border-b bg-green-50 border-gray-200 px-6 py-4 dark:border-gray-500 dark:bg-gray-800">
                    <h2 class="text-2xl font-semibold text-green-700 dark:text-green-300">Enter Guest Details</h2>

                    <button wire:click="closeGuestModal"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none dark:text-gray-200 dark:hover:text-gray-400">
                        &times;
                    </button>
                </div>

                <div class="p-4">
                    <!-- Guest Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">First
                                Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="guest_first_name" placeholder="Ex. Juan"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600"
                                required>
                            @error('guest_first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Middle
                                Name</label>
                            <input type="text" wire:model="guest_middle_name" placeholder="Ex. Mercado"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            @error('guest_middle_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Last
                                Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="guest_last_name" placeholder="Ex. Dela Cruz"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600"
                                required>
                            @error('guest_last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Suffix</label>
                            <input type="text" wire:model="guest_suffix" placeholder="Ex. Jr., Sr., III"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            @error('guest_suffix')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Guest Type -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Guest Type
                            <span class="text-red-500">*</span></label>
                        <select wire:model="guest_type_id"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Guest Type</option>
                            @foreach ($guest_types as $type)
                                <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('guest_type_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Gender <span
                                class="text-red-500">*</span></label>
                        <select wire:model="guest_gender"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Prefer not to say</option>
                        </select>
                        @error('guest_gender')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Residency Status-->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Residency Status<span
                                class="text-red-500">*</span></label>
                        <select wire:model.live="guest_residency"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Residency Status</option>
                            <option value="local">Local</option>
                            <option value="foreigner">Foreigner</option>
                        </select>
                        @error('guest_residency')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                                class="text-red-500">*</span></label>
                        <select wire:model="guest_country_of_origin"
                            @if ($guest_residency === 'local') readonly disabled @endif
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select a country</option>
                            @foreach ($countries as $countryOption)
                                <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                            @endforeach
                        </select>
                        @error('guest_country_of_origin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center gap-2 mt-6">
                        <x-button type="button" wire:click="closeGuestModal"
                            class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                            Cancel
                        </x-button>
                        <x-button type="button" wire:click="addMultipleGuests">
                            Add Guest
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Guest Modal -->
    @if ($editGuestModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden dark:bg-gray-700">

                <!-- Header -->
                <div
                    class="flex justify-between items-center border-b bg-green-50 border-gray-200 px-6 py-4 dark:border-gray-500 dark:bg-gray-800">
                    <h2 class="text-2xl font-semibold text-green-700 dark:text-green-300">Edit Guest Details</h2>

                    <button wire:click="$set('editGuestModal', false)"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none dark:text-gray-200 dark:hover:text-gray-400">
                        &times;
                    </button>
                </div>

                <div class="p-4">

                    <!-- Guest Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">First
                                Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="editingGuest.guest_first_name"
                                placeholder="Ex. Juan"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600"
                                required>
                            @error('editingGuest.guest_first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Middle
                                Name</label>
                            <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                                placeholder="Ex. Mercado"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            @error('editingGuest.guest_middle_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Last
                                Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="editingGuest.guest_last_name"
                                placeholder="Ex. Dela Cruz"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600"
                                required>
                            @error('editingGuest.guest_last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Suffix</label>
                            <input type="text" wire:model.defer="editingGuest.guest_suffix"
                                placeholder="Ex. Jr., Sr., III"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            @error('editingGuest.guest_suffix')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Guest Type -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Guest Type
                            <span class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuest.guest_type_id"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Guest Type</option>
                            @foreach ($guest_types as $type)
                                <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('editingGuest.guest_type_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Gender <span
                                class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuest.guest_gender"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Prefer not to say</option>
                        </select>
                        @error('editingGuest.guest_gender')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Residency Status-->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Residency Status<span
                                class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuest.guest_residency"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select Residency Status</option>
                            <option value="local">Local</option>
                            <option value="foreigner">Foreigner</option>
                        </select>
                        @error('editingGuest.guest_residency')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Country of Origin -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                                class="text-red-500">*</span></label>
                        <select wire:model.defer="editingGuest.guest_country_of_origin"
                            @if ($guest_residency === 'local') readonly disabled @endif
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">Select a country</option>
                            @foreach ($countries as $countryOption)
                                <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                            @endforeach
                        </select>
                        @error('editingGuest.guest_country_of_origin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between gap-2 mt-6">
                        <x-ghost-button wire:click="$set('editGuestModal', false)">
                            Cancel
                        </x-ghost-button>
                        <x-button wire:click="updateGuest">
                            Save Changes
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
