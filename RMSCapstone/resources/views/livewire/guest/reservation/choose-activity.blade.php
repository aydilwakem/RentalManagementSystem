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

                    <!-- Activity Name -->
                    <div class="p-5 pb-3">
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
                        <p class="text-gray-600 text-sm mb-4 text-justify">
                            @if (!empty($activity->description))
                                {{ $activity->description }}
                            @else
                                Try this activity only at Canopy Farm!
                            @endif
                        </p>


                        <!-- Controls -->
                        <div class="flex items-center justify-between sm:flex-row gap-4 mt-4">
                            <!-- Counter -->
                            <div class="flex flex-col">
                                <label for="quantity-{{ $activity->id }}"
                                    class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                                <div class="flex items-center">

                                    <button type="button" wire:click.prevent="decrementActivity('{{ $activity->id }}')"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline">
                                        -
                                    </button>

                                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                        {{ min($quantity[$activity->id] ?? 1, $total_pax) }}
                                    </span>

                                    @if (($quantity[$activity->id] ?? 1) < $total_pax)
                                        <button type="button" wire:click.prevent="incrementActivity('{{ $activity->id }}')"
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



                            <!-- Add to Cart Button -->
                            <div class="mt-auto">
                                <button wire:click="addActivityToCart({{ $activity->id }})"
                                    class="w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                    wire:loading.attr="disabled">
                                    <div class="flex items-center justify-center">
                                        <!-- Spinner -->
                                        <span wire:loading wire:target="addActivityToCart({{ $activity->id }})"
                                            class="mr-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                </path>
                                            </svg>
                                        </span>
                                        <!-- Button Text -->
                                        <span wire:loading.remove wire:target="addActivityToCart({{ $activity->id }})">
                                            Add to Cart
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- <div class="bg-white border rounded-xl overflow-hidden shadow-sm w-full">
                    <div class="flex flex-col md:flex-row h-full">

                        <!-- Image -->
                        <div class="w-full md:w-[300px] h-[200px] flex-shrink-0">
                            <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                                class=" object-cover rounded-l-xl">
                        </div>

                        <!-- Content -->
                        <div class="w-full h-full md:w-2/3 p-8 pl-10 flex flex-col justify-between gap-6 bg-gray-50">
                            <!-- Activity Info -->
                            <div>
                                <h4 class="text-2xl font-semibold mb-2">{{ $activity->name }}</h4>
                                <p class="text-base text-gray-700 dark:text-gray-400">
                                    Description: {{ $activity->description }}
                                </p>
                                <p class="text-base text-gray-700 dark:text-gray-400">
                                    <i class="fas fa-money mr-2"></i> Amount: {{ $activity->amount }}
                                </p>
                            </div>

                            <!-- Controls -->
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                                <!-- Quantity -->
                                <div class="w-full sm:w-auto flex flex-col">
                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700">Quantity:</label>
                                    <select id="quantity-{{ $activity->id }}" wire:model.live="quantity.{{ $activity->id }}"
                                        class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <!-- Button -->
                                <button wire:click="addActivityToCart({{ $activity->id }})"
                                    class="w-full sm:w-auto px-6 py-2 bg-green-700 hover:bg-green-800 text-white rounded transition">
                                    Add to Cart
                                </button>
                            </div>
                        </div>

                    </div>
                </div> --}}
            @endforeach
        </div>
    </div>
</div>