<div class="min-h-screen p-10 bg-white">

    <form wire:submit.prevent="register">

        <!-- STEP 1: Choose Room -->
        @if ($currentStep == 1)
            <div class="w-full flex justify-center">
                <div class="step-one w-full max-w-7xl px-4 py-2">
                    <div class="header">
                        <!-- Title -->
                        <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Book Your Stay</h1>
                        <!-- Date Picker & Search -->
                        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
                            <input type="date" wire:model="check_in_date"
                                class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                                placeholder="Check-in">
                            <input type="date" wire:model="check_out_date"
                                class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                                placeholder="Check-out">
                            <button
                                class="px-4 py-3 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                Search Availability
                            </button>
                        </div>
                    </div>
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Room Listings -->
                        <div class="lg:col-span-2 space-y-6">
                            @foreach ($rooms as $room)
                                <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                                    <div class="md:flex">
                                        <!-- Image -->
                                        <img src="{{ $room->image_url ?? 'images/rms-default.png' }}"
                                            alt="{{ $room->name }}" class="w-full md:w-1/3 h-60 object-cover">

                                        <!-- Room Card -->
                                        <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4">
                                            <!-- Room Info -->
                                            <div class="md:w-2/3">
                                                <h4 class="text-2xl font-semibold mb-2"> {{ $room->name }}</h4>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-user mr-2"></i> Ideal Guests:
                                                    {{ $room->ideal_guest }}
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-users mr-2"></i>Maximum Capacity:
                                                    {{ $room->max_adults }} Adults, {{ $room->max_kids }} Kids
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-plus mr-2"></i> Extra Person Charge: 1000
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                                </p>

                                                <p class="text-sm italic text-gray-500 mt-1">{{ $room->description }}
                                                </p>

                                                <p class="mt-4 text-lg font-medium">
                                                    Rate Per Night:
                                                    <span
                                                        class="text-green-700 font-bold">₱{{ number_format($room->base_rate, 2) }}</span>
                                                </p>

                                                <a href="#"
                                                    class="text-sm mt-2 hover:underline inline-block text-gray-600">See
                                                    more details</a>
                                            </div>

                                            <!-- Booking Inputs -->
                                            <div class="mt-auto pt-2">
                                                <div class="flex gap-2">
                                                    <!-- Adults -->
                                                    <div class="flex-1">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                        <select wire:model="adults"
                                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                            @for ($i = 0; $i <= 4; $i++)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <!-- Children -->
                                                    <div class="flex-1">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">Children</label>
                                                        <select wire:model="children"
                                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                            @for ($i = 0; $i <= 4; $i++)
                                                                <option value="{{ $i }}">
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <!-- Rooms -->
                                                    {{-- <div class="flex-1">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 me-2">Rooms</label>
                                                        <select wire:model="rooms"
                                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                            @for ($i = 0; $i <= 5; $i++)
                                                                <option value="{{ $i }}">
                                                                    {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div> --}}
                                                </div>

                                                <!-- Add to Cart Button -->
                                                <div>
                                                    <button wire:click="selectRoom({{ $room->id }})"
                                                        class="px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                                        Add Room
                                                    </button>
                                                </div>

                                                <!-- Error Display -->
                                                @error('room_id')
                                                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Booking Summary -->
                        <div class="w-96 h-full bg-white dark:bg-gray-800 dark:border-gray-700">
                            <!-- Card Header -->
                            <div
                                class="text-lg bg-gray-100 border-l border-r border-t dark:bg-gray-700 text-center font-semibold text-green-700 dark:text-gray-400 rounded-t-lg p-2">
                                Reservation Summary
                            </div>
                            <!-- Card Body -->
                            <div class="p-4 border-l border-r border-b border-gray-200 shadow-sm rounded-b-lg">
                                <div class="flex flex-col items-center text-md text-gray-800 mb-2">
                                    <!-- Label -->
                                    {{-- <span class="text-md font-semibold text-gray-700 mb-1">Stay Period</span> --}}

                                    <!-- Dates -->
                                    <div class="flex items-center">
                                        Apr 14, 2025
                                        <i class="fa-solid fa-arrow-right px-4"></i>
                                        Apr 16, 2025
                                    </div>
                                </div>

                                <!-- Room Selected -->
                                <div class="flex justify-between items-center">
                                    <!-- Always show the title -->
                                    <div>
                                        <strong>Room:</strong>
                                        <!-- Show name only if a room is selected -->
                                        @if ($room_id)
                                            {{ $rooms->firstWhere('id', $room_id)?->name }}
                                        @endif
                                    </div>

                                    <!-- Optional: show the price only when selected -->
                                    @if ($room_id)
                                        <div class="text-md font-semibold text-gray-800">
                                            ₱{{ $rooms->firstWhere('id', $room_id)?->base_rate }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                                    <div>Pax: </div>
                                </div>

                                <!-- Price Breakdown -->
                                <hr class="my-2 border-gray-200">
                                <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                                    <div>Subtotal</div>
                                    <div>₱ </div>
                                </div>
                                <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                                    <div>Total</div>
                                    <div class="text-lg">₱ </div>
                                </div>
                                <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
                                    <div>Deposit</div>
                                    <div class="font-semibold">₱ </div>
                                </div>
                                @if (($currentStep == 1) | ($currentStep == 2) | ($currentStep == 3))
                                    <button type="button"
                                        class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                        wire:click="increaseStep()">Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!-- STEP 2: Choose Activity -->
        @if ($currentStep == 2)
            <div class="step-two">
                <div class="rounded-xl shadow bg-white">
                    <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 2 - Choose
                        an
                        Activity</div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="activity_id"
                                        class="block mb-2 text-sm font-medium text-gray-900">Activity</label>
                                    <select wire:model="activity_id" id="activity_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                        <option value="">Select Activity</option>
                                        @foreach ($activities as $activity)
                                            <option value="{{ $activity->id }}">{{ $activity->name }} -
                                                {{ $activity->amount }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('activity_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <!-- STEP 3: Guest Info -->
        @if ($currentStep == 3)
            <div class="w-full flex justify-center">
                <div class="step-one w-full max-w-7xl px-4 py-2">
                    <div class="header">
                        <!-- Title -->
                        <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Confirm Your Reservation</h1>
                    </div>
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Room Listings -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="rounded-xl shadow bg-white overflow-hidden">
                                <!-- Header -->
                                <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl">
                                    Guest Details
                                </div>

                                <!-- Form Body -->
                                <div class="p-6 lg:col-span-2 space-y-6">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                        <!-- First Name -->
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">First
                                                Name</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="first_name">
                                            @error('first_name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Last Name -->
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Last
                                                Name</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="last_name">
                                            @error('last_name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Contact Number -->
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact
                                                Number</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="contact_number">
                                            @error('contact_number')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="email">
                                            @error('email')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Address - Street -->
                                        <div class="lg:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Street
                                                Address</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="address_street">
                                            @error('address_street')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Address - City -->
                                        <div class="lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="address_city">
                                            @error('address_city')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Address - Province -->
                                        <div class="lg:col-span-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                wire:model="address_province">
                                            @error('address_province')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Booking Summary -->
                        <div class="w-96 h-full bg-white dark:bg-gray-800 dark:border-gray-700">
                            <!-- Card Header -->
                            <div
                                class="text-lg bg-gray-100 border-l border-r border-t dark:bg-gray-700 text-center font-semibold text-green-700 dark:text-gray-400 rounded-t-lg p-2">
                                Reservation Summary
                            </div>
                            <!-- Card Body -->
                            <div class="p-4 border-l border-r border-b border-gray-200 shadow-sm rounded-b-lg">
                                <div class="flex flex-col items-center text-md text-gray-800 mb-2">
                                    <!-- Label -->
                                    {{-- <span class="text-md font-semibold text-gray-700 mb-1">Stay Period</span> --}}

                                    <!-- Dates -->
                                    <div class="flex items-center">
                                        Apr 14, 2025
                                        <i class="fa-solid fa-arrow-right px-4"></i>
                                        Apr 16, 2025
                                    </div>
                                </div>

                                <!-- Room Selected -->
                                <div class="flex justify-between items-center">
                                    <!-- Always show the title -->
                                    <div>
                                        <strong>Room:</strong>
                                        <!-- Show name only if a room is selected -->
                                        @if ($room_id)
                                            {{ $rooms->firstWhere('id', $room_id)?->name }}
                                        @endif
                                    </div>

                                    <!-- Optional: show the price only when selected -->
                                    @if ($room_id)
                                        <div class="text-md font-semibold text-gray-800">
                                            ₱{{ $rooms->firstWhere('id', $room_id)?->base_rate }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                                    <div>Pax: </div>
                                </div>

                                <!-- Price Breakdown -->
                                <hr class="my-2 border-gray-200">
                                <div class="flex justify-between items-center text-sm text-gray-600 mb-1">
                                    <div>Subtotal</div>
                                    <div>₱ 4,950.00</div>
                                </div>
                                <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                                    <div>Total</div>
                                    <div class="text-lg">₱ 5,544.00</div>
                                </div>
                                <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
                                    <div>Deposit</div>
                                    <div class="font-semibold">₱ 2,772.00</div>
                                </div>
                                @if (($currentStep == 1) | ($currentStep == 2) | ($currentStep == 3))
                                    <button type="button"
                                        class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                        wire:click="increaseStep()">Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- STEP 4: Payment Receipt -->
        @if ($currentStep == 4)
            <div class="step-four">
                <div class="rounded-xl shadow bg-white">
                    <div class="bg-green-600 text-white text-lg font-semibold px-4 py-2 rounded-t-xl">STEP 4 - Payment
                        Details</div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Payment Reference Number -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Reference Number</label>
                                <input type="text" class="w-full border rounded-md px-3 py-2" placeholder=""
                                    wire:model="payment_reference_number">

                                @error('payment_reference_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Screenshot -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Screenshot</label>

                                @if ($this->payment_screenshot)
                                    <div>
                                        <label>Uploaded Screenshot:</label>
                                        <img src="{{ asset('storage/' . $this->payment_screenshot) }}"
                                            alt="Payment Screenshot" width="200">
                                    </div>
                                @endif

                                <input type="file" class="w-full border rounded-md px-3 py-2"
                                    wire:model="payment_screenshot">

                                @error('payment_screenshot')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Terms -->
                            <div class="col-span-2">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" id="terms" wire:model="terms"
                                        class="border-gray-300 rounded">
                                    <span class="text-sm">You must agree with our <a href="#"
                                            class="text-blue-600 underline">Terms and Condition</a></span>

                                    @error('terms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 pt-4">

            @if ($currentStep == 1)
                <div></div>
            @endif

            {{-- Back Button --}}
            @if (($currentStep == 2) | ($currentStep == 3) | ($currentStep == 4))
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                    wire:click="decreaseStep()">Back</button>
            @endif

            {{-- Next Button --}}
            @if (($currentStep == 1) | ($currentStep == 2) | ($currentStep == 3))
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm"
                    wire:click="increaseStep()">Next</button>
            @endif

            {{-- Submit Button --}}
            @if ($currentStep == 4)
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Submit</button>
            @endif

        </div>

    </form>
</div>
