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
        <!--TODO: Add lazy loading-->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($rooms as $room)
                    <div class=" space-y-6" wire:key="room-{{ $room->id }}">
                        <div
                            class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                            <div class="md:flex">

                                <div class="md:w-1/3">

                                    <!-- Image Container -->
                                    <div class="relative w-full h-64 overflow-hidden rounded-xl shadow-md">
                                        @php
                                            $firstImage = $room->images[0] ?? null;
                                        @endphp

                                        @if ($firstImage)
                                            <img src="{{ asset('storage/' . $firstImage) }}"
                                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300" />
                                        @else
                                            <!-- Default Placeholder -->
                                            <img src="{{ asset('images/rms-default.png') }}"
                                                class="absolute inset-0 w-full h-full object-cover" />
                                        @endif
                                    </div>


                                </div>


                                <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4 bg-white">

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
                                            ₱{{ number_format($room->extra_person_charge, 2) }}
                                        </p>

                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                        </p>
                                        <p class="text-sm italic text-gray-500 mt-1"> {{ $room->description }} </p>
                                        <p class="mt-4 text-lg font-medium">
                                            Rate Per Night: <span
                                                class="text-green-700 font-bold">₱{{ number_format($room->amount, 2) }}</span>
                                        </p>
                                        {{-- <a href="#" class="text-sm mt-2 hover:underline inline-block text-gray-600">See
                                            more
                                            details</a> --}}
                                    </div>

                                    <!-- Room Booking Controls -->
                                    <div class="mt-auto pt-2 flex flex-col justify-between">
                                        <div class="flex gap-4">

                                            <!-- Adults -->
                                            <div class="flex-1">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                <select wire:model.live="adults.{{ $room->id }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                    @for ($i = 1; $i <= $room->max_adults; $i++)
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



                                            @php
                                                $cartCollection = collect($cart); // Convert array to collection
                                                $roomInCart = $cartCollection->contains(function ($item) use ($room) {
                                                    return $item['type'] === 'room' && $item['room_id'] == $room->id;
                                                });
                                            @endphp

                                            <!-- Room info here -->

                                            @if ($roomInCart)
                                            @else
                                                <x-button wire:click="addRoomToCart({{ $room->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="addRoomToCart({{ $room->id }})"
                                                    class="relative h-10 w-full justify-center">

                                                    <div class="flex items-center justify-center relative w-full">
                                                        <!-- Spinner -->
                                                        <span wire:loading
                                                            class=" flex items-center justify-center"
                                                            wire:target="addRoomToCart({{ $room->id }})">
                                                            <svg class="animate-spin h-5 w-5 text-white"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12"
                                                                    r="10" stroke="currentColor" stroke-width="4" />
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                                            </svg>
                                                        </span>

                                                        <!-- Button Text -->
                                                        <span wire:loading.remove
                                                            wire:target="addRoomToCart({{ $room->id }})">
                                                            Add Room
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
        @endif
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('roomCarousel', (images) => ({
                active: 0,
                images: images,
                hover: false,
                init() {
                    this.active = 0;
                }
            }));
        });
    </script>
</div>
