<!-- day-tour-step-one.blade.php -->
<div class="space-y-8">

    <!-- Main Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left side: Date Picker + Tours -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Date Picker -->
            <div>
                <div class="flex items-center justify-center">
                    <div class="flex flex-col items-center">
                        <p class="mb-2 text-lg text-gray-700 ">
                            Choose Tour Date
                        </p>

                        <div class="relative w-full max-w-sm">
                            <input type="date" wire:model.live="tourDate"
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                class="peer border border-gray-300 rounded-lg
                           px-4 py-3 w-full text-base
                           shadow-md hover:shadow-lg transition-shadow duration-300

                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                        </div>

                        @error('tourDate')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- @if ($tourDate)
                <p class="text-base text-gray-600 mt-3 text-center">
                    Showing rates for
                    <span class="font-bold">{{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }}</span>
                    ({{ $this->getDayType($tourDate) }})
                </p>
                @endif --}}
            </div>
            <!-- Available Tours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($availableTours as $tour)
                    @php
                        $hasRates = $tour->activeRates && count($tour->activeRates) > 0;
                        $hasRatesForSelectedDate = false;
                        $availableRatesForDate = [];

                        if ($hasRates) {
                            foreach ($tour->activeRates as $rate) {
                                // If no date is selected, assume available for now (will show all rates)
                                $isAvailableForDate = $this->tourDate
                                    ? $rate->day_type === $this->getDayType($this->tourDate)
                                    : true;
                                if ($isAvailableForDate) {
                                    $hasRatesForSelectedDate = true;
                                    $availableRatesForDate[] = $rate;
                                }
                            }
                        }

                        // If no date was selected, the final availability just depends on if *any* rates exist
                        if (!$this->tourDate) {
                            $hasRatesForSelectedDate = $hasRates;
                        }

                        // Define status variables for cleaner class/badge logic
                        $isSelected = $selectedTour && $selectedTour->id == $tour->id;
                        $isAvailable = $hasRatesForSelectedDate;
                    @endphp

                    <div @class([
                        'relative group border rounded-xl overflow-hidden shadow-sm hover:shadow-lg bg-whitetransition cursor-pointer',
                        'border-green-500 ring-2 ring-green-300' => $isSelected && $isAvailable,
                        'border-red-500 ring-2 ring-red-300' => $isSelected && !$isAvailable,
                        'border-gray-200 ' => !$isSelected,
                    ]) wire:click="selectTour({{ $tour->id }})">

                        @if ($isSelected)
                            @if ($isAvailable)
                                <div
                                    class="absolute top-3 right-3 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow z-10">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </div>
                            @else
                                <div
                                    class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow z-10">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Not Available
                                </div>
                            @endif
                        @endif

                        <div class="relative">
                            @php
                                if (is_string($tour->images)) {
                                    $decoded = json_decode($tour->images, true);
                                    $images = is_array($decoded) ? $decoded : [];
                                } elseif (is_array($tour->images)) {
                                    $images = $tour->images;
                                } else {
                                    $images = [];
                                }

                                $firstImage = $images[0] ?? null;
                            @endphp

                            <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('images/daytour-default.png') }}"
                                alt="{{ $tour->name }}"
                                class="w-full h-52 object-cover transition-transform duration-500">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            <h3 class="absolute bottom-3 left-3 text-lg font-semibold text-white drop-shadow">
                                {{ $tour->name }}
                            </h3>
                        </div>


                        <div class="p-4 space-y-4 bg-white h-full">
                            @if ($tour->description)
                                <div class="text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit($tour->description, 120) }}
                                </div>
                            @endif

                            <div class="space-y-3">
                                @if ($tour->inclusions)
                                    <div class="space-y-1">
                                        <div class="font-semibold text-green-600 flex items-center text-sm">
                                            <i class="fas fa-check-circle mr-2 text-xs"></i>
                                            <span>What's Included</span>
                                        </div>
                                        <div class="text-xs text-gray-600 pl-5">
                                            @php
                                                // Split by new lines or commas
                                                $inclusions = preg_split("/\r\n|\n|\r|,/", $tour->inclusions);
                                                $inclusions = array_filter(array_map('trim', $inclusions)); // clean up blanks
                                                $displayInclusions = array_slice($inclusions, 0);
                                            @endphp

                                            <span>{{ implode(', ', $displayInclusions) }}</span>

                                            {{-- @if (count($inclusions) > 3)
                                                <span class="text-blue-500 text-xs font-medium ml-1">
                                                    +{{ count($inclusions) - 3 }} more
                                                </span>
                                            @endif --}}
                                        </div>
                                    </div>
                                @endif

                            </div>

                            @if (!$hasRates)
                                <div
                                    class="text-center p-3 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-700 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No rates configured
                                </div>
                            @elseif($hasRatesForSelectedDate)
                                <div class="space-y-2">
                                    <div class="font-semibold text-sm text-gray-700 ">Available Rate:</div>
                                    @foreach ($availableRatesForDate as $rate)
                                        @php
                                            $isSelectedRate =
                                                $isSelected && $selectedRate && $selectedRate->id == $rate->id;
                                        @endphp
                                        <div
                                            class="flex items-center justify-between p-2 rounded-lg border text-sm
                                            {{ $isSelectedRate ? 'bg-green-50 border-green-400 text-green-700 font-semibold' : 'bg-gray-50  border-gray-200  text-gray-700 ' }}">
                                            <div class="flex items-center space-x-2">
                                                <span>{{ $rate->rate_name }} –</span>
                                                <span
                                                    class="font-semibold">₱{{ number_format($rate->adult_rate, 2) }}</span>
                                                <span
                                                    class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $tour->package_type === 'with_room' ? 'bg-green-100 text-green-800 border ' : 'bg-gray-100 text-gray-800 border ' }}">
                                                    {{ $tour->package_type === 'with_room' ? 'With Room' : 'Without Room' }}
                                                </span>
                                            </div>

                                            @if ($isSelectedRate)
                                                <i class="fas fa-check-circle text-green-600"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="text-center p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-600 text-sm">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>
                                    No available rate for the selected date
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right side: Reservation Summary -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200  h-full sticky top-24">

            <!-- Header -->
            <div
                class="bg-green-50  text-green-700
        text-center text-lg font-semibold p-3 rounded-t-2xl shadow-sm border-b">
                Reservation Summary
            </div>

            <div class="p-6">
                @if ($selectedTour && $selectedRate)
                    <!-- Selected Tour -->
                    <div class="relative rounded-md border bg-gray-50  p-4 mb-4">
                        <p class="text-sm text-gray-700  mb-2">
                            Date: <strong>{{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }}</strong>
                        </p>
                        <p class="text-md font-semibold text-gray-700 ">
                            {{ $selectedTour->name }} <br>
                        </p>
                        <p class="text-xl font-bold text-green-700">
                            ₱{{ number_format($selectedRate->adult_rate, 2) }} <span
                                class="text-gray-500 text-sm font-light">/ {{ $selectedRate->rate_name }}</span>
                        </p>

                        <button wire:click="selectTour(null)"
                            class="absolute top-3 right-3 w-7 h-7 bg-gray-200 text-red-500 hover:text-red-700 rounded-full flex items-center justify-center text-sm shadow-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="space-y-2 border-t pt-4">
                        <!-- Subtotal -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 ">Subtotal:</span>
                            <span class="text-sm font-medium text-gray-700 ">
                                ₱{{ number_format($this->subtotal, 2) }}
                            </span>
                        </div>

                        <!-- Convenience Fee -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 ">
                                Convenience Fee:
                                @if ($this->convenience_fee > 0)
                                    <span
                                        class="text-xs text-gray-500">({{ number_format(($this->convenience_fee / $this->subtotal) * 100, 2) }}%)</span>
                                @endif
                            </span>
                            <span class="text-sm font-medium text-gray-700 ">
                                ₱{{ number_format($this->convenience_fee, 2) }}
                            </span>
                        </div>

                        <!-- Total Amount -->
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 ">
                            <span class="text-base font-semibold text-gray-800 ">Total Amount:</span>
                            <span class="text-lg font-bold text-green-700 ">
                                ₱{{ number_format($this->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-3xl mb-2"></i>
                        <p class="text-sm">No tour package selected yet.</p>
                        <p class="text-xs mt-1 text-center">Select a tour package to see pricing details</p>
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>
