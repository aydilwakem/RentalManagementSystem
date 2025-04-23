<div class="min-h-screen p-10 bg-white">
    <form wire:submit.prevent="register">
        <div class="flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">

            <!---------------------------------------------- MAIN STEPS  --------------------------------------------->
            <div class="w-full flex justify-center">

                <!---------------------------------------------- STEP 1 - CHOOSE A ROOM  -------------------------------------------->
                @if ($currentStep == 1)
                    <div class="w-full flex justify-center">
                        <div class="step-one w-full max-w-7xl px-4">
                            <div class="header">
                                <!-- Title -->
                                <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Book Your Stay</h1>
                                <!-- Date Picker & Search -->
                                <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">


                                <input type="date" wire:model.live="check_in_date"  class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500" 
                                    placeholder="Check-in">

                                <h1><i class="fas fa-arrow-right"></i></h1>

                                <input type="date" wire:model.live="check_out_date" class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"  placeholder="Check-out">


                                    {{-- <button
                                        class="px-4 py-3 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                        Search Availability
                                    </button> --}}
                                </div>
                            </div>

                            <!-- Main Content Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            <!-- Room Listings -->
                            @foreach ($rooms as $room)
                            <div class="lg:col-span-2 space-y-6" wire:key="room-{{ $room->id }}">
                                <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                                    <div class="md:flex">

                                        <!-- Image -->
                                        <div class="mb-4">
                                            <img src="{{ asset($room->image ? 'storage/' . $room->image : 'images/rms-default.png') }}"
                                                class="w-full h-64 object-cover rounded-lg shadow-md">
                                        </div>

                                        <!-- Room Card -->
                                        <div class="md:w-2/3 p-4 flex flex-col md:flex-row justify-between gap-4">
                                            <!-- Room Info -->
                                            <div class="md:w-2/3">
                                                <h4 class="text-2xl font-semibold mb-2">{{ $room->name_number }}</h4>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-user mr-2"></i> Ideal Guests: {{ $room->name_number }}
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-users mr-2"></i> Maximum Capacity: {{ $room->max_adults }} Adults, {{ $room->max_kids }} Kids
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-plus mr-2"></i> Extra Person Charge: 1000
                                                </p>

                                                <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                                                    <i class="fas fa-utensils mr-2"></i> Free breakfast included
                                                </p>

                                                <p class="text-sm italic text-gray-500 mt-1"> {{ $room->description }} </p>

                                                <p class="mt-4 text-lg font-medium">
                                                    Rate Per Night:
                                                    <span class="text-green-700 font-bold">{{ $room->amount }}</span>
                                                </p>

                                                <a href="#"
                                                    class="text-sm mt-2 hover:underline inline-block text-gray-600">
                                                    See more details
                                                </a>
                                            </div>

                                            <!-- Booking Inputs -->
                                            <div class="mt-auto pt-2">
                                                <div class="flex gap-2">

                                                    <!-- Adults -->
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                                    <select wire:model.live="total_adults_by_room.{{ $room->id }}"
                                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                        @for ($i = 0; $i <= $room->max_adults; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <!-- Kids -->
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700">Children</label>
                                                    <select wire:model.live="total_kids_by_room.{{ $room->id }}"
                                                            class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                        @for ($i = 0; $i <= $room->max_kids; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                </div>

                                                <!-- Add to Cart Button -->
                                                <div>
                                                    <button type="button"
                                                        wire:click="addToCart({{ $room->id }})"
                                                        class="px-4 py-2 mt-3 w-full 
                                                            {{ $check_in_date && $check_out_date ? 'bg-green-700 bg-opacity-85 hover:bg-green-800' : 'bg-gray-400 cursor-not-allowed' }}
                                                            border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                                        {{ !$check_in_date || !$check_out_date ? 'disabled' : '' }}>
                                                        Add Room
                                                    </button>
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

                <!---------------------------------------------- STEP 2 - CHOOSE AN ACTIVITY ---------------------------------------->
                @if ($currentStep == 2)
                    <div class="w-full flex justify-center">
                        <div class="step-three w-full max-w-7xl px-4">
                            <div class="header">
                                <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Add Exciting Activities</h1>
                                <p class="text-center text-gray-600 text-md mb-4">
                                    Want to make your stay even more memorable? Choose from our exciting range of activities
                                    designed to enhance your experience. <br>These are completely optional—join in only if
                                    it
                                    feels right for you!
                                </p>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                                <!-- Activities -->
                                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($activities as $activity)
                                    <!-- Activity Card -->
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-4">
                                      
                                          <!-- Image -->
                                          <div class="mb-4">
                                            <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                                                class="w-full h-64 object-cover rounded-lg shadow-md">
                                        </div>


                                        <div class="p-5 pb-3">
                                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $activity->name}}</h2>
                                            <p class="text-gray-600 text-sm mb-4 text-justify">
                                                {{ $activity->description}}
                                            </p>
                                            <p class="text-gray-600 text-sm text-justify"> Inclusions: {{ $activity->inclusions}}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-green-600 font-bold text-lg">{{ $activity->amount}}</span>

                                                <!-- Add to Cart Button -->
                                                <div>
                                                    <button type="button" wire:click="addActivity({{ $activity->id }})"
                                                        class="px-4 py-2 mt-3 w-full bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                                    Add Activity
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!---------------------------------------------- STEP 3 - GUEST DETAILS --------------------------------------------->
                @if ($currentStep == 3)
                    <!-- Step 3 Content Here -->
                    <div class="w-full flex justify-center">
                        <div class="step-three w-full max-w-7xl px-4">
                            <div class="header">
                                <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Confirm Your Reservation</h1>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2 space-y-6">
                                    <div class="rounded-xl shadow bg-white overflow-hidden">
                                        <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl">
                                            Guest Details
                                        </div>

                                        <div class="p-6 lg:col-span-2 space-y-6">
                                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">First
                                                        Name</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="Juan">
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last
                                                        Name</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="Dela Cruz">
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact
                                                        Number</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="0917 123 4567">
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                    <input type="email"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="juan@example.com">
                                                </div>

                                                <div class="lg:col-span-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Street
                                                        Address</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="123 Mabuhay St.">
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="Quezon City">
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                                    <input type="text"
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                        placeholder="Metro Manila">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!---------------------------------------------- SUMMARY TAB  ---------------------------------------------> 
            <div class="w-full lg:w-1/3 bg-gray-50 border border-gray-200 rounded-lg shadow-md p-6 h-fit sticky top-10">
                <h2 class="text-xl font-semibold mb-4 text-green-700">Booking Summary</h2>

                <!-- Reservation Dates -->
                <div class="flex items-center text-md text-gray-800 mb-2">
                    <span>{{ $check_in_date }}</span>
                    <i class="fa-solid fa-arrow-right px-4"></i>
                    <span>{{ $check_out_date }}</span>
                </div>

                <hr class="my-2 border-gray-200">

                <!-- SelectedActivity and SelectedRoom -->
                @if($selectedRoom)
                    <div>
                        <div class="flex items-start gap-2 mb-2 py-2">
                            <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1">
                                <div class="flex justify-between items-center">
                                    <div class="text-gray-900">
                                        <strong class="text-gray-900">Room:</strong>
                                        {{ $selectedRoom->name_number }} 
                                    </div>
                                    <div class="text-md font-semibold text-green-700">
                                        ₱{{ number_format($selectedRoom->amount, 2) }} 
                                    </div>
                                </div>

                                <div class="flex justify-between items-center font-semibold text-gray-900 mt-1">
                                    <div>Pax: {{ $pax }}</div> 
                                </div>
                            </div>

                            <!-- Delete Button -->
                            <button type="button"
                                wire:click="removeRoom({{ $selectedRoom->id }})" 
                                class="text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full w-4 h-4 flex items-center justify-center transition"
                                title="Remove Room">
                                <span class="text-xl leading-none">&times;</span>
                            </button>
                        </div>
                    </div>
                    

    
                <!-- Price Breakdown -->
                <hr class="my-2 border-gray-200">
                <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                    <div>Total</div>
                    <div class="text-lg">₱{{ number_format($total_amount, 2) }}</div>
                </div>

                <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
                    <div>Deposit</div>
                    <div class="font-semibold">₱{{ number_format($total_amount / 2, 2) }}</div>
                </div>

                <!-- Step Navigation -->
                <div class="flex justify-between mt-4">
                    @if ($currentStep == 1)
                        <div></div>
                    @endif

                    {{-- Next Button --}}
                    @if ($currentStep == 1 || $currentStep == 2)
                        <button type="button"
                            class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                            wire:click="increaseStep()">Next</button>
                    @endif

                    {{-- Submit Button --}}
                    @if ($currentStep == 3)
                        <button type="submit"
                            class="mt-4 block w-full px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">Submit</button>
                    @endif
                </div>

                @else
                <!-- Show when no room is selected -->
                <div class="flex flex-col items-center justify-center text-gray-500 text-sm py-6">
                    <i class="fa-solid fa-bed text-3xl mb-2"></i>
                    <span>No rooms added yet</span>
                </div>
                @endif
                
    </form>

            {{-- Back Button --}}
            @if ($currentStep == 2 || $currentStep == 3)
            
            <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md text-sm"
                wire:click="decreaseStep()">Back</button>
            @endif
</div>