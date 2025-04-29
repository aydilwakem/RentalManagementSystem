<div class="min-h-screen p-10 bg-white">
    <div class="flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">

        {{-- Left Side: Form Steps --}}
        <div class="w-full flex justify-center">
            <div class="w-2/3">

                <div class="header">
                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Book Your Stay</h1>
                    <!-- Date Picker & Search -->
                    <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
        
        
                        <input type="date" wire:model.live="check_in_date"
                            class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                            placeholder="Check-in">
        
                        <h1><i class="fas fa-arrow-right"></i></h1>
        
                        <input type="date" wire:model.live="check_out_date"
                            class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                            placeholder="Check-out">
        
        
                        {{-- <button
                            class="px-4 py-3 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                            Search Availability
                        </button> --}}
                    </div>
                </div>

                {{-- <div> --}}
                    {{-- <label for="checkin">Check-in Date & Time:</label>
                    <input type="datetime-local" id="checkin" wire:model.live="check_in_date">

                    <label for="checkout">Check-out Date & Time:</label>
                    <input type="datetime-local" id="checkout" wire:model.live="check_out_date"> --}}
                    {{--
                </div> --}}


                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <!-- Choose a Room -->
                @if ($currentStep == 1)
                    <div class="step-room">
                        @include('livewire.guest.reservation.choose-room')
                    </div>
                @endif

                <!-- Choose an Activity -->
                @if($currentStep == 2)
                    <div class="step-activity">
                        @include('livewire.guest.reservation.choose-activity')
                    </div>
                @endif

                <!-- Enter Guest Details -->
                @if ($currentStep == 3)
                    <div class="step-guest-details">
                        @include('livewire.guest.reservation.guest-detail')
                    </div>
                @endif

                <!-- Review reservation -->
                @if ($currentStep == 4)
                    <div class="step-review">
                        @include('livewire.guest.reservation.review')
                    </div>
                @endif


            </div>
        </div>

        {{-- Summary Tab:

        This section displays a live summary of the guest’s reservation, including:
        - Formatted check-in and check-out dates
        - Duration of stay in nights
        - Selected rooms with guest count (adults & kids)
        - Selected activities with quantity
        - Total number of guests

        It also allows the user to remove items (rooms/activities) from the reservation cart.
        --}}

        <div class="w-full lg:w-1/3 bg-gray-50 border border-gray-200 rounded-lg shadow-md p-6 h-fit sticky top-10">

    
            <!------------------------------ Reservation Date Details --------------------------->

            @php
                use Carbon\Carbon;
            @endphp

            <div class="flex items-center text-md text-gray-800 mb-2">
                <span>
                    {{ $check_in_date ? Carbon::parse($check_in_date)->format('F j, Y g:i A') : '' }}
                </span>

                @error('check_in_date') <span class="text-red-600">{{ $message }}</span> @enderror

                <i class="fa-solid fa-arrow-right px-4"></i>
                <span>
                    {{ $check_out_date ? Carbon::parse($check_out_date)->format('F j, Y g:i A') : '' }}
                </span>

                @error('check_out_date') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Stay Duration -->
            <p>Stay Duration: {{ $this->stayDuration }} night(s)</p>

            <hr class="my-2 border-gray-200">


            <!------------------------------ Selected Items ------------------------------------->
            @php
                $cartCollection = collect($cart);  // Convert array to collection
            @endphp


            <div>
                @if ($cartCollection->isNotEmpty())

                    <div class="flex items-start gap-2 mb-2 py-2">
                        {{-- Selected Rooms --}}
                        @if ($cartCollection->contains('type', 'room'))
                            @foreach ($cart as $item)
                                @if ($item['type'] === 'room')
                                    <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1"
                                        wire:key="cart-item-{{ $item['room_id'] }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="text-gray-900">
                                                <strong>Room:</strong> {{ $item['room_name'] }}<br>
                                                <span class="text-sm">Adults: {{ $item['adults'] }}, Kids:
                                                    {{ $item['kids'] }}</span>
                                                    <span class="text-sm">Extra Guest: {{ $item['extra_guest'] }}</span>
                                                    <span class="text-sm">Extra Charge: {{ $item['extra_charge'] }}</span>
                                                    <span class="text-sm">Total Amount: {{ $item['total_amount'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                    wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['room_id'] }})"
                                                    class="text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full w-5 h-5 flex items-center justify-center transition"
                                                    title="Remove Room">
                                                    <span class="text-xl leading-none">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        {{-- Selected Activities --}}
                        @if ($cartCollection->contains('type', 'activity'))
                            @foreach ($cart as $item)
                                @if ($item['type'] === 'activity')
                                    <div class="flex justify-between items-center mb-2" wire:key="cart-item-{{ $item['activity_id'] }}">
                                        <div class="text-gray-900">
                                            <strong>Activity:</strong> {{ $item['activity_name'] }}<br>
                                            <span class="text-sm">Quantity: {{ $item['quantity'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-md font-semibold text-green-700">
                                                ₱{{ number_format($item['amount'], 2) }}
                                            </div>
                                            <button type="button"
                                                wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                                                class="text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full w-5 h-5 flex items-center justify-center transition"
                                                title="Remove Activity">
                                                <span class="text-xl leading-none">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                @else
                    <!-- Show when no room is selected -->
                    <div class="flex flex-col items-center justify-center text-gray-500 text-sm py-6">
                        <i class="fa-solid fa-bed text-3xl mb-2"></i>
                        <span>No rooms added yet</span>
                    </div>
                @endif

                <p class="mt-4">Total Guests: {{ $total_pax }}</p>
    
            </div>

            @error('cart') <span class="text-red-600">{{ $message }}</span> @enderror


            <!------------------------------ Price Breakdown ------------------------------------->
            <hr class="my-2 border-gray-200">

            <!-- Total Amount -->
            <div class="flex justify-between items-center font-semibold text-gray-900 mb-3">
                <div>Total</div>
                <div class="text-lg">{{ $this->computeTotalAmount() }}</div>
            </div>

            <!-- Deposit -->
            <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
                <div>Deposit</div>
                <div class="font-semibold">
                    {{ $total_amount > 0 ? number_format($total_amount * 0.5, 2) : '₱0.00' }}</div>
            </div>

            <!-- Navigation Buttons-->
            <div class="mt-6 flex justify-between">

                @if ($currentStep == 1)
                    <div> </div>
                @endif

                <!-- Back button -->
                @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded" wire:click="decreaseStep()">Back</button>
                @endif

                <!-- Next button -->
                @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3)
                    <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" wire:click="increaseStep()">
                        Next</button>
                @endif

                <!-- Confirm Reservation button -->
                @if ($currentStep == 4)
                    <button type="button" class="px-4 py-2 bg-green-600 text-white rounded" wire:click="register">Confirm
                        Reservation</button>
                @endif
            </div>

        </div>




    </div>
</div>


{{-- SUMMARY TAB --}



{{-- <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1">
    <div class="flex justify-between items-center">
        @foreach ($cart as $item)
        @if ($item['type'] === 'room')
        <li wire:key="cart-item-{{ $item['room_id'] }}">
            {{ $item['room_name'] }} – Adults: {{ $item['adults'] }}, Kids: {{ $item['kids'] }}
            <button wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['room_id'] }})"
                class="text-red-600 ml-2">Remove</button>
        </li>
        @endif
        @endforeach
    </div>
</div> --}}




{{-- <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1">
    <div class="flex justify-between items-center">
        @if ($cartCollection->contains('type', 'activity'))
        <!-- Check if the cart contains any activities -->
        <h4 class="text-lg font-bold mt-4">Your Selected Activities:</h4>
        <ul class="list-disc pl-6">

            @foreach ($cart as $item)
            @if ($item['type'] === 'activity')
            <li wire:key="cart-item-{{ $item['activity_id'] }}">
                {{ $item['activity_name'] }} – Quantity: {{ $item['quantity'] }}
                <button wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                    class="text-red-600 ml-2">Remove</button>
            </li>
            @endif
            @endforeach
        </ul>
        @endif
    </div>
</div> --}}