<div class="min-h-screen p-10 bg-white">
    <form wire:submit.prevent="register">
        <!-- STEP 1: Choose Room -->
        @if ($currentStep == 1)
        <div class="w-full flex justify-center">
            <div class="step-one w-full max-w-7xl px-4">
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
                                <img src="{{ asset($room->image ? 'storage/' . $room->image : 'images/rms-default.png') }}"
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
                                                        <option value="{{ $i }}">
                                                        {{ $i }}
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

                <!-- Reservation Summary -->
                <div class="w-96 h-full bg-white dark:bg-gray-800 dark:border-gray-700">
                    <div
                        class="text-lg bg-gray-100 border-l border-r border-t dark:bg-gray-700 text-center font-semibold text-green-700 dark:text-gray-400 rounded-t-lg p-2">
                        Reservation Summary
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 border-l border-r border-b border-gray-200 shadow-sm rounded-b-lg">
                        <div class="flex flex-col items-center text-md text-gray-800 mb-2">
                            <!-- Dates -->
                            <div class="flex items-center">
                                Apr 14, 2025
                                <i class="fa-solid fa-arrow-right px-4"></i>
                                Apr 16, 2025
                            </div>
                        </div>

                        <hr class="my-2 border-gray-200 py-1">
                        @if ($room_id)
                        <!-- Room Selected -->
                        <div class="flex items-start gap-2 mb-2 py-2">
                            <div
                                class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1">
                                <div class="flex justify-between items-center">
                                    <div class="text-gray-900">
                                        <strong class="text-gray-900">Room:</strong>
                                        {{ $rooms->firstWhere('id', $room_id)?->name }}
                                    </div>

                                    <div class="text-md font-semibold text-green-700">
                                        ₱{{ $rooms->firstWhere('id', $room_id)?->base_rate }}
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center font-semibold text-gray-900 mt-1">
                                    <div>Pax: </div>
                                    {{-- <i class="fa-solid fa-user me-1"></i> --}}
                                </div>
                            </div>

                            <!-- Delete Button -->
                            <button wire:click="removeRoom({{ $room->id }})"
                                class="text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full w-4 h-4 flex items-center justify-center transition"
                                title="Remove Room">
                                <span class="text-xl leading-none">&times;</span>
                            </button>
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

                        <!-- Next Button -->
                        @if (($currentStep == 1) | ($currentStep == 2) | ($currentStep == 3))
                        <button type="button"
                            class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                            wire:click="increaseStep()">Next</button>
                        @endif
                        @else
                        <!-- Show when no room is selected -->
                        <div class="flex flex-col items-center justify-center text-gray-500 text-sm py-6">
                            <i class="fa-solid fa-bed text-3xl mb-2"></i>
                            <span>No rooms added yet</span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
</div>
@endif

