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
                        <p class="mb-2 text-lg text-gray-700 dark:text-gray-300">
                            Choose Tour Date
                        </p>

                        <div class="relative w-full max-w-sm">
                            <input type="date" wire:model.live="tourDate"
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                class="peer border border-gray-300 dark:border-gray-600 rounded-lg
                           px-4 py-3 w-full text-base  /* Input text is now 'text-base' and py-3 for more height */
                           shadow-md hover:shadow-lg transition-shadow duration-300
                           dark:bg-gray-700 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                        </div>

                        @error('tourDate')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- @if ($tourDate)
        <p class="text-base text-gray-600 dark:text-gray-400 mt-3 text-center">
            Showing rates for
            <span class="font-bold">{{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }}</span>
            ({{ $this->getDayType($tourDate) }})
        </p>
    @endif --}}
            </div>
            <!-- Available Tours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($availableTours as $tour)
                    <div class="relative group border rounded-xl overflow-hidden shadow-sm hover:shadow-lg bg-white dark:bg-gray-800 transition cursor-pointer
                        {{ $selectedTour && $selectedTour->id == $tour->id ? 'border-green-500 ring-2 ring-green-300' : 'border-gray-200 dark:border-gray-700' }}"
                        wire:click="selectTour({{ $tour->id }})">

                        <!-- Selected Badge -->
                        @if ($selectedTour && $selectedTour->id == $tour->id)
                            <div
                                class="absolute top-3 right-3 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                <i class="fas fa-check mr-1"></i> Selected
                            </div>
                        @endif

                        <!-- Tour Image -->
                        <div class="relative">
                            @php $firstImage = $tour->images[0] ?? null; @endphp
                            <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('images/daytour-default.png') }}"
                                alt="{{ $tour->name }}"
                                class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            <h3 class="absolute bottom-3 left-3 text-lg font-semibold text-white drop-shadow">
                                {{ $tour->name }}
                            </h3>
                        </div>

                        <!-- Rates -->
                        <div class="p-4">
                            @if ($tour->activeRates && count($tour->activeRates) > 0)
                                @foreach ($tour->activeRates as $rate)
                                    @php
                                        $isAvailableForDate = $this->tourDate
                                            ? $rate->day_type === $this->getDayType($this->tourDate)
                                            : true;
                                        $isSelected =
                                            $selectedTour &&
                                            $selectedTour->id == $tour->id &&
                                            $selectedRate &&
                                            $selectedRate->id == $rate->id;
                                    @endphp
                                    @if ($isAvailableForDate)
                                        <div
                                            class="flex items-center justify-between p-2 rounded-lg border text-sm
                                            {{ $isSelected ? 'bg-green-50 border-green-400 text-green-700 font-semibold' : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300' }}">
                                            <span>{{ $rate->rate_name }} –
                                                ₱{{ number_format($rate->adult_rate, 2) }}</span>
                                            @if ($isSelected)
                                                <i class="fas fa-check-circle text-green-600"></i>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div
                                    class="text-center p-3 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-700 text-sm">
                                    No rates configured
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right side: Reservation Summary -->
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 h-full sticky top-24">

            <!-- Header -->
            <div
                class="bg-green-50 dark:bg-green-800 text-green-700 dark:text-green-300
                text-center text-lg font-semibold p-3 rounded-t-2xl shadow-sm border-b">
                Reservation Summary
            </div>

            <div class="p-6">
                @if ($selectedTour && $selectedRate)
                    <div class="relative rounded-md border bg-gray-50 p-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            Date: <strong>{{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }}</strong>
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $selectedTour->name }} <br>
                            <span class="text-gray-500">{{ $selectedRate->rate_name }}</span>
                        </p>
                        <p class="text-xl font-bold text-green-700 mt-4">
                            ₱{{ number_format($selectedRate->adult_rate, 2) }}
                        </p>

                        <button wire:click="selectTour(null)"
                            class="absolute top-0 right-0 p-3 text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle text-3xl mb-2"></i>
                        <p class="text-sm">No tour package selected yet.</p>
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>
