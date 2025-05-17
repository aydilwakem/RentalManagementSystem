<div class="w-full flex justify-center">
    <div class="step-one w-full px-4">
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
                    class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                    <img class="w-full h-48 object-cover"
                        src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                        alt="{{ $activity->name }}">


                    <div wire:key="activity-{{ $activity->id }}"
                        class="bg-white border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-6">


                        <div class="flex flex-col md:flex-row md:space-x-6">
                            <!-- Activity Info -->
                            <div class="md:w-2/3 space-y-2">
                                <h4 class="text-xl font-semibold text-gray-800">{{ $activity->name }}</h4>

                                <div class="flex flex-col">


                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700 mb-1">
                                        Quantity:
                                    </label>
                                    <div class="flex items-center">
                                        <button type="button" wire:click="decrementActivity({{ $activity->id }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline">
                                            -
                                        </button>

                                        <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                            {{ min($quantity[$activity->id] ?? 1, $total_pax) }}
                                        </span>

                                        @if (($quantity[$activity->id] ?? 1) < $total_pax)
                                            <button type="button" wire:click="incrementActivity({{ $activity->id }})"
                                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline">
                                                +
                                            </button>
                                        @else
                                            <span class="text-red-500 text-xs ml-2">
                                                Maximum quantity reached (based on your total guests)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button
                                    wire:click="addActivityToCart({{ $activity->id }})""
                                                                                                                                                                        class="
                                    px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 text-white rounded">
                                    Add to Cart
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>