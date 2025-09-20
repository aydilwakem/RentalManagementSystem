<div class="w-full flex justify-center">
    <div class="step-one w-full">
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

            <div wire:loading wire:target="check_in_date,check_out_date,category" class="space-y-4">
                @for ($i = 0; $i < 3; $i++) @include('livewire.guest.room-skeleton') @endfor </div>

                    <div wire:loading.remove wire:target="check_in_date,check_out_date,category" class="space-y-4">
                        @foreach ($rooms as $room)
                        <div class=" space-y-6" wire:key="room-{{ $room->id }}">
                            <div
                                class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                                <div class="    md:flex">

                                    <div class="w-full md:w-1/3">
                                        <!-- Image Container -->
                                        <div class="relative w-full h-48 md:h-full overflow-hidden rounded-xl">
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

                                            <h4 class="text-2xl font-semibold mb-2">{{ ucwords($room->name_number) }}
                                            </h4>

                                                <div class="mb-3">
                                                    @livewire('guest.reservation.property-reviews', ['propertyId' => $room->id], key('reviews-'.$room->id))
                                                </div>

                                            <p class="text-base font-normal text-gray-700  ">
                                                <i class="fas fa-user mr-2"></i> Ideal Guests: {{ $room->ideal_guest }}
                                            </p>

                                            @if ($room->occupancy_type === 'whole_number')
                                            <p class="text-base font-normal text-gray-700  ">
                                                <i class="fas fa-users mr-2"></i>
                                                Maximum Capacity: {{ $room->max_guests }} guests
                                            </p>
                                            @elseif ($room->occupancy_type === 'combinations')
                                            @php
                                            $originalCombinations = collect($room->occupancy_rules)
                                            ->where('type', 'original');

                                            $formatted = $originalCombinations->map(function ($combo) {
                                            $parts = [];

                                            if (!empty($combo['adults'])) {
                                            $parts[] = $combo['adults'] . ' adult' . ($combo['adults'] > 1 ? 's' : '');
                                            }

                                            if (!empty($combo['kids'])) {
                                            $parts[] = $combo['kids'] . ' kid' . ($combo['kids'] > 1 ? 's' : '');
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

                                                @if ($room->freebies)
                                                    <p class="text-base font-normal text-gray-700  ">
                                                        <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                                    </p>
                                                @endif


                                                <p class="text-sm italic text-gray-500 mt-1"> {{ $room->description }}

                                                </p>


                                                <p class="text-sm italic text-gray-500 mt-1"> Children 2 years old and below are free of charge.
                                                </p>



                                                <p class="mt-4 text-lg font-medium">
                                                    Rate Per Night:
                                                    @if ($room->rate_name || $room->rate_type)
                                                        @if ($room->amount > $room->dynamic_rate)
                                                            <span class="text-red-500 font-semibold line-through mr-2 text-sm">
                                                                ₱{{ number_format($room->amount, 2) }}
                                                            </span>
                                                            <span class="text-green-700 font-bold">
                                                                ₱{{ number_format($room->dynamic_rate, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="text-green-700 font-bold">
                                                                ₱{{ number_format($room->dynamic_rate, 2) }}
                                                            </span>
                                                        @endif
                                                        <br>
                                                        <span class="inline-block py-1 px-2 rounded-full text-xs font-semibold mb-3
                                                            @if ($room->rate_type === 'Weekend') bg-yellow-100 text-yellow-700
                                                            @elseif ($room->rate_type === 'Weekdays') bg-green-100 text-green-700
                                                            @elseif ($room->rate_type === 'Peak') bg-red-100 text-red-700
                                                            @elseif ($room->rate_type === 'Holiday') bg-purple-100 text-purple-700
                                                            @else bg-gray-100 text-gray-600
                                                            @endif ">
                                                            {{ $room->rate_name }}
                                                            @if ($room->rate_type)
                                                                - {{ $room->rate_type }} Rate
                                                            @endif
                                                        </span>
                                                    @endif
                                                </p>

                                                <!-- More details Bbtton -->
                                                <a href="#"
                                                    class="text-gray-600 hover:underline hover:text-green-800 transition mt-auto"
                                                    onclick="openRoomModal({{ $room->id }}); return false;">
                                                    See more details
                                                </a>

                                                <!-- Modal -->
                                                <div id="modal-room-{{ $room->id }}"
                                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                                    <div
                                                        class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-4 relative max-h-[80vh] overflow-y-auto">
                                                        <button type="button" onclick="closeRoomModal({{ $room->id }})"
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
                                                                class="w-full h-full object-cover rounded-lg shadow" />
                                                            @if (count($images) > 1)
                                                                <!-- Prev Button -->
                                                                <button onclick="prevRoomImage({{ $room->id }}, {{ count($images) }})"
                                                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 rounded-full bg-gray-200 px-2 py-1">
                                                                    <i class="fa-solid fa-chevron-left"></i>
                                                                </button>
                                                                <!-- Next Button -->
                                                                <button onclick="nextRoomImage({{ $room->id }}, {{ count($images) }})"
                                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 rounded-full bg-gray-200 px-2 py-1">
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
                                                            <h2 class="text-xl font-bold mb-1 text-green-700">
                                                                Room: {{ $room->name_number }}
                                                            </h2>
                                                            <ul class="list-disc list-inside space-y-1">
                                                                <li><strong>Ideal Guests:</strong>
                                                                    {{ $room->ideal_guest }}
                                                                </li>

                                                                   @if ($room->occupancy_type === 'whole_number')
                                                                    <p class="text-base font-normal text-gray-700  ">
                                                                        <i class="fas fa-users mr-2"></i>
                                                                        Maximum Capacity: {{ $room->max_guests }} guests
                                                                    </p>
                                                                    @elseif ($room->occupancy_type === 'combinations')
                                                                    @php
                                                                    $originalCombinations = collect($room->occupancy_rules)
                                                                    ->where('type', 'original');

                                                                    $formatted = $originalCombinations->map(function ($combo) {
                                                                    $parts = [];

                                                                    if (!empty($combo['adults'])) {
                                                                    $parts[] = $combo['adults'] . ' adult' . ($combo['adults'] > 1 ? 's' : '');
                                                                    }

                                                                    if (!empty($combo['kids'])) {
                                                                    $parts[] = $combo['kids'] . ' kid' . ($combo['kids'] > 1 ? 's' : '');
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

                                                                <li><strong>Extra Person Charge:</strong>
                                                                    ₱{{ number_format($room->extra_person_charge, 2) }}
                                                                </li>

                                                                <li><strong>Description:</strong>
                                                                    {{ $room->name_number }} is a room ideal for {{ $room->ideal_guest }} guest{{ $room->ideal_guest > 1 ? 's' : '' }} with a maximum capacity of {{ $room->max_adults }} Adults and {{ $room->max_kids }} Kids.
                                                                    {{-- It features {{ $room->bed_quantity }} {{ strtolower($room->bed_type) }} bed{{ $room->bed_quantity > 1 ? 's' : '' }}. --}}
                                                                    The base rate is ₱{{ number_format($room->amount, 2) }}{{ $room->extra_person_charge ? ', with an extra charge of ₱' . number_format($room->extra_person_charge, 2) . ' per additional guest per night' : '' }}.

                                                                </li>

                                                                <li><strong>Rate Per Night:</strong>
                                                                    ₱{{ number_format($room->amount, 2) }}
                                                                </li>
                                                                {{-- <li><strong>Rate Per Night:</strong> ₱{{
                                                                    number_format($room->dynamic_rate, 2) }}</li> --}}
                                                            </ul>
                                                            <!-- Room Amenities -->
                                                            <div class="mt-2 mb-4">
                                                                <strong>Included Amenities</strong>
                                                                @if ($room->features && count($room->features))
                                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                                        @foreach ($room->features as $feature)
                                                                            <span
                                                                                class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold text-gray-700 mr-2">
                                                                                {{ $feature->name }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-400 text-sm">No amenities
                                                                        listed.</span>
                                                                @endif
                                                            </div>
                                                            <hr>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <!-- Room Booking Controls -->
                                        <div class="mt-auto pt-2 flex flex-col justify-between">
                                            @if ($room->is_booked)
                                            @else
                                            <div class="flex gap-4">
                                                <!-- Adults -->
                                                <div class="flex-1">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                    <select wire:model.live="adults.{{ $room->id }}"
                                                        wire:change="updateKidOptions({{ $room->id }})"
                                                        class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                        @foreach ($room->availableAdultOptions ?? [] as $adult)
                                                        <option value="{{ $adult }}">{{ $adult }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Kids -->
                                                <div class="flex-1">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700">Children</label>
                                                    <select wire:model.live="kids.{{ $room->id }}"
                                                        class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                        @foreach ($dynamicKidOptions[$room->id] ??
                                                        $room->availableKidOptions ?? [] as $kid)
                                                        <option value="{{ $kid }}">{{ $kid }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="mt-4">

                                                @php
                                                $cartCollection = collect($cart); // Convert array to collection
                                                $roomInCart = $cartCollection->contains(function ($item) use ($room, ) {
                                                return $item['type'] === 'room' &&
                                                $item['room_id'] == $room->id;
                                                });
                                                @endphp

                                                <!-- Room info here -->

                                                @if ($roomInCart)
                                                {{-- <span
                                                    class="block w-full text-center py-2 text-yellow-800 bg-yellow-100 border border-yellow-300 font-semibold rounded text-xs uppercase">
                                                    <i class="fa-solid fa-check-to-slot"></i> In Cart
                                                </span> --}}
                                                <x-warning-button
                                                    class="relative h-10 w-full justify-center !bg-yellow-400 ">
                                                    <span>
                                                        <i class="fa-solid fa-check-to-slot"></i> In Cart
                                                    </span>
                                                </x-warning-button>
                                                @elseif ($room->is_booked)
                                                <x-danger-button>
                                                    <i class="fa-solid fa-circle-xmark me-1"></i> Sold Out
                                                </x-danger-button>

                                                @else
                                                <x-button wire:click="addRoomToCart({{ $room->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="addRoomToCart({{ $room->id }})"
                                                    class="relative h-10 w-full justify-center">

                                                    <div class="flex items-center justify-center relative w-full">
                                                        <!-- Spinner -->
                                                        <span wire:loading class=" flex items-center justify-center"
                                                            wire:target="addRoomToCart({{ $room->id }})">
                                                            <svg class="animate-spin h-5 w-5 text-white"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                    stroke="currentColor" stroke-width="4" />
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
        </script>
    </div>
