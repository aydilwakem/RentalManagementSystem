<div class="w-full flex justify-center">
    <div class="step-one w-full px-4">
        <!-- Main Content Grid -->
        @if ($rooms->count() === 0)
            <div class="w-full flex justify-center">
                <div class="step-one w-full px-4">
                    <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                        <div class="p-4 text-center">
                            <h4 class="text-2xl font-semibold mb-2">No Rooms Available</h4>
                            <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                Sorry, there are no rooms available for the selected dates.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach ($rooms as $room)
                    <div class=" space-y-6" wire:key="room-{{ $room->id }}">
                        <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                            <div class="md:flex">

                                <!-- Image -->
                                <div class="md:w-1/3">
                                    <img src="{{ asset($room->image ? 'storage/' . $room->image : 'images/rms-default.png') }}"
                                        class="w-full h-64 object-cover rounded-lg shadow-md" />
                                </div>


                                <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4 bg-gray-50">

                                    <!-- Room Info -->
                                    <div class="md:w-2/3">

                                        <h4 class="text-2xl font-semibold mb-2">{{ $room->name_number }}</h4>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-user mr-2"></i> Ideal Guests: {{ $room->ideal_guest }}
                                        </p>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-users mr-2"></i> Maximum Capacity: {{ $room->max_adults }}
                                            Adults,
                                            {{ $room->max_kids }} Kids
                                        </p>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-plus mr-2"></i> Extra Person Charge:
                                            {{ $room->extra_person_charge }}
                                        </p>

                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                        </p>
                                        <p class="text-sm italic text-gray-500 mt-1"> {{ $room->description }} </p>
                                        <p class="mt-4 text-lg font-medium">
                                            Rate Per Night: <span class="text-green-700 font-bold">{{ $room->amount }}</span>
                                        </p>
                                        <a href="#" class="text-sm mt-2 hover:underline inline-block text-gray-600">See more
                                            details</a>
                                    </div>

                                    <!-- Room Booking Controls -->
                                    <div class="mt-auto pt-2 flex flex-col justify-between">
                                        <div class="flex gap-4">

                                            <!-- Adults -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                <select wire:model.live="adults.{{ $room->id }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                    @for ($i = 0; $i <= $room->max_adults; $i++)
                                                        <option value="{{ $i }}">{{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <!-- Kids -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Children</label>
                                                <select wire:model.live="kids.{{ $room->id }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                    @for ($i = 0; $i <= $room->max_kids; $i++)
                                                        <option value="{{ $i }}">{{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                        </div>
                                        <div class="mt-4">
                                            <!-- Add ROom button -->
                                            <button wire:click="addRoomToCart({{ $room->id }})"
                                                class="w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                                wire:loading.attr="disabled">

                                                <div class="flex items-center justify-center">
                                                    <!-- Spinner -->
                                                    <span wire:loading wire:target="addRoomToCart({{ $room->id }})"
                                                        class="mr-2">
                                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                            </path>
                                                        </svg>
                                                    </span>

                                                    <!-- Button Text -->
                                                    <span wire:loading.remove wire:target="addRoomToCart({{ $room->id }})">
                                                        Add Room
                                                    </span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>