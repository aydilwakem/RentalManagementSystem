<div class="w-full flex justify-center">
    <div class="step-one w-full">
        {{-- <h1 class="text-3xl font-bold text-green-700">Our Activities</h1> --}}
        {{-- <p class="text-lg text-gray-700 text mb-3">
            These activities are <b>add-ons</b> to your bookings, enhancing your experience during your stay at
            Canopy
            Farm. These are not required but are highly recommended!
        </p> --}}
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @foreach ($activities as $activity)
                <!-- Activity Card -->
                <div
                    class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0 flex flex-col">

                    <!-- Activity Image -->
                    @php
                        $images = $activity->images ?? [];
                        $firstImage = count($images) ? $images[0] : null;
                    @endphp

                    <img class="w-full h-48 object-cover"
                        src="{{ asset($firstImage ? 'storage/' . $firstImage : 'images/rms-default.png') }}"
                        alt="{{ $activity->name }}">

                    <!-- Activity Name -->
                    <div class="p-5 pb-3 flex flex-col flex-grow">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-800"> {{ ucfirst($activity->name) }}</h2>
                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-green-600 font-bold text-lg">
                                    @if ($activity->amount == 0)
                                        <span class="text-green-600 font-semibold">FREE</span>
                                    @else
                                        ₱{{ number_format($activity->amount, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 text-justify">
                            {{-- Show more / less when description is long --}}
                            @if (empty($activity->description))
                                <span class="text-gray-600">Try this activity only at Canopy Farm!</span>
                            @elseif ($expandedActivity === $activity->id)
                                {{ $activity->description }}
                                <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                    class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                    less</a>
                            @else
                                {{ Str::limit($activity->description, 120, '...') }}
                                @if (Str::length($activity->description) > 100)
                                    <a href="#"
                                        wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                        class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                        more</a>
                                @endif
                            @endif
                        </p>

                        <!-- Available Times -->
                        @if ($activity->schedule_type !== 'no_schedule')
                            <div class="mt-4">
                                @if ($activity->schedule_type === 'system')
                                    <h3 class="text-md font-medium text-gray-900 dark:text-white">Choose Time Slot</h3>
                                    @if (is_array($activity->available_times) && count($activity->available_times))
                                        @foreach ($activity->available_times as $time)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="selected_time_{{ $activity->id }}"
                                                    {{-- This groups radios per
                                                    activity --}} wire:model="selectedTimes.{{ $activity->id }}"
                                                    value="{{ $time }}"
                                                    class="text-green-600 focus:ring-green-500 border-gray-300 text-sm">
                                                <span class="text-gray-700 dark:text-gray-200 text-sm">
                                                    {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                                </span>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 italic">No system-defined schedule for this
                                            activity.</p>
                                    @endif
                                @elseif ($activity->schedule_type === 'guest')
                                    <h3 class="text-md font-medium text-gray-900 dark:text-white">Input preferred time
                                    </h3>
                                    <input type="time" wire:model.lazy="selectedTimes.{{ $activity->id }}"
                                        class="border border-gray-300 rounded px-3 py-2 w-full dark:bg-gray-700 dark:text-white">
                                @endif

                                @error("selectedTimes.{$activity->id}")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif



                        <!-- Controls -->
                        <div class="flex items-center justify-between sm:flex-row mt-auto">
                            <!-- Counter -->
                            <div class="flex flex-col">
                                <label for="quantity-{{ $activity->id }}"
                                    class="mt-2 text-md font-medium text-gray-900 mb-1">
                                    Quantity:
                                </label>

                                <!-- Counter Buttons -->
                                <div class="flex items-center">
                                    <button type="button"
                                        wire:click.prevent="decrementItemQuantity('activity', {{ $activity->id }})"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline">
                                        -
                                    </button>

                                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                        {{ min($quantity[$activity->id] ?? 1, $total_pax) }}
                                    </span>

                                    @if (($quantity[$activity->id] ?? 1) < $total_pax)
                                        <button type="button"
                                            wire:click.prevent="incrementItemQuantity('activity', {{ $activity->id }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline">
                                            +
                                        </button>
                                    @endif
                                </div>


                            </div>



                            <!-- Add to Cart Button -->
                            <div>

                                @php
                                    $cartCollection = collect($cart); // Convert array to collection
                                    $activityInCart = $cartCollection->contains(function ($item) use ($activity) {
                                        return $item['type'] === 'activity' && $item['activity_id'] == $activity->id;
                                    });
                                @endphp

                                @if ($activityInCart)
                                @else
                                    <x-button wire:click="addActivityToCart('activity', {{ $activity->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="addActivityToCart('activity', {{ $activity->id }})"
                                        class="relative h-8 w-35 justify-center mt-8">

                                        <div class="flex items-center justify-center relative w-full">
                                            <!-- Spinner -->
                                            <span wire:loading class=" flex items-center justify-center"
                                                wire:target="addActivityToCart('activity', {{ $activity->id }})">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                                </svg>
                                            </span>

                                            <!-- Button Text -->
                                            <span wire:loading.remove
                                                wire:target="addActivityToCart('activity', {{ $activity->id }})">
                                                Add Activity
                                            </span>
                                        </div>
                                    </x-button>
                                @endif
                            </div>
                        </div>
                        <!-- Max Message Below -->
                        <div class="flex justify-center mt-1">
                            @if (($quantity[$activity->id] ?? 1) >= $total_pax)
                                <span class="text-gray-500 text-xs mt-1">
                                    Maximum quantity reached
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
