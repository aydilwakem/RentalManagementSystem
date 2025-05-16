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
                <div wire:key="activity-{{ $activity->id }}"
                    class="bg-white border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:space-x-6">
                        <!-- Activity Info -->
                        <div class="md:w-2/3 space-y-2">
                            <h4 class="text-xl font-semibold text-gray-800">{{ $activity->name }}</h4>

                            <div class="flex flex-col">
                                <label for="quantity-{{ $activity->id }}" class="text-sm font-medium text-gray-700 mb-1">
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

                        <div class="md:w-1/3 flex flex-col justify-between mt-4 md:mt-0 space-y-4">
                            @php
                                $isSelected = collect($selectedActivities)->contains('activity_id', $activity->id);
                                $action = $isSelected ? 'RemoveActivity' : 'SelectedActivities';
                            @endphp

                            <button wire:click="{{ $action }}({{ $activity->id }})"
                                class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150"
                                wire:loading.attr="disabled">

                                <div class="flex items-center justify-center">
                                    <span wire:loading wire:target="{{ $action }}({{ $activity->id }})" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                    <span wire:loading.remove wire:target="{{ $action }}({{ $activity->id }})">
                                        {{ $isSelected ? 'Remove Activity' : 'Add Activity' }}
                                    </span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>