<!-- STEP 2: Choose Activity -->
@if ($currentStep == 2)
<div class="w-full flex justify-center">
    <div class="step-three w-full max-w-7xl px-4">
        <div class="header">
            <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Add Exciting Activities</h1>
            <p class="text-center text-gray-600 text-md mb-4">
                Want to make your stay even more memorable? Choose from our exciting range of activities
                designed to enhance your experience. <br>These are completely optional—join in only if it
                feels right for you!
            </p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activities -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($activities as $activity)
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-4">
                    <img class="w-full h-48 object-cover"
                        src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                        alt="{{ $activity->name }}">

                    <div class="p-5 pb-3">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $activity->name }}</h2>
                        <p class="text-gray-600 text-sm mb-4 text-justify">
                            {{ $activity->description }}
                        </p>
                        <p class="text-gray-600 text-sm text-justify"> Inclusions:
                            {{ $activity->inclusions }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span
                                class="text-green-600 font-bold text-lg">₱{{ number_format($activity->amount, 2) }}</span>
                            <!-- Add to Cart Button -->
                            <div>
                                <button wire:click="addActivity({{ $activity->id }})"
                                    class="px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                    Add Activity
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>

            <!-- Reservation Summary -->
            <div class="w-96 h-full bg-white dark:bg-gray-800 dark:border-gray-700">
                <div
                    class="text-lg bg-gray-100 border-l border-r border-t dark:bg-gray-700 text-center font-semibold text-green-700 dark:text-gray-400 rounded-t-lg p-2">
                    Reservation Summary
                </div>
                <div class="p-4 border-l border-r border-b border-gray-200 shadow-sm rounded-b-lg">
                    <div class="flex flex-col items-center text-md text-gray-800 mb-2">
                        <div class="flex items-center">
                            Apr 14, 2025
                            <i class="fa-solid fa-arrow-right px-4"></i>
                            Apr 16, 2025
                        </div>
                    </div>
                    <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-900">
                                <strong class="text-gray-900">Room:</strong>
                                {{ $rooms->firstWhere('id', $room_id)?->name }}
                            </div>

                            <div class="text-md font-semibold text-green-700">
                                ₱{{ $rooms->firstWhere('id', $room_id)?->base_rate }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center font-semibold text-gray-900 mt-1">
                            <div>Pax: </div>
                            {{-- <i class="fa-solid fa-user me-1"></i> --}}
                        </div>
                        <div class="flex justify-between items-center">
                            <div><strong>Activity:</strong>
                                @if ($room_id)
                                {{ $activity->firstWhere('id', $activity_id)?->name }}
                                @endif
                            </div>
                            @if ($room_id)
                            <div class="text-md font-semibold text-green-700">
                                ₱{{ $activity->firstWhere('id', $activity_id)?->amount }}</div>
                            @endif
                        </div>
                    </div>

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
                    @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3)
                    <button type="button"
                        class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                        wire:click="increaseStep()">Next</button>
                    @endif
                </div>
                <div class="flex justify-end mt-4">
                    {{-- Back Button --}}
                    @if (($currentStep == 2) | ($currentStep == 3) | ($currentStep == 4))
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                        wire:click="decreaseStep()">Back</button>
                    @endif
                </div>
            </div>
        </div>


    </div>
</div>
@endif


<!-- STEP 3: Guest Info -->
@if ($currentStep == 3)
<div class="w-full flex justify-center">
    <div class="step-three w-full max-w-7xl px-4">
        <div class="header">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Confirm Your Reservation</h1>
        </div>
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                            @if ($room_id)
                            {{ $rooms->firstWhere('id', $room_id)?->name }}
                            @endif
                        </div>

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
                <div class="flex justify-end mt-4">
                    {{-- Back Button --}}
                    @if (($currentStep == 2) | ($currentStep == 3) | ($currentStep == 4))
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                        wire:click="decreaseStep()">Back</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endif

<!-- STEP 4: Payment Receipt -->
@if ($currentStep == 4)
<div class="w-full flex justify-center">
    <div class="step-four w-full max-w-7xl px-4">
        <div class="header">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Payment Details</h1>
        </div>
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl shadow bg-white overflow-hidden">
                    <!-- Header -->
                    <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl">
                        Complete payment to reserve your room
                    </div>
                    <!-- Form Body -->
                    <div class="p-6 lg:col-span-2 space-y-6">
                        <div class="space-y-6">

                            <div x-data="{ selected: '' }" class="space-y-4">
                                @foreach ($paymentMethod as $index => $method)
                                @php
                                $optionId = 'option' . $index;
                                @endphp

                                <label
                                    class="block border rounded-lg p-4 cursor-pointer transition duration-300 w-full"
                                    :class="selected === '{{ $optionId }}' ?
                                                        'border-green-600 bg-green-50' : 'border-gray-300'"
                                    @click="selected = '{{ $optionId }}'">

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <input type="radio" name="option"
                                                value="{{ $optionId }}" x-model="selected"
                                                class="text-green-600" />
                                            <span
                                                class="font-medium text-gray-800">{{ $method->mode_of_payment_name }}</span>
                                        </div>
                                        <svg x-show="selected === '{{ $optionId }}'"
                                            class="w-5 h-5 text-green-600" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586
                                                            4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <!-- Payment Details -->
                                    <div x-show="selected === '{{ $optionId }}'" x-transition
                                        class="mt-4 px-2 pt-2 text-center">
                                        @if ($method->account_number)
                                        <div class="text-md font-semibold text-gray-700">
                                            {{ $method->account_name }}
                                        </div>
                                        <div class="text-md font-semibold text-gray-700 mb-2">
                                            {{ $method->account_number }}
                                        </div>
                                        @endif

                                        <!-- QR Code -->
                                        <img src="{{ asset($method->mode_of_payment_qr_image ? 'storage/' . $method->mode_of_payment_qr_image : 'images/rms-default.png') }}"
                                            alt="{{ $method->mode_of_payment_name }}"
                                            class="w-48 h-auto mx-auto rounded-md shadow-sm border border-gray-200 object-contain">
                                    </div>
                                </label>
                                @endforeach
                            </div>



                            <!-- Payment Reference Number -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Reference
                                    Number</label>
                                <input type="text" class="w-full border rounded-md px-3 py-2"
                                    placeholder="" wire:model="payment_reference_number">

                                @error('payment_reference_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Screenshot -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment Screenshot</label>

                                @if ($this->payment_screenshot)
                                <div class="mb-2">
                                    <label class="block text-sm font-medium">Uploaded
                                        Screenshot:</label>
                                    <img src="{{ asset('storage/' . $this->payment_screenshot) }}"
                                        alt="Payment Screenshot" class="w-48 border rounded">
                                </div>
                                @endif

                                <input type="file" class="w-full border rounded-md px-3 py-2"
                                    wire:model="payment_screenshot">

                                @error('payment_screenshot')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Terms -->
                            <div>
                                <label class="flex items-start space-x-2">
                                    <input type="checkbox" id="terms" wire:model="terms"
                                        class="mt-1 border-gray-300 rounded">
                                    <span class="text-sm leading-5">By checking this box, you confirm that you have read and agree to our <a
                                            href="#" class="text-blue-600 underline">Terms and
                                            Condition</a></span>
                                </label>

                                @error('terms')
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
                            @if ($room_id)
                            {{ $rooms->firstWhere('id', $room_id)?->name }}
                            @endif
                        </div>

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
                <div class="flex justify-between items-center mt-4">
                    {{-- Back Button --}}
                    @if (($currentStep == 2) | ($currentStep == 3) | ($currentStep == 4))
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                        wire:click="decreaseStep()">Back</button>
                    @endif
                    {{-- Submit Button --}}
                    @if ($currentStep == 4)
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Submit</button>
                    @endif
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
            {{-- @if (($currentStep == 2) | ($currentStep == 3) | ($currentStep == 4))
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                    wire:click="decreaseStep()">Back</button>
            @endif --}}

            {{-- Next Button --}}
            {{-- @if (($currentStep == 1) | ($currentStep == 2) | ($currentStep == 3))
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm"
                    wire:click="increaseStep()">Next</button>
            @endif --}}

            {{-- Submit Button --}}
            {{-- @if ($currentStep == 4)
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Submit</button>
            @endif --}}

        </div>

        </form>
    </div>