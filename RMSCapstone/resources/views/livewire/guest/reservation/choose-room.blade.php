<div class="w-full flex justify-center">


    <div class="step-one w-full max-w-7xl px-4">



        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($rooms as $room)
                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                            <div class="md:flex">

                                <!-- Image -->
                                <div class="mb-4">
                                    <img src="{{ asset($room->image ? 'storage/' . $room->image : 'images/rms-default.png') }}"
                                        class="w-full h-64 object-cover rounded-lg shadow-md">
                                </div>

                                <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4">

                                    <!-- Room Info -->
                                    <div class="md:w-2/3">

                                        <h4 class="text-2xl font-semibold mb-2">{{ $room->name_number }}</h4>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-user mr-2"></i> Ideal Guests:
                                            {{ $room->ideal_guest
                                                                                                                                                                                                                                                                                                                                                                                        }}
                                        </p>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-users mr-2"></i> Maximum Capacity: {{
                $room->max_adults }} Adults, {{ $room->max_kids }} Kids
                                        </p>
                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-plus mr-2"></i> Extra Person Charge:
                                            {{ $room->extra_person_charge }}
                                        </p>

                                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                            <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                        </p>
                                        <p class="text-sm italic text-gray-500 mt-1"> {{ $room->description }}
                                        </p>
                                        <p class="mt-4 text-lg font-medium">
                                            Rate Per Night:
                                            <span class="text-green-700 font-bold">{{ $room->amount }}</span>
                                        </p>
                                        <a href="#" class="text-sm mt-2 hover:underline inline-block text-gray-600">
                                            See more details
                                        </a>

                                    </div>

                                    <div class="mt-auto pt-2">

                                        <div class="flex gap-2">

                                            <!-- Adults -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                <select wire:model.live="adults.{{ $room->id }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                    @for ($i = 0; $i <= $room->ideal_guest; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <!-- Kids -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">Children</label>
                                                <select wire:model.live="kids.{{ $room->id }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                    @for ($i = 0; $i <= $room->ideal_guest; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div>
                                                <button wire:click="addRoomToCart({{ $room->id }})"
                                                    class="px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 text-white rounded">
                                                    Add Room
                                                </button>
                                            </div>

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