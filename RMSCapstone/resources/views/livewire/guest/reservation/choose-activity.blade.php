<div class="w-full flex justify-center">
    <div class="step-one w-full">
        <h1 class="text-3xl font-bold text-green-700">Our Activities</h1>
        <p class="text-lg text-gray-700 text mb-3">
            These activities are <b>add-ons</b> to your bookings, enhancing your experience during your stay at
            Canopy
            Farm. These are not required but are highly recommended!
        </p>
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @foreach ($activities as $activity)
                <!-- Activity Card -->
                <div
                    class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0 flex flex-col">
                    <img class="w-full h-48 object-cover"
                        src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                        alt="{{ $activity->name }}">

                    <!-- Activity Name -->
                    <div class="p-5 pb-3 flex flex-col flex-grow">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-800"> {{ $activity->name }}</h2>
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
                        <p class="text-gray-600 text-sm mb-4 text-justify flex-grow">
                            @if (!empty($activity->description))
                                {{ Str::limit($activity->description, 300) }}
                            @else
                                Try this activity only at Canopy Farm!
                            @endif
                        </p>


                        <!-- Controls -->
                        <div class="flex items-center justify-between sm:flex-row mt-auto">
                            <!-- Counter -->
                            <div class="flex flex-col">
                                <label for="quantity-{{ $activity->id }}" class="text-sm font-medium text-gray-700 mb-1">
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

                                <!-- Max Message Below -->
                                @if (($quantity[$activity->id] ?? 1) >= $total_pax)
                                    <span class="text-gray-500 text-xs mt-1">
                                        Maximum quantity reached
                                    </span>
                                @endif
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
                                        class="relative h-10 w-full justify-center mt-5">

                                        <div class="flex items-center justify-center relative w-full">
                                            <!-- Spinner -->
                                            <span wire:loading class=" flex items-center justify-center"
                                                wire:target="addActivityToCart('activity', {{ $activity->id }})">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>