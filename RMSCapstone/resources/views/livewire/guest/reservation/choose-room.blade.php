<div class="w-full flex justify-center">
    <div class="step-one w-full">

        <!-- Search and Filter Section -->
        <div x-data="{ open: false }" class="mb-4 p-3 border bg-white rounded-xl relative">

            <!-- Top bar with icons -->
            <div class="flex items-center justify-between">
                <h2 class="text-md font-semibold text-gray-700 flex items-center gap-2">
                    Room Filters
                </h2>
                <!-- Expand / Collapse Button -->
                <button @click="open = !open"
                    class="flex items-center justify-center w-9 h-9 rounded-full border transition duration-200
                    {{ (!empty($searchQuery) || !empty($roomCategoryFilter) || !empty($idealGuestFilter) || !empty($priceSort))
                        ? 'bg-green-100 border-green-300 text-green-700 hover:bg-green-200'
                        : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-100'
                    }}"
                    title="Show filters">

                    <template x-if="!open">
                        <i class="fas fa-sliders-h"></i>
                    </template>
                    <template x-if="open">
                        <i class="fas fa-times"></i>
                    </template>
                </button>
            </div>

            <!-- Collapsible Filter Panel -->
            <div x-show="open" x-transition.opacity.scale.80 class="mt-4 space-y-5">
                <!-- Search & Filters Row -->
                <div class="flex flex-col md:flex-row gap-5 items-start md:items-end justify-between">

                    <!-- Search by Name -->
                    <div class="w-full md:w-auto flex-1 min-w-[250px]">
                        <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-search text-gray-600 mr-1"></i> Search by Room Name
                        </label>
                        <div class="relative">
                            <input type="text" id="searchQuery" wire:model.live="searchQuery"
                                placeholder="Enter room name"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                @if ($searchQuery)
                                    <button wire:click="$set('searchQuery', '')"
                                        class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                                        type="button">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                @else
                                    <i class="fas fa-search text-gray-400 text-sm"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Room Category Filter -->
                    <div class="w-full md:w-auto flex-1 min-w-[180px]">
                        <label for="property_category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tags text-gray-600 mr-1"></i> Category
                        </label>
                        <select id="property_category_id" wire:model.live="roomCategoryFilter"
                            class="w-full p-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach ($roomCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ideal Guest Filter -->
                    <div class="w-full md:w-auto flex-1 min-w-[180px]">
                        <label for="idealGuestFilter" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-users text-gray-600 mr-1"></i> Ideal Guests
                        </label>
                        <select id="idealGuestFilter" wire:model.live="idealGuestFilter"
                            class="w-full p-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 cursor-pointer">
                            <option value="">Any Number</option>
                            @foreach ($this->idealGuestOptions as $guestCount)
                                <option value="{{ $guestCount }}">
                                    {{ $guestCount }} guest{{ $guestCount > 1 ? 's' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Sort Filter -->
                    <div class="w-full md:w-auto flex-1 min-w-[180px]">
                        <label for="priceSort" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-coins text-gray-600 mr-1"></i> Sort by Price
                        </label>
                        <select id="priceSort" wire:model.lazy="priceSort"
                            class="w-full p-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 cursor-pointer">
                            <option value="">Default</option>
                            <option value="low_high">Price: Low to High</option>
                            <option value="high_low">Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Results Info -->
                @if (!empty($searchQuery) || !empty($roomCategoryFilter) || !empty($idealGuestFilter) || !empty($priceSort))
                    <div class="mt-3 text-sm text-gray-600 bg-white border border-gray-100 rounded-lg px-4 py-3">
                        <p class="flex flex-wrap items-center gap-x-2 leading-relaxed">
                            @if (!empty($searchQuery))
                                <span><i class="fas fa-search mr-1 text-green-600"></i> "{{ $searchQuery }}"</span>
                            @endif

                            @if (!empty($searchQuery) && (!empty($roomCategoryFilter) || !empty($idealGuestFilter) || !empty($priceSort)))
                                <span class="text-gray-400">•</span>
                            @endif

                            @if (!empty($roomCategoryFilter))
                                <span><i class="fas fa-tag mr-1 text-green-600"></i>
                                    {{ $roomCategories->firstWhere('id', $roomCategoryFilter)->name ?? 'Unknown' }}
                                </span>
                            @endif

                            @if (!empty($roomCategoryFilter) && (!empty($idealGuestFilter) || !empty($priceSort)))
                                <span class="text-gray-400">•</span>
                            @endif

                            @if (!empty($idealGuestFilter))
                                <span><i class="fas fa-user-group mr-1 text-green-600"></i>
                                    {{ $idealGuestFilter }} guest{{ $idealGuestFilter > 1 ? 's' : '' }}
                                </span>
                            @endif

                            @if (!empty($idealGuestFilter) && !empty($priceSort))
                                <span class="text-gray-400">•</span>
                            @endif

                            {{-- Price Sort Display --}}
                            @if (!empty($priceSort))
                                <span>
                                    <i class="fas fa-coins mr-1 text-green-600"></i>
                                    @if ($priceSort === 'low_high')
                                        Price: Low → High
                                    @elseif($priceSort === 'high_low')
                                        Price: High → Low
                                    @endif
                                </span>
                            @endif

                            @if ($rooms->count() > 0)
                                <span class="text-gray-500">— {{ $rooms->count() }} results found</span>
                            @endif
                        </p>

                        <button wire:click="clearFilters"
                            class="mt-2 text-xs text-red-600 hover:text-red-700 underline font-medium transition">
                            Clear all filters
                        </button>
                    </div>
                @endif

            </div>
        </div>



        <!-- Main Content Grid -->
        @if ($rooms->count() === 0)
            <div class="w-full flex justify-center">
                <div class="step-one w-full px-4">
                    <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                        <div class="p-4 text-center">
                            <h4 class="text-2xl font-semibold mb-2">No Rooms Available</h4>
                            <p class="text-base font-normal text-gray-700  ">
                                Sorry, there are no rooms available for the selected dates.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- <div class="mb-4 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="w-full">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search by Name</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </div>
                            <input type="text" id="search" wire:model.live.debounce.300ms="search"
                                class="w-full pl-10 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5"
                                placeholder="e.g., Pool House">
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select id="sort_by" wire:model.live="sortBy"
                            class="mt-1 w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5">
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>

                    {{-- <div class="w-full">
                        <label for="property_category_id" class="block text-sm font-medium text-gray-700">Room
                            Category</label>
                        <select id="property_category_id" wire:model.live="roomCategoryFilter"
                            class="mt-1 w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5">
                            <option value="">All Categories</option>
                            @foreach ($roomCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div> --}}
            <div class="grid grid-cols-1">

                <!-- Room Category Filter -->
                {{-- <div class="flex items-center">
                <label for="property_category_id" class="w-32 text-sm font-medium text-gray-900">Room
                    Category:</label>
                <select id="property_category_id" name="property_category_id" wire:model.live="roomCategoryFilter"
                    class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="">All</option>
                    @foreach ($roomCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center space-x-4 mb-3">
                <div class="flex items-center">
                    <label for="property_category_id" class="text-sm font-medium text-gray-900 me-2">Room
                        Category:</label>
                    <select id="property_category_id" wire:model.live="roomCategoryFilter"
                        class="w-32 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                        <option value="">All Categories</option>
                        @foreach ($roomCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Loading indicator --}}
                {{-- <div wire:loading wire:target="roomCategoryFilter" class="text-sm text-gray-500">
                    <i class="fas fa-spinner fa-spin"></i> Updating rooms...
                </div>
            </div> --}}

                <div wire:loading
                    wire:target="check_in_date,check_out_date,category,search,sortBy,searchQuery,roomCategoryFilter,idealGuestFilter,priceSort"
                    class="space-y-4">

                    @for ($i = 0; $i < 3; $i++)
                        @include('livewire.guest.room-skeleton')
                    @endfor
                </div>

                <div wire:loading.remove wire:target="check_in_date,check_out_date,category,search,sortBy,"
                    class="space-y-4">
                    @foreach ($rooms as $room)
                        <div class=" space-y-6" wire:key="room-{{ $room->id }}">
                            <div
                                class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                                <div class="md:flex">

                                    <div class="w-full md:w-1/3">
                                        <!-- Image Container -->
                                        <div x-data="{ active: 0, images: @js($room->images ?? []), interval: null, hovering: false }" x-init="if (images.length > 1) {
                                            interval = setInterval(() => {
                                                active = (active + 1) % images.length;
                                            }, 4000);
                                        }"
                                            @mouseenter=" hovering = true; clearInterval(interval); "
                                            @mouseleave="
                                                hovering = false;
                                                if (images.length > 1) interval = setInterval(() => { active = (active + 1) % images.length; }, 4000);
                                            "
                                            wire:ignore
                                            class="relative w-full h-48 md:h-full overflow-hidden rounded-xl bg-gray-100 shadow-md">

                                            <!-- Slides -->
                                            <template x-for="(image, index) in images" :key="index">
                                                <img x-show="active === index" :src="'/storage/' + image"
                                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out"
                                                    x-transition:enter="opacity-0" x-transition:enter-end="opacity-100"
                                                    x-transition:leave="opacity-100"
                                                    x-transition:leave-end="opacity-0" />
                                            </template>

                                            <!-- Fallback if no image -->
                                            <template x-if="images.length === 0">
                                                <img src="{{ asset('images/rms-default.png') }}"
                                                    class="absolute inset-0 w-full h-full object-cover" />
                                            </template>

                                            <!-- SOLD OUT Badge -->
                                            @if ($room->is_booked)
                                                <div
                                                    class="absolute top-3 right-[-40px] bg-red-600 text-white text-xs font-bold py-1 px-12 transform rotate-45 shadow-lg">
                                                    SOLD OUT
                                                </div>
                                            @endif

                                            <!-- Left Arrow -->
                                            <button x-show="hovering && images.length > 1"
                                                @click="active = active === 0 ? images.length - 1 : active - 1"
                                                class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-700 rounded-full p-2 shadow-lg transition-all duration-300 focus:outline-none">
                                                <i class="fa-solid fa-chevron-left h-5 w-5"></i>
                                            </button>

                                            <!-- Right Arrow -->
                                            <button x-show="hovering && images.length > 1"
                                                @click="active = (active + 1) % images.length"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-700 rounded-full p-2 shadow-lg transition-all duration-300 focus:outline-none">
                                                <i class="fa-solid fa-chevron-right h-5 w-5"></i>
                                            </button>

                                            <!-- Dot Indicators -->
                                            <div x-show="hovering && images.length > 1"
                                                class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                                <template x-for="(image, index) in images" :key="index">
                                                    <button @click="active = index"
                                                        :class="active === index ? 'bg-green-200 scale-110' : 'bg-white/80'"
                                                        class="w-3 h-3 rounded-full border border-green-400 transition-all duration-200"></button>
                                                </template>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4 bg-white">

                                        <!-- Room Info -->
                                        <div class="md:w-2/3">

                                            <div class="flex space-x-1">
                                                <h4 class="text-2xl font-semibold mb-1">
                                                    {{ ucwords($room->name_number) }} </h4>
                                                <div class="mt-1">
                                                    @livewire('guest.reservation.property-reviews', ['propertyId' => $room->id], key('reviews-' . $room->id))
                                                </div>
                                            </div>

                                            <!-- Room Capacity and Charges -->
                                            <div>
                                                <p class="text-sm text-gray-700 mb-1 px-1"> {{ $room->description }}
                                                </p>

                                                {{-- Show Pool House relationship note --}}
                                                @php
                                                    $poolHouseBooked = $rooms->contains(function ($r) {
                                                        return $r->category->name === 'Pool House' &&
                                                            $r->name_number === 'Pool House' &&
                                                            $r->is_booked;
                                                    });

                                                    $anySubRoomBooked = $rooms->contains(function ($r) {
                                                        return $r->category->name === 'Pool House' &&
                                                            $r->name_number !== 'Pool House' &&
                                                            $r->is_booked;
                                                    });
                                                @endphp

                                                @if ($room->category->name === 'Pool House' && $room->name_number !== 'Pool House')
                                                    <div
                                                        class="bg-green-50 border-l-4 border-green-600 text-green-800 p-3 rounded-md mb-3 text-xs leading-relaxed">
                                                        <strong>Part of the Pool House.</strong><br>
                                                        This room is one of the exclusive subrooms within the Pool House
                                                        property.
                                                        When the entire Pool House is booked, this room becomes
                                                        unavailable
                                                        for
                                                        individual booking.
                                                        @if ($poolHouseBooked)
                                                            <div class="mt-1 italic text-gray-600">Currently
                                                                unavailable
                                                                — the
                                                                full Pool
                                                                House is reserved.</div>
                                                        @endif
                                                    </div>
                                                @elseif ($room->category->name === 'Pool House' && $room->name_number === 'Pool House')
                                                    @if ($anySubRoomBooked)
                                                        <div
                                                            class="bg-yellow-50 border-l-4 border-yellow-600 text-yellow-800 p-3 rounded-md mb-3 text-xs leading-relaxed">
                                                            <strong>Individual Pool House rooms are booked.</strong><br>
                                                            Some of the subrooms under the Pool House are currently
                                                            reserved,
                                                            which may
                                                            affect full-property availability.
                                                        </div>
                                                    @endif
                                                @endif


                                                <p class="text-base font-normal text-gray-700  ">
                                                    <i class="fas fa-user mr-2"></i> Ideal Guests:
                                                    {{ $room->ideal_guest }}
                                                </p>

                                                @if ($room->occupancy_type === 'whole_number')
                                                    <p class="text-base font-normal text-gray-700  ">
                                                        <i class="fas fa-users mr-2"></i>
                                                        Maximum Capacity: {{ $room->max_guests }} guests
                                                    </p>
                                                @elseif ($room->occupancy_type === 'combinations')
                                                    @php
                                                        $originalCombinations = collect($room->occupancy_rules)->where(
                                                            'type',
                                                            'original',
                                                        );

                                                        $formatted = $originalCombinations->map(function ($combo) {
                                                            $parts = [];

                                                            if (!empty($combo['adults'])) {
                                                                $parts[] =
                                                                    $combo['adults'] .
                                                                    ' adult' .
                                                                    ($combo['adults'] > 1 ? 's' : '');
                                                            }

                                                            if (!empty($combo['kids'])) {
                                                                $parts[] =
                                                                    $combo['kids'] .
                                                                    ' kid' .
                                                                    ($combo['kids'] > 1 ? 's' : '');
                                                            }

                                                            return implode(' and ', $parts);
                                                        });
                                                    @endphp

                                                    @if ($formatted->isNotEmpty())
                                                        <p class="text-base font-normal text-gray-700  ">
                                                            <i class="fas fa-users mr-2"></i>
                                                            Max occupancy: {{ $formatted->implode(' or ') }}
                                                        </p>
                                                    @endif
                                                @endif

                                                <p class="text-base font-normal text-gray-700  ">
                                                    <i class="fas fa-plus mr-2"></i> Extra Person Charge:
                                                    ₱{{ number_format($room->extra_person_charge, 2) }}
                                                </p>

                                                <p class="text-sm italic text-gray-500 mt-1"> Children 2yrs old and
                                                    below are free of charge</p>

                                                @if ($room->freebies)
                                                    <p class="text-base font-normal text-gray-700  ">
                                                        <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                                    </p>
                                                @endif
                                            </div>


                                            <!-- Room Rate Information -->
                                            <div class="mt-4 border-2 rounded-md p-2 mb-2 bg-gray-50">
                                                @php
                                                    // Get all rates information
                                                    $allRates = $this->getAllRoomRates($room);

                                                    // Get ALL applied rates for the entire stay period
                                                    $appliedRates = $this->getAppliedRatesForStay(
                                                        $room,
                                                        $this->check_in_date,
                                                        $this->check_out_date,
                                                    );

                                                    $baseRate = $room->amount;

                                                    // Calculate rate breakdown for selected dates
                                                    $rateSummary = $this->getRateSummary(
                                                        $room,
                                                        $this->check_in_date,
                                                        $this->check_out_date,
                                                    );
                                                    $nights = $rateSummary['nights'] ?? 0;
                                                    $totalRate = $rateSummary['total_amount'] ?? 0;

                                                    // Determine if we're showing multiple rates or single rate
$hasMultipleRates = count($appliedRates) > 1;
$hasSpecialRate =
    count($appliedRates) > 0 &&
    $appliedRates[0]['rate_type'] !== null;
$isBaseRateOnly =
    !$hasSpecialRate ||
    (count($appliedRates) === 1 &&
        $appliedRates[0]['rate_type'] === null);
                                                @endphp

                                                <!-- Main Rate Display -->
                                                <div>
                                                    @if ($this->check_in_date && $this->check_out_date && $nights > 0)
                                                        <!-- Show rates per night when dates are selected -->
                                                        @if ($isBaseRateOnly)
                                                            <!-- Only base rate applied -->
                                                            <p class="text-lg font-medium">
                                                                <span class="text-green-700 font-bold">
                                                                    Base Rate - ₱{{ number_format($baseRate, 2) }} per
                                                                    night
                                                                </span>
                                                            </p>
                                                        @else
                                                            <!-- Special rates applied -->
                                                            @foreach ($appliedRates as $appliedRate)
                                                                <p class="text-lg font-medium">
                                                                    @if ($appliedRate['rate_type'] === null)
                                                                        <!-- Base Rate -->
                                                                        <span class="text-gray-500 line-through">
                                                                            Base Rate -
                                                                            ₱{{ number_format($appliedRate['average_rate'], 2) }}
                                                                            per night
                                                                        </span>
                                                                    @else
                                                                        <!-- Special Rate -->
                                                                        <span class="text-green-700 font-bold">
                                                                            {{ $appliedRate['name'] }} -
                                                                            ₱{{ number_format($appliedRate['average_rate'], 2) }}<span
                                                                                class="text-sm text-gray-500">/per
                                                                                night</span>
                                                                        </span>
                                                                        <span
                                                                            class="inline-block py-1 px-2 rounded-full text-xs font-semibold ml-2 relative top-[-4px]
                                                                            @if ($appliedRate['rate_type'] === 'Weekend') bg-yellow-100 text-yellow-700
                                                                            @elseif ($appliedRate['rate_type'] === 'Weekdays') bg-green-100 text-green-700
                                                                            @elseif ($appliedRate['rate_type'] === 'Peak') bg-red-100 text-red-700
                                                                            @elseif ($appliedRate['rate_type'] === 'Holiday') bg-purple-100 text-purple-700
                                                                            @else bg-blue-100 text-blue-700 @endif ">
                                                                            {{ $appliedRate['rate_type'] }}
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            @endforeach
                                                        @endif

                                                        <!-- Total Stay Cost -->
                                                        <p class="text-sm text-gray-600">
                                                            Total for {{ $nights }}
                                                            night{{ $nights > 1 ? 's' : '' }}:
                                                            <span
                                                                class="font-semibold text-gray-700">₱{{ number_format($totalRate, 2) }}</span>
                                                        </p>
                                                    @else
                                                        <!-- Show base rate when no dates selected -->
                                                        <p class="text-lg font-medium">
                                                            <span class="text-green-700 font-bold">
                                                                Base Rate - ₱{{ number_format($baseRate, 2) }} per
                                                                night
                                                            </span>
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Detailed Breakdown (Collapsible) -->
                                                @if ($this->check_in_date && $this->check_out_date && count($appliedRates) > 0 && $hasMultipleRates)
                                                    <div class="mt-3 text-sm">
                                                        <button type="button"
                                                            class="text-green-700 hover:text-green-800 font-medium flex items-center"
                                                            onclick="toggleRateBreakdown({{ $room->id }})">
                                                            <i class="fas fa-circle-info mr-2"></i>
                                                            View Rate Breakdown
                                                            <i class="fas fa-chevron-down ml-1 text-xs"
                                                                id="breakdown-arrow-{{ $room->id }}"></i>
                                                        </button>

                                                        <div id="rate-breakdown-{{ $room->id }}"
                                                            class="mt-2 hidden">
                                                            <div
                                                                class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                                <p class="font-semibold text-gray-700 mb-2 text-sm">
                                                                    Rate
                                                                    Calculation:</p>

                                                                <div class="space-y-2">
                                                                    @foreach ($appliedRates as $appliedRate)
                                                                        <div
                                                                            class="flex justify-between items-center text-xs">
                                                                            <span class="text-gray-600">
                                                                                {{ $appliedRate['nights'] }}
                                                                                night{{ $appliedRate['nights'] > 1 ? 's' : '' }}
                                                                                -
                                                                                <span
                                                                                    class="font-medium">{{ $appliedRate['name'] }}</span>
                                                                                <span
                                                                                    class="text-gray-500">(₱{{ number_format($appliedRate['average_rate'], 2) }}/night)</span>
                                                                            </span>
                                                                            <span class="font-semibold text-gray-700">
                                                                                ₱{{ number_format($appliedRate['total_amount'], 2) }}
                                                                            </span>
                                                                        </div>
                                                                    @endforeach

                                                                    <div class="border-t border-gray-300 pt-2 mt-2">
                                                                        <div
                                                                            class="flex justify-between items-center font-semibold">
                                                                            <span class="text-gray-700">Total Room
                                                                                Rate:</span>
                                                                            <span
                                                                                class="text-green-700">₱{{ number_format($totalRate, 2) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (!$this->check_in_date || !$this->check_out_date)
                                                    <!-- No dates selected message -->
                                                    <div
                                                        class="mt-2 text-center p-2 bg-yellow-50 rounded border border-yellow-200">
                                                        <p class="text-yellow-700 text-xs font-medium">
                                                            <i class="fas fa-calendar-plus mr-1"></i>
                                                            Select dates to see special rates
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- More details button -->
                                            {{-- <a href="#"
                                                class="text-green-700 hover:underline transition mt-auto font-semibold"
                                                onclick="openRoomModal({{ $room->id }}); return false;">
                                                See more details
                                            </a> --}}

                                            <!-- Modal -->
                                            <div id="modal-room-{{ $room->id }}"
                                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                                <div
                                                    class="bg-white rounded-2xl shadow-lg max-w-4xl w-full p-6 relative max-h-[80vh] overflow-y-auto">
                                                    <button type="button"
                                                        onclick="closeRoomModal({{ $room->id }})"
                                                        class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-7 h-7 flex items-center justify-center text-2xl focus:outline-none">
                                                        <span
                                                            class="w-full h-full flex items-center justify-center pointer-events-none">&times;</span>
                                                    </button>

                                                    <!-- Carousel -->
                                                    @php
                                                        $images = $room->images ?? [];
                                                    @endphp
                                                    <div class="relative mb-4 px-12 mt-3">
                                                        <img id="modal-room-img-{{ $room->id }}"
                                                            src="{{ count($images) ? asset('storage/' . $images[0]) : asset('images/rms-default.png') }}"
                                                            class="w-full max-h-[350px] object-contain rounded-lg shadow bg-gray-100 mx-auto" />

                                                        @if (count($images) > 1)
                                                            <!-- Prev Button -->
                                                            <button
                                                                onclick="prevRoomImage({{ $room->id }}, {{ count($images) }})"
                                                                class="absolute left-2 top-1/2 transform -translate-y-1/2 rounded-full bg-gray-200 px-2 py-1 w-8 h-8">
                                                                <i class="fa-solid fa-chevron-left"></i>
                                                            </button>
                                                            <!-- Next Button -->
                                                            <button
                                                                onclick="nextRoomImage({{ $room->id }}, {{ count($images) }})"
                                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 rounded-full bg-gray-200 px-2 py-1 w-8 h-8">
                                                                <i class="fa-solid fa-chevron-right"></i>
                                                            </button>
                                                        @endif
                                                        <!-- Index Indicators -->
                                                        <div
                                                            class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-2 mt-2">
                                                            @foreach ($images as $idx => $img)
                                                                <div id="modal-room-dot-{{ $room->id }}-{{ $idx }}"
                                                                    onclick="setRoomImage({{ $room->id }}, {{ $idx }})"
                                                                    class="w-2.5 h-2.5 rounded-full cursor-pointer bg-gray-400 border border-gray-400">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="mt-4 text-sm text-gray-700 p-3">
                                                        <h2 class="text-2xl font-extrabold text-green-700">
                                                            Room: {{ ucwords($room->name_number) }}
                                                        </h2>

                                                        <p class="text-md font-semibold text-gray-700 mb-3">
                                                            {{ $room->description }}
                                                        </p>
                                                        {{-- {{ $room->name_number }} is a room ideal for
                                                        {{ $room->ideal_guest }}
                                                        guest{{ $room->ideal_guest > 1 ? 's' : '' }} with a
                                                        maximum capacity of {{ $room->max_adults }} Adults and
                                                        {{ $room->max_kids }} Kids.
                                                        The base rate is
                                                        ₱{{ number_format($room->amount, 2) }}{{
                                                        $room->extra_person_charge ? ',
                                                        with an extra charge of ₱' .
                                                        number_format($room->extra_person_charge,
                                                        2) . ' per additional guest per night' : '' }}. --}}

                                                        <ul class="list-disc list-inside space-y-1">
                                                            <li><strong>Bed:</strong>
                                                                @if ($room->beds->count())
                                                                    @foreach ($room->beds as $bed)
                                                                        {{ $bed->bed_quantity }}
                                                                        {{ ucfirst($bed->bed_type) }}
                                                                        Bed(s)
                                                                        @if (!$loop->last)
                                                                            ,
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    Not indicated
                                                                @endif
                                                            </li>


                                                            <li><strong>Ideal Guests:</strong>
                                                                {{ $room->ideal_guest }}
                                                            </li>

                                                            @if ($room->occupancy_type === 'whole_number')
                                                                <li><strong>Maximum Guests:</strong>
                                                                    {{ $room->max_guests }} guests
                                                                </li>
                                                            @elseif ($room->occupancy_type === 'combinations')
                                                                @php
                                                                    $originalCombinations = collect(
                                                                        $room->occupancy_rules,
                                                                    )->where('type', 'original');

                                                                    $formatted = $originalCombinations->map(function (
                                                                        $combo,
                                                                    ) {
                                                                        $parts = [];

                                                                        if (!empty($combo['adults'])) {
                                                                            $parts[] =
                                                                                $combo['adults'] .
                                                                                ' adult' .
                                                                                ($combo['adults'] > 1 ? 's' : '');
                                                                        }

                                                                        if (!empty($combo['kids'])) {
                                                                            $parts[] =
                                                                                $combo['kids'] .
                                                                                ' kid' .
                                                                                ($combo['kids'] > 1 ? 's' : '');
                                                                        }

                                                                        return implode(' and ', $parts);
                                                                    });
                                                                @endphp

                                                                @if ($formatted->isNotEmpty())
                                                                    <li><strong>Maximum Occupancy:</strong>
                                                                        {{ $formatted->implode(' or ') }}
                                                                    </li>
                                                                @endif
                                                            @endif

                                                            <li><strong>Extra Person Charge:</strong>
                                                                ₱{{ number_format($room->extra_person_charge, 2) }}
                                                            </li>

                                                            <li><strong>Base Rate Per Night:</strong>
                                                                ₱{{ number_format($room->amount, 2) }}
                                                            </li>
                                                            @if ($this->check_in_date && $this->check_out_date && $nights > 0)
                                                                @php
                                                                    $appliedRates = $this->getAppliedRatesForStay(
                                                                        $room,
                                                                        $this->check_in_date,
                                                                        $this->check_out_date,
                                                                    );
                                                                    $hasSpecialRate =
                                                                        count($appliedRates) > 0 &&
                                                                        $appliedRates[0]['rate_type'] !== null;
                                                                @endphp

                                                                @if ($hasSpecialRate)
                                                                    <!-- Show applied rates -->
                                                                    @foreach ($appliedRates as $appliedRate)
                                                                        <li>
                                                                            <strong>
                                                                                @if ($appliedRate['rate_type'] === null)
                                                                                    Base Rate:
                                                                                @else
                                                                                    {{ $appliedRate['name'] }}:
                                                                                @endif
                                                                            </strong>
                                                                            ₱{{ number_format($appliedRate['average_rate'], 2) }}
                                                                            per night
                                                                            ({{ $appliedRate['nights'] }}
                                                                            night{{ $appliedRate['nights'] > 1 ? 's' : '' }})
                                                                        </li>
                                                                    @endforeach
                                                                @endif

                                                                <li><strong>Total Rate for Stay:</strong>
                                                                    ₱{{ number_format($totalRate, 2) }} for
                                                                    {{ $nights }}
                                                                    night{{ $nights > 1 ? 's' : '' }}
                                                                </li>
                                                            @endif

                                                            {{-- <li><strong>Rate Per Night:</strong> ₱{{
                                                                number_format($room->dynamic_rate, 2) }}</li> --}}
                                                        </ul>
                                                        <hr class="my-2">

                                                        <!-- Room Amenities -->
                                                        <div class="mt-2 mb-4">
                                                            <h3 class="font-semibold text-gray-700 mb-2 text-lg">
                                                                Included
                                                                Amenities</h3>

                                                            @if ($room->features && count($room->features))
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-2">
                                                                    @foreach ($room->features as $feature)
                                                                        <div class="flex items-center space-x-2">
                                                                            <!-- Check Icon -->
                                                                            <i
                                                                                class="fa-solid fa-check text-green-700"></i>
                                                                            <span
                                                                                class="text-gray-700 text-md">{{ $feature->name }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 text-sm">No amenities
                                                                    listed.</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Room Booking Controls -->
                                        <div class="pt-2 flex flex-col justify-between">
                                            {{-- <x-button href="#" onclick="openRoomModal({{ $room->id }}); return false;"
                                                class=" !bg-white !border-green-600 !border-2 !text-green-700 hover:underline rounded-xl ease-in-out transition duration-150" >
                                                See more details
                                            </x-button> --}}

                                            @php
                                                $cartCollection = collect($cart);

                                                // Check if current room is in cart
                                                $roomInCart = $cartCollection->contains(function ($item) use ($room) {
                                                    return $item['type'] === 'room' && $item['room_id'] == $room->id;
                                                });

                                                // Check if the Pool House (whole property) is booked
                                                $poolHouseBooked = $rooms->contains(function ($r) {
                                                    return $r->category->name === 'Pool House' &&
                                                        $r->name_number === 'Pool House' &&
                                                        $r->is_booked;
                                                });

                                                // Check if any individual Pool House room (Psalm 23, 24, 27) is booked
                                                $anySubRoomBooked = $rooms->contains(function ($r) {
                                                    return $r->category->name === 'Pool House' &&
                                                        $r->name_number !== 'Pool House' &&
                                                        $r->is_booked;
                                                });

                                                // Check if Pool House is in cart
                                                $poolHouseInCart = $cartCollection->contains(function ($item) use (
                                                    $rooms,
                                                ) {
                                                    $roomItem = $rooms->firstWhere('id', $item['room_id']);
                                                    return $roomItem &&
                                                        $roomItem->category->name === 'Pool House' &&
                                                        $roomItem->name_number === 'Pool House';
                                                });

                                                // Check if any subroom is in the cart
                                                $anySubRoomInCart = $cartCollection->contains(function ($item) use (
                                                    $rooms,
                                                ) {
                                                    $roomItem = $rooms->firstWhere('id', $item['room_id']);
                                                    return $roomItem &&
                                                        $roomItem->category->name === 'Pool House' &&
                                                        $roomItem->name_number !== 'Pool House';
                                                });

                                                // Determine if dropdowns and button should be hidden
                                                $isUnavailable =
                                                    $room->is_booked ||
                                                    ($poolHouseBooked &&
                                                        $room->category->name === 'Pool House' &&
                                                        $room->name_number !== 'Pool House') ||
                                                    ($anySubRoomBooked &&
                                                        $room->category->name === 'Pool House' &&
                                                        $room->name_number === 'Pool House') ||
                                                    ($poolHouseInCart &&
                                                        $room->category->name === 'Pool House' &&
                                                        $room->name_number !== 'Pool House') ||
                                                    ($anySubRoomInCart &&
                                                        $room->category->name === 'Pool House' &&
                                                        $room->name_number === 'Pool House');
                                            @endphp

                                            <div class="pb-6 sm:pb-4">
                                                <a href="#"
                                                    class="text-green-700 hover:text-green-600 transition font-semibold text-md sm:text-sm sm:text-gray-700 sm:hover:text-green-600 sm:hover:underline"
                                                    onclick="openRoomModal({{ $room->id }}); return false;">
                                                    See more details
                                                    <i class="fa-solid fa-arrow-up-right-from-square pl-1"></i>
                                                </a>
                                            </div>
                                            <div>
                                                @unless ($isUnavailable)
                                                    <div class="flex gap-4 mb-3">
                                                        <!-- Adults -->
                                                        <div class="flex-1">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                            <select wire:model.live="adults.{{ $room->id }}"
                                                                wire:change="updateKidOptions({{ $room->id }})"
                                                                class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                                @foreach ($room->availableAdultOptions ?? [] as $adult)
                                                                    <option value="{{ $adult }}">
                                                                        {{ $adult }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Kids -->
                                                        <div class="flex-1">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Children</label>
                                                            <select wire:model.live="kids.{{ $room->id }}"
                                                                class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                                @foreach ($dynamicKidOptions[$room->id] ?? ($room->availableKidOptions ?? []) as $kid)
                                                                    <option value="{{ $kid }}">
                                                                        {{ $kid }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endunless
                                                @if ($roomInCart)
                                                    <x-warning-button
                                                        class="relative h-10 w-full justify-center !bg-yellow-400">
                                                        <span><i class="fa-solid fa-check-to-slot"></i> In Cart</span>
                                                    </x-warning-button>
                                                @elseif ($isUnavailable)
                                                    <!-- Nothing shown when unavailable -->
                                                    <div class="h-10"></div>
                                                @else
                                                    <x-button wire:click="addRoomToCart({{ $room->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addRoomToCart({{ $room->id }})"
                                                        class="relative h-10 w-full justify-center">
                                                        <div class="flex items-center justify-center relative w-full">
                                                            <!-- Spinner -->
                                                            <span wire:loading
                                                                wire:target="addRoomToCart({{ $room->id }})"
                                                                class="flex items-center justify-center">
                                                                <svg class="animate-spin h-5 w-5 text-white"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4" />
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                                                </svg>
                                                            </span>

                                                            <!-- Button Text -->
                                                            <span wire:loading.remove
                                                                wire:target="addRoomToCart({{ $room->id }})">
                                                                <i class="fa-solid fa-cart-plus"></i> Add Room
                                                            </span>
                                                        </div>
                                                    </x-button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
    </div>
    @endif
    <script>
        function openRoomModal(roomId) {
            document.getElementById('modal-room-' + roomId).classList.remove('hidden');
            window['roomImgIdx_' + roomId] = 0;
            updateRoomImage(roomId);
        }

        function closeRoomModal(roomId) {
            document.getElementById('modal-room-' + roomId).classList.add('hidden');
        }

        function prevRoomImage(roomId, imgCount) {
            window['roomImgIdx_' + roomId] = (window['roomImgIdx_' + roomId] - 1 + imgCount) % imgCount;
            updateRoomImage(roomId);
        }

        function nextRoomImage(roomId, imgCount) {
            window['roomImgIdx_' + roomId] = (window['roomImgIdx_' + roomId] + 1) % imgCount;
            updateRoomImage(roomId);
        }

        function setRoomImage(roomId, idx) {
            window['roomImgIdx_' + roomId] = idx;
            updateRoomImage(roomId);
        }

        function updateRoomImage(roomId) {
            var images = @json($rooms->mapWithKeys(fn($room) => [$room->id => collect($room->images)->map(fn($img) => asset('storage/' . $img))->values()])->toArray());
            var idx = window['roomImgIdx_' + roomId] || 0;
            var imgArr = images[roomId];
            if (imgArr && imgArr.length) {
                document.getElementById('modal-room-img-' + roomId).src = imgArr[idx];
                // Update dots
                for (let i = 0; i < imgArr.length; i++) {
                    let dot = document.getElementById('modal-room-dot-' + roomId + '-' + i);
                    if (dot) dot.classList.toggle('bg-white', i === idx);
                    if (dot) dot.classList.toggle('bg-gray-400', i !== idx);
                }
            }
        }

        function toggleRateBreakdown(roomId) {
            const breakdown = document.getElementById('rate-breakdown-' + roomId);
            const arrow = document.getElementById('breakdown-arrow-' + roomId);

            if (breakdown.classList.contains('hidden')) {
                breakdown.classList.remove('hidden');
                arrow.classList.remove('fa-chevron-down');
                arrow.classList.add('fa-chevron-up');
            } else {
                breakdown.classList.add('hidden');
                arrow.classList.remove('fa-chevron-up');
                arrow.classList.add('fa-chevron-down');
            }
        }
    </script>
</div>
