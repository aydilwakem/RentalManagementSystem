<div class="min-h-screen p-10">
    <div class="flex flex-col lg:flex-row gap-4 mx-auto">


        @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        {{-- Left Side: Form Steps --}}
        <div class="w-full flex">
            <div class="w-full">

                <div class="header">
                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Book Your Stay</h1>
                    <!-- Date Picker & Search -->
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
                @if ($currentStep == 2)
                <div class="step-activity">
                    @include('livewire.guest.reservation.choose-activity')
                </div>
                @endif

                <!-- Enter Guest Details -->
                @if ($currentStep == 3)
                <div class="step-guest-details w-full">
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

        <div class="w-full lg:w-1/3 bg-gray-50 border border-gray-200 rounded-lg shadow-md p-6 h-fit sticky top-0 z-10">
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

            <!------------------------------ Selected Items ------------------------------------->
            @php
            $cartCollection = collect($cart); // Convert array to collection
            @endphp


            <div>
                @if ($cartCollection->isNotEmpty())
                <div class="flex flex-col gap-2 mb-2 py-2">
                    <!-- Selected Rooms -->
                    @if ($cartCollection->contains('type', 'room'))
                    @foreach ($cart as $item)
                    @if ($item['type'] === 'room')
                    <!-- Room Card -->
                    <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
                        wire:key="cart-item-{{ $item['room_id'] }}">
                        <!-- Back Button -->
                        <button type="button" wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['room_id'] }})"
                            class="text-gray-700 bg-gray-200 hover:bg-gray-300 hover:text-red-600 rounded-full w-6 h-6 flex items-center justify-center text-2xl absolute top-2 right-2 focus:outline-none"
                            title="Remove Room">
                            <span class="leading-none ">&times;</span>
                        </button>
                        <!-- Room Details -->
                        <div class="text-gray-800 flex flex-col justify-between mt-1">
                            <!-- Room Name -->
                            <div class="text-md">
                                <i class="fa-solid fa-bed"></i>
                                <strong>Room:</strong> {{ $item['room_name'] }}
                            </div>

                            <!-- Guest Info -->
                            <div class="text-sm text-gray-600">
                                Adults: {{ $item['adults'] }}, Kids: {{ $item['kids'] }}
                            </div>

                            <!-- Charges Breakdown -->
                            <div class="flex justify-between items-start gap-2">
                                <!-- Labels -->
                                <div class="space-y-1">
                                    @if ($item['extra_charge'])
                                    <div class="text-sm text-gray-600">Extra Person Charge:</div>
                                    @endif
                                    <div class="text-sm text-gray-600">Subtotal:</div>
                                </div>

                                <!-- Amounts -->
                                <div class="text-right space-y-1">
                                    <div class="text-sm font-semibold text-gray-800">
                                        ₱{{ number_format($item['extra_charge'], 2) }}
                                    </div>
                                    <div class="text-sm font-semibold text-gray-800">
                                        ₱{{ number_format($item['total_amount'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif

                    <!-- Selected Activities -->
                    @if ($cartCollection->contains('type', 'activity'))
                    @foreach ($cart as $item)
                    @if ($item['type'] === 'activity')
                    <!-- Activity Card -->
                    <div class="bg-gray-100 py-3 px-2 pr-8 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
                        wire:key="cart-item-{{ $item['activity_id'] }}">
                        <!-- back Button -->
                        <button type="button"
                            wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['activity_id'] }})"
                            class="text-gray-700 bg-gray-200 hover:bg-gray-300 hover:text-red-600 rounded-full w-6 h-6 flex items-center justify-center text-2xl absolute top-2 right-2 focus:outline-none"
                            title="Remove Activity">
                            <span class="leading-none ">&times;</span>
                        </button>
                        <!-- Activity Details -->
                        <div class="text-gray-800 flex flex-col justify-between mt-1">
                            <div class="text-md">
                                <i class="fa-solid fa-square-plus"></i>
                                <strong>Activity:</strong> {{ $item['activity_name'] }}
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    Quantity: {{ $item['quantity'] }}
                                </div>
                                <div class="text-sm font-semibold">
                                    ₱{{ number_format($item['amount'], 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                    <p class="mt-4">Total Guests: {{ $total_pax }}</p>
                </div>
                @else
                <!-- Show when no room is selected -->
                <div class="flex flex-col items-center justify-center text-gray-500 text-sm py-6">
                    <i class="fa-solid fa-bed text-3xl mb-2"></i>
                    <span>No rooms added yet</span>
                </div>
                @endif
            </div>

            @error('cart')
            <span class="text-red-600">{{ $message }}</span>
            @enderror


            <!------------------------------ Price Breakdown ------------------------------------->
            @if ($cartCollection->contains('type', 'room'))
            <div>
                <hr class="my-2 border-gray-200">

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
            </div>
            @endif

            <!-- Navigation Buttons-->
            @if ($cartCollection->contains('type', 'room'))
            <div class="mt-6 flex justify-between">

                @if ($currentStep == 1)
                <div> </div>
                @endif

                <!-- Back button -->
                @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                <button type="button"
                    class="mt-4 block px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150"
                    wire:click="decreaseStep()"> Back
                </button>
                @endif

                <!-- Next button -->
                @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3)
                <button type="button"
                    class="mt-4 block px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                    wire:click="increaseStep()">Next</button>
                @endif

                {{-- Start of Modal for showing the Terms and Conditions --}}
                <div x-data="{ showModal: false, agreed: false }"
                    x-init="$watch('showModal', value => document.body.classList.toggle('overflow-hidden', value))"
                    @keydown.escape.window="showModal = false">

                    @if ($currentStep == 4)
                    <button type="button"
                        class="mt-4 block px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                        @click="showModal = true">
                        Confirm
                    </button>
                    @endif

                    <!-- Modal -->
                    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                        x-show="showModal" x-transition style="display: none;">
                        <div
                            class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                            <h2 class="text-lg font-semibold mb-4">Terms and Conditions</h2>

                            <div class="text-sm text-gray-800 space-y-3">
                                <p>
                                    {{ $terms_and_conditions }}
                                </p>
                            </div>

                            <!-- Checkbox -->
                            <div class="mt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" x-model="agreed" wire:model="terms"
                                        class="form-checkbox text-green-600">
                                    <span class="ml-2 text-sm text-gray-700">I agree to the Terms and Conditions</span>
                                </label>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-2 mt-6">
                                <button @click="showModal = false"
                                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                    Cancel
                                </button>
                                <button :disabled="!agreed" @click="showModal = false; $wire.register()"
                                    class="px-4 py-2 rounded-md text-white transition duration-150 ease-in-out" :class="agreed 
                                    ? 'bg-green-600 hover:bg-green-700' 
                                    : 'bg-green-300 cursor-not-allowed'">
                                    Confirm & Complete Reservation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- End of Modal for showing the Terms and Conditions --}}



                @if ($errors->has('terms'))
                <span class="text-red-500 text-xs">{{ $errors->first('terms') }}</span>
                @endif

            </div>
            @endif
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


{{-- <input type="date" wire:model="check_in_date" wire:change="getAvailableRooms">
<input type="date" wire:model="check_out_date" wire:change="getAvailableRooms"> --}}
{{-- <button
    class="px-4 py-3 bg-green-700 bg-opacity-85 hover:bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
    Search Availability
</button> --}}


{{-- <div> --}}
    {{-- <label for="checkin">Check-in Date & Time:</label>
    <input type="datetime-local" id="checkin" wire:model.live="check_in_date">

    <label for="checkout">Check-out Date & Time:</label>
    <input type="datetime-local" id="checkout" wire:model.live="check_out_date"> --}}
    {{--
</div> --}}