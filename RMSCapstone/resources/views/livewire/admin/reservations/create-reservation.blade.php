<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg shadow-md">

    <h2 class="mb-6 text-2xl font-bold text-gray-900 text-center">Add New Reservation</h2>

    <div class="space-y-6">

        <!------------------------- ENTER DATES SECTION -------------------------->

        <!-- Check in and check out dates-->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
                <input type="date" wire:model.live="check_in_date"
                    min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}"
                    class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                    placeholder="Check-in">

                <h1><i class="fas fa-arrow-right"></i></h1>

                <input type="date" wire:model.live="check_out_date"
                    min="{{ isset($check_in_date) ? \Carbon\Carbon::parse($check_in_date)->addDay()->format('Y-m-d') : \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d') }}"
                    class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                    placeholder="Check-out">
            </div>

        </div>

        <!------------------------------ Reservation Date Details --------------------------->
        @php
        use Carbon\Carbon;
        @endphp

        @if ($check_in_date)
        <div
            class="-mt-6 -mx-6 mb-4 bg-gray-100 text-green-700 text-center text-lg font-semibold py-2 rounded-t-lg shadow-sm">
            Reservation Summary
        </div>

        <div class="flex justify-center items-center text-md text-gray-800 space-x-4">
            <span>
                {{ Carbon::parse($check_in_date)->format('F j, Y') }}
            </span>

            @error('check_in_date')
            <span class="text-red-600">{{ $message }}</span>
            @enderror

            <i class="fa-solid fa-arrow-right"></i>
            @if ($check_out_date)
            <span>
                {{ Carbon::parse($check_out_date)->format('F j, Y') }}
            </span>
            @endif
        </div>
        @endif

        @if ($check_out_date)
        <div class="flex justify-center items-center text-md text-gray-800 mb-2 space-x-4">
            <!-- Stay Duration -->
            <p class="text-center">Stay Duration: {{ $this->stayDuration }} night(s)</p>
        </div>
        @endif

        @if ($check_in_date)
        <hr class="my-2 border-gray-200">
        @endif

        <p class="mt-4">Total Guests: {{ $this->total_pax }}</p>

        <!-- Total Amount -->
        <div class="flex justify-between items-center font-semibold text-green-700 mb-1">
            <div class="text-lg">Total</div>
            <div class="text-lg">₱{{ number_format($this->computeTotalAmount(), 2) }}</div>
        </div>

        <!-- Deposit -->
        <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
            <div>Deposit</div>
            <div class="font-semibold">
                ₱{{ number_format($this->deposit ?? 0, 2) }}</div>
        </div>

        <button wire:click="debug"
            class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            + Debug
        </button>


        <!---------------------- ROOM AND ACTIVITY CART -------------------------->

        <div class="space-y-6"> <!-- Stack vertically with spacing -->

            <!-- Room Cart Table (Top) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">ACCOMMODATIONS</h3>

                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full table-auto border border-gray-300 bg-white">
                        <thead class="bg-green-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Room Name</th>
                                <th class="px-4 py-2 border">Total Adults</th>
                                <th class="px-4 py-2 border">Total Kids</th>
                                <th class="px-4 py-2 border">Extra Guest</th>
                                <th class="px-4 py-2 border">Base Rate</th>
                                <th class="px-4 py-2 border">Extra Charge</th>
                                <th class="px-4 py-2 border">Total Amount</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($selectedRooms as $room)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $room['room_name'] }} {{ $room['roomRate'] }}
                                </td>
                                <td class="px-4 py-2 border">{{ $room['adults'] }}</td>
                                <td class="px-4 py-2 border">{{ $room['kids'] }}</td>
                                <td class="px-4 py-2 border">{{ $room['extra_guest'] }}</td>
                                <td class="px-4 py-2 border">{{ $room['roomAmount'] }}</td>
                                <td class="px-4 py-2 border">{{ $room['extra_charge'] }}</td>
                                <td class="px-4 py-2 border">{{ $room['total_amount'] }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <button wire:click="RemoveRoom({{ $room['room_id'] }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                    No rooms added yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="text-right">Total Amount: ₱{{ number_format($this->computeTotalAmountOfAllRooms(), 2) }}</p>


                <!-- Add Room Button -->
                <div class="flex justify-end mt-4">
                    <button wire:click="OpenRoomModal"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        + Add Room
                    </button>
                </div>
            </div>


            <!-- Activity Cart Table (Bottom) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">ADD ONS</h3>

                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full table-auto border border-gray-300 bg-white">
                        <thead class="bg-green-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Room Name</th>
                                <th class="px-4 py-2 border">Price</th>
                                <th class="px-4 py-2 border">Quantity</th>
                                <th class="px-4 py-2 border">Total Amount</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($selectedActivities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $activity['activity_name'] }}</td>
                                <td class="px-4 py-2 border">{{ $activity['activity_rate'] }}</td>
                                <td class="px-4 py-2 border">{{ $activity['quantity'] }}</td>
                                <td class="px-4 py-2 border">{{ $activity['amount'] }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <button wire:click="RemoveActivity({{ $activity['activity_id'] }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                    No activities added yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="text-right">Total Amount: ₱{{ number_format($this->computeTotalAmountOfAllActivities(), 2) }}
                </p>


                <!-- Add Activity Button -->
                <div class="flex justify-end mt-4">
                    <button wire:click="OpenActivityModal"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        + Add Activity
                    </button>
                </div>
            </div>

        </div>

        <!------------------------- GUEST DETAIL SECTION ------------------------->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- First Name -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" wire:model="first_name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('first_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Middle Name -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input type="text" wire:model="middle_name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('middle_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" wire:model="last_name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('last_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Number -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="text" wire:model="contact_number"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('contact_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Country -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                <input type="text" wire:model="country"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('country')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Company Name -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <input type="text" wire:model="company_name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                @error('last_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-1">
                <!-- Source of Hearing -->
                <label class="block text-sm font-medium text-gray-700 mb-1">Heard From</label>
                <select wire:model="heard_from"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="">Select an option</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Youtube">Youtube</option>
                    <option value="Google">Google</option>
                </select>
                @error('heard_from')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>


        <!-- Additional Guests Section (Optional) -->
        <div class="flex flex-col space-y-2 w-full">
            <div class="font-semibold">
                Additional Guests (Optional)
            </div>

            <!-- Displaying Added Guests -->
            <div class="mt-6">
                @if(count($guests) > 0)
                <ul class="space-y-2">
                    @foreach($guests as $guest)
                    <li class="flex justify-between items-center p-2 bg-gray-100 rounded-md">
                        <span>{{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}</span>
                        <div class="space-x-2">
                            <button wire:click="editGuest({{ $loop->index }})" class="text-blue-500">Edit</button>
                            <button wire:click="deleteGuest({{ $loop->index }})" class="text-red-500">Delete</button>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p>No guests added yet.</p>
                @endif
            </div>

            <!-- Button to open modal -->
            @if(count($guests) < $total_pax - 1) <div class="mt-4">
                <button type="button" wire:click="openGuestModal"
                    class="inline-flex items-center px-3 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-1"></i> Add Guest
                </button>
        </div>
        @endif

    </div>



    <!------------------------- MODALS SECTION ------------------------->

    <!-- Room Modal -->
    @if ($roomModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">

            @error('selectedRooms')
            <span class="text-red-600">{{ $message }}</span>
            @enderror

            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h2 class="text-2xl font-semibold text-gray-800">Choose Rooms</h2>

                <button wire:click="$set('roomModal', false)"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none">
                    &times;
                </button>
            </div>

            <!-- Body / Room List -->
            <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                @if ($rooms->count() === 0)
                <div class="w-full flex justify-center">
                    <div class="step-one w-full px-4">
                        <div
                            class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
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
                <div>
                    @foreach ($rooms as $room)
                    <div wire:key="room-{{ $room->id }}"
                        class="bg-white border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-6">
                        <div class="flex flex-col md:flex-row md:space-x-6">

                            <!-- Room Info -->
                            <div class="md:w-2/3 space-y-2">
                                <h4 class="text-xl font-semibold text-gray-800">{{ $room->name_number }}</h4>

                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-user mr-2 text-gray-500"></i> Ideal Guests:
                                    {{ $room->ideal_guest }}
                                </p>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-users mr-2 text-gray-500"></i> Max: {{ $room->max_adults }}
                                    Adults, {{ $room->max_kids }} Kids
                                </p>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-plus mr-2 text-gray-500"></i> Extra Person Charge:
                                    {{ $room->extra_person_charge }}
                                </p>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-utensils mr-2 text-gray-500"></i> Free breakfast included
                                </p>
                                <p class="text-xs italic text-gray-500 mt-1">{{ $room->description }}</p>

                                <p class="mt-3 text-base font-medium">
                                    Rate Per Night:
                                    <span class="text-green-700 font-bold">{{ $room->amount }}</span>
                                </p>
                            </div>

                            <!-- Booking Controls -->
                            <div class="md:w-1/3 flex flex-col justify-between mt-4 md:mt-0 space-y-4">
                                <div class="flex gap-4">
                                    <!-- Adults -->
                                    <div class="w-1/2">
                                        <label class="block text-sm font-medium text-gray-700">Adults</label>
                                        <select wire:model.live="adults.{{ $room->id }}"
                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1 focus:ring-green-500 focus:border-green-500">
                                            @for ($i = 1; $i <= $room->max_adults; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                        </select>
                                    </div>

                                    <!-- Kids -->
                                    <div class="w-1/2">
                                        <label class="block text-sm font-medium text-gray-700">Children</label>
                                        <select wire:model.live="kids.{{ $room->id }}"
                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1 focus:ring-green-500 focus:border-green-500">
                                            @for ($i = 0; $i <= $room->max_kids; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>





                                <!-- Add Room Button -->

                                @php
                                $isSelected = collect($selectedRooms)->contains('room_id', $room->id);
                                @endphp

                                <button wire:click="{{ $isSelected ? 'RemoveRoom' : 'SelectedRooms' }}({{ $room->id }})"
                                    class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150"
                                    wire:loading.attr="disabled">

                                    <div class="flex items-center justify-center">
                                        <span wire:loading wire:target="SelectedRooms({{ $room->id }})" class="mr-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                </path>
                                            </svg>
                                        </span>

                                        <span wire:loading.remove
                                            wire:target="{{ $isSelected ? 'RemoveRoom' : 'SelectedRooms' }}({{ $room->id }})">
                                            {{ $isSelected ? 'Remove Room' : 'Add Room' }}
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Activity Modal -->
    @if ($activityModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">

            @error('selectedActivities')
            <span class="text-red-600">{{ $message }}</span>
            @enderror

            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                <h2 class="text-2xl font-semibold text-gray-800">Choose Activities</h2>

                <button wire:click="$set('activityModal', false)"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none">
                    &times;
                </button>
            </div>

            <!-- Body / Room List -->
            <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                @if ($activities->count() === 0)
                <div class="w-full flex justify-center">
                    <div class="step-one w-full px-4">
                        <div
                            class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                            <div class="p-4 text-center">
                                <h4 class="text-2xl font-semibold mb-2">No Activities Available</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div>
                    @foreach ($activities as $activity)
                    <div wire:key="activity-{{ $activity->id }}"
                        class="bg-white border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-6">
                        <div class="flex flex-col md:flex-row md:space-x-6">

                            <!-- Activity Info -->
                            <div class="md:w-2/3 space-y-2">
                                <h4 class="text-xl font-semibold text-gray-800">{{ $activity->name }}</h4>

                                <div class="flex flex-col">
                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                                    <div class="flex items-center">
                                        <button type="button"
                                            wire:click.prevent="decrementActivity('{{ $activity->id }}')"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline">
                                            -
                                        </button>

                                        <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                            {{ min($quantity[$activity->id] ?? 1, $total_pax) }}
                                        </span>

                                        @if (($quantity[$activity->id] ?? 1) < $total_pax) <button type="button"
                                            wire:click.prevent="incrementActivity('{{ $activity->id }}')"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline">
                                            +
                                            </button>
                                            @else
                                            <span class="text-gray-500 text-xs ml-2">
                                                Maximum quantity reached
                                            </span>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Add Activity Button -->
                            <div class="md:w-1/3 flex flex-col justify-between mt-4 md:mt-0 space-y-4">
                                @php
                                $isSelected = collect($selectedActivities)->contains('activity_id', $activity->id);
                                @endphp

                                <button
                                    wire:click="{{ $isSelected ? 'RemoveActivity' : 'SelectedActivities' }}({{ $activity->id }})"
                                    class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150"
                                    wire:loading.attr="disabled">

                                    <div class="flex items-center justify-center">
                                        <span wire:loading wire:target="SelectedActivities({{ $activity->id }})"
                                            class="mr-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                </path>
                                            </svg>
                                        </span>

                                        <span wire:loading.remove
                                            wire:target="{{ $isSelected ? 'RemoveActivity' : 'SelectedActivities' }}({{ $activity->id }})">
                                            {{ $isSelected ? 'Remove Activity' : 'Add Activity' }}
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Edit Guest Details</h2>

            <!-- Guest Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">First Name</label>
                    <input type="text" wire:model.defer="editingGuest.guest_first_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                    @error('editingGuest.guest_first_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Middle Name</label>
                    <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    @error('editingGuest.guest_middle_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Last Name</label>
                    <input type="text" wire:model.defer="editingGuest.guest_last_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                    @error('editingGuest.guest_last_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Suffix</label>
                    <input type="text" wire:model.defer="editingGuest.guest_suffix"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    @error('editingGuest.guest_suffix') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Guest Type -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Guest Type</label>
                <select wire:model.defer="editingGuest.guest_type_id"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Guest Type</option>
                    @foreach($guest_types as $type)
                    <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                    @endforeach
                </select>
                @error('editingGuest.guest_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Gender -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Gender</label>
                <select wire:model.defer="editingGuest.guest_gender"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                @error('editingGuest.guest_gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Residency -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Residency</label>
                <select wire:model.defer="editingGuest.guest_residency"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Residency</option>
                    <option value="local">Local</option>
                    <option value="foreigner">Foreigner</option>
                </select>
                @error('editingGuest.guest_residency') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country of Origin -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Country of Origin</label>
                <input type="text" wire:model.defer="editingGuest.guest_country_of_origin"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                @error('editingGuest.guest_country_of_origin') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 mt-6">
                <button wire:click="$set('showEditModal', false)"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button wire:click="updateGuest" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Guest Modal -->
    @if($showGuestModal)
    <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Enter Guest Details</h2>

            <!-- Guest Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">First Name</label>
                    <input type="text" wire:model="guest_first_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                    @error('guest_first_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Middle Name</label>
                    <input type="text" wire:model="guest_middle_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    @error('guest_middle_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Last Name</label>
                    <input type="text" wire:model="guest_last_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                    @error('guest_last_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Suffix</label>
                    <input type="text" wire:model="guest_suffix"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    @error('guest_suffix') <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Guest Type -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Guest Type</label>
                <select wire:model="guest_type_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Guest Type</option>
                    @foreach($guest_types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('guest_type_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gender -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Gender</label>
                <select wire:model="guest_gender" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                @error('guest_gender') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Residency -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Residency</label>
                <select wire:model="guest_residency" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    <option value="">Select Residency</option>
                    <option value="local">Local</option>
                    <option value="foreigner">Foreigner</option>
                </select>
                @error('guest_residency') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country of Origin -->
            <div class="mt-4">
                <label class="block text-sm text-gray-700">Country of Origin</label>
                <input type="text" wire:model="guest_country_of_origin"
                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                @error('guest_country_of_origin') <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Actions -->
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" wire:click="closeGuestModal"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="button" wire:click="addMultipleGuests"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white transition duration-150 ease-in-out">
                    Add Guest
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Guest Modal -->
    @if($addRoomFirstModal)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">No Rooms Selected</h2>
            <p class="text-gray-700">To add activities, please add one or more rooms first.</p>
            <div class="mt-4 text-right">
                <button wire:click="$set('addRoomFirstModal', false)"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif


    <!------------------------- BUTTON TO SUBMIT ------------------------->
    <button type="button" wire:click="CreateReservation"
        class="inline-flex items-center px-3 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
        Create Reservation
    </button>

</div>
</div>