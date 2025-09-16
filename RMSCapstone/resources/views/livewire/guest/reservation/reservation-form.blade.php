<div class="min-h-screen p-7">
    <div class="flex flex-col lg:flex-row mx-auto">


        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Left Side: Form Steps --}}
        <div class="w-full flex">
            <div class="w-full">

                <div class="header">
                    @if ($currentStep != 4)
                        <!-- Title -->
                        <h1 class="text-3xl font-bold text-green-700 text-center mb-4">Book Your Stay</h1>
                        <!-- Date Picker & Search -->
                        <div class="flex items-center justify-center md:gap-4 mb-8 ">
                            <div class="flex flex-col items-center">
                                <p class="mb-1">Check-In Date</p>
                                <input type="date" wire:model.live="check_in_date"
                                    min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}"
                                    class="border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                                    placeholder="Check-in">
                            </div>

                            <div class="p-2 mt-6">
                                <h1><i class="fas fa-arrow-right"></i></h1>
                            </div>

                            <div class="flex flex-col items-center">
                                <p class="mb-1">Check-Out Date</p>
                                <input type="date" wire:model.live="check_out_date"
                                    min="{{ isset($check_in_date) ? \Carbon\Carbon::parse($check_in_date)->addDay()->format('Y-m-d') : \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d') }}"
                                    class="border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500"
                                    placeholder="Check-out">
                            </div>
                        </div>
                    @endif
                </div>

                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <!-- Choose a Room -->
                @if ($currentStep == 1)
                    <div class="step-room md:px-12">
                        @include('livewire.guest.reservation.step-header', ['currentStep' => $currentStep])
                        {{-- @include('livewire.guest.reservation.guest-detail') --}}
                        @include('livewire.guest.reservation.choose-room')
                        {{-- @include('livewire.guest.reservation.review') --}}
                        {{-- @include('livewire.guest.reservation.choose-activity') --}}
                    </div>
                @endif

                <!-- Choose an Activity -->
                @if ($currentStep == 2)
                    <div class="step-activity md:px-12">
                        @include('livewire.guest.reservation.step-header', ['currentStep' => $currentStep])
                        @include('livewire.guest.reservation.choose-activity')
                    </div>
                @endif

                <!-- Enter Guest Details -->
                @if ($currentStep == 3)
                    <div class="step-guest-details w-full md:px-12">
                        @include('livewire.guest.reservation.step-header', ['currentStep' => $currentStep])
                        @include('livewire.guest.reservation.guest-detail')
                    </div>
                @endif

                <!-- Review reservation -->
                @if ($currentStep == 4)
                    <div class="step-review md:px-12">
                        @include('livewire.guest.reservation.step-header', ['currentStep' => $currentStep])
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

        {{-- TODO: Make Mobile View Version - DOing --}}


        @if ($currentStep != 4)
            <div class="hidden lg:block lg:w-1/3 bg-white border border-gray-200 rounded-lg shadow-md py-6 px-4 h-fit sticky top-0 z-10 mt-4">
                <!------------------------------ Reservation Date Details --------------------------->
                @php
                    $carbon = new \Carbon\Carbon();
                @endphp

                @if ($check_in_date)
                    <div
                        class="-mt-6 -mx-4 mb-4 bg-gray-100 text-green-700 text-center text-lg font-semibold p-2 rounded-t-lg shadow-sm border-b">
                        Reservation Summary
                    </div>

                    <div class="flex justify-center items-center text-md text-gray-800 space-x-2">
                        <span>
                            {{ $this->getFormattedCheckInDate() }}
                        </span>

                        @error('check_in_date')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror

                        <i class="fa-solid fa-arrow-right"></i>
                        @if ($check_out_date)
                            <span>
                                {{ $this->getFormattedCheckOutDate() }}
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
                        <div class="flex flex-col gap-2 py-2">
                            <!-- Selected Rooms -->
                            @if ($cartCollection->contains('type', 'room'))
                                @foreach ($cart as $item)
                                    @if ($item['type'] === 'room')
                                        <!-- Room Card -->
                                        <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
                                            wire:key="cart-item-{{ $item['room_id'] }}">
                                            <!-- Back Button -->
                                            <button type="button"
                                                wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['room_id'] }})"
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
                                                <div class="flex justify-between items-start gap-1">

                                                    <!-- Labels -->
                                                    <div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $item['roomRateName'] }} (x {{ $this->stayDuration }} Night/s):
                                                        </div>
                                                        @if ($item['extra_charge'])
                                                            <div class="text-sm text-gray-600">Extra Person Charge:
                                                            </div>
                                                        @endif
                                                        <div class="text-sm font-semibold text-gray-600">Subtotal:</div>
                                                    </div>

                                                    <!-- Amounts -->
                                                    <div class="text-right">
                                                        <div class="text-sm  text-gray-800">
                                                            ₱{{ number_format($item['roomAmount'], 2) }}
                                                        </div>
                                                        @if ($item['extra_charge'])
                                                            <div class="text-sm  text-gray-800">
                                                                ₱{{ number_format($item['extra_charge'], 2) }}
                                                            </div>
                                                        @endif
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
                                        <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
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
                                                <!-- Charges Breakdown -->
                                                <div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-gray-600">Unit Cost: (x {{ $item['quantity'] }})</span>
                                                        <span class="text-sm text-gray-600">₱{{ number_format($item['activity_rate'], 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-semibold text-gray-800">Subtotal:</span>
                                                        <span class="text-sm font-semibold text-gray-800">₱{{ number_format($item['amount'], 2) }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                            <!-- Selected Services -->
                            @if ($cartCollection->contains('type', 'service'))
                                @foreach ($cart as $item)
                                    @if ($item['type'] === 'service')
                                        <!-- Service Card -->
                                        <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
                                            wire:key="cart-item-{{ $item['service_id'] }}">
                                            <!-- back Button -->
                                            <button type="button"
                                                wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['service_id'] }})"
                                                class="text-gray-700 bg-gray-200 hover:bg-gray-300 hover:text-red-600 rounded-full w-6 h-6 flex items-center justify-center text-2xl absolute top-2 right-2 focus:outline-none"
                                                title="Remove Service">
                                                <span class="leading-none ">&times;</span>
                                            </button>
                                            <!-- Service Details -->
                                            <div class="text-gray-800 flex flex-col justify-between mt-1">
                                                <div class="text-md">
                                                    <i class="fa-solid fa-gift"></i>
                                                    <strong>Add-ons:</strong> {{ $item['service_name'] }}
                                                </div>
                                                <!-- Charges Breakdown -->
                                                <div class="flex justify-between items-start gap-2">
                                                    <!-- Label and Quantity -->
                                                    <div class="text-sm text-gray-600">
                                                        Quantity: {{ $item['quantity'] }}
                                                    </div>
                                                    <!-- Amount -->
                                                    <div class="text-sm font-semibold text-gray-800">
                                                        ₱{{ number_format($item['amount'], 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif


                            <!-- Pet Fee Amount -->
                            @if ($this->computePetTotal())
                                <div
                                    class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative">
                                    <!-- Activity Details -->
                                    <div class="text-gray-800 flex flex-col justify-between mt-1">
                                        <!-- Charges Breakdown -->
                                        <div class="flex justify-between items-start gap-2">
                                            <!-- Label and Quantity -->
                                            <div class="text-md">
                                                <i class="fa-solid fa-paw"></i>
                                                <strong>Pet fee:</strong>
                                            </div>
                                            <!-- Amount -->
                                            <div class="text-sm font-semibold text-gray-800">
                                                ₱{{ number_format($this->computePetTotal(), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <p>Total Guests: {{ $total_pax }}</p>
                        </div>
                    @else
                        <!-- Show when no room is selected -->
                        <div class="flex flex-col items-center justify-center text-gray-500 text-sm py-6">
                            <i class="fa-solid fa-bed text-3xl mb-2"></i>
                            <span>No rooms added yet</span>
                        </div>
                    @endif
                </div>

                @if(!empty($cartNotices))
                    <div class="mb-4">
                        @foreach($cartNotices as $notice)
                            <div class="bg-yellow-100 text-yellow-800 p-2 rounded mb-1 text-sm" role="alert">
                                {!! $notice !!}
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('cart')
                    <div id="toast-danger"
                        class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm  "
                        role="alert">
                        <div
                            class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg  ">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                            </svg>
                            <span class="sr-only">Error icon</span>
                        </div>
                        <div class="ms-3 text-sm font-normal">{{ $message }}</div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                            data-dismiss-target="#toast-danger" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                @enderror



                <!------------------------------ Price Breakdown ------------------------------------->
                @if ($cartCollection->contains('type', 'room'))
                    <div>

                        <!-- Discount Code -->
                        <hr class="my-2 border-gray-200">
                        @if ($discountMessage)
                            <p class="text-sm mt-1 text-green-600">{{ $discountMessage }}</p>
                        @endif

                        @if ($errorMessage)
                            {{-- <x-toast-message type="danger">
                                {{ $errorMessage }}
                            </x-toast-message> --}}
                            <p class="text-sm mt-1 text-red-500">{{ $errorMessage }}</p>
                        @endif

                        @php
                            $hasCode = !empty($promoCode) && empty($discountMessage) === false;
                        @endphp

                        <div class="relative w-full mt-4">
                            <input type="text" wire:model="promoCode"
                                wire:key="promo-code-{{ $hasCode ? 'applied' : 'empty' }}"
                                class="border rounded-md px-4 py-2 w-full pr-16 shadow-sm transition focus:outline-none focus:ring-1
                                {{ $hasCode ? 'border-green-500 ring-green-500 bg-green-50 text-green-800 font-semibold' : 'border-gray-300 focus:ring-green-500 focus:border-green-500' }}"
                                placeholder="Enter Promo Code" autocomplete="off" {{ $hasCode ? 'disabled' : '' }}
                                >


                            @if ($discountMessage)
                                <button wire:key="remove-promo-button" type="button" wire:click="removePromoCode"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-red-600 text-md font-medium focus:outline-none"
                                    title="Remove Promo Code">
                                    &times;
                                </button>
                            @else
                                <button wire:key="apply-promo-button" type="button" wire:click="applyPromoCode"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-green-600 text-sm font-medium hover:underline focus:outline-none">
                                    Apply
                                </button>
                            @endif
                        </div>

                        <!-- Subtotal -->
                        <div class="flex justify-between items-center text-sm text-gray-600 mt-3">
                            <div>Subtotal</div>
                            <div class="font-semibold flex flex-col items-end">
                                @if($discountMessage)
                                    <!-- Original subtotal with strikethrough -->
                                    <span class="line-through text-gray-400">
                                        ₱{{ number_format($this->computeBaseSubtotal(), 2) }}
                                    </span>
                                    <!-- Subtotal after discount -->
                                    <span class="text-green-700 font-semibold">
                                        ₱{{ number_format($this->computeSubtotalAfterDiscount(), 2) }}
                                    </span>
                                @else
                                    <!-- No discount applied -->
                                    <span>
                                        ₱{{ number_format($this->computeSubtotalAmount(), 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Final Total (after discount) -->
                        <div class="flex justify-between items-center text-lg font-semibold text-gray-800 mt-2">
                            <div>Total</div>
                            <div>₱{{ number_format($this->computeSubtotalAfterDiscount(), 2) }}</div>
                        </div>

                        <!-- Deposit (if enabled) -->
                        @if ($this->enable_deposit_percentage)
                            <div class="flex justify-between items-center text-sm text-yellow-700 mt-2">
                                <div>Required Deposit ({{ $this->deposit_percentage }}%)</div>
                                <div class="font-semibold">
                                    ₱{{ number_format($this->deposit ?? 0, 2) }}
                                </div>
                            </div>
                        @endif

                        <!-- Convenience Fee -->
                        <div class="flex justify-between items-center text-sm text-gray-600 mt-2">
                            <div class="flex items-center gap-2">
                                <span>Convenience Fee (3%)</span>
                                <div class="relative group inline-block">
                                    <i class="fas fa-info-circle text-gray-500 text-xs cursor-pointer"></i>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-xs text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                        A processing fee is applied for secure online payments.
                                    </div>
                                </div>
                            </div>
                            <div class="font-semibold">
                                ₱{{ number_format($this->computeConvenienceFee(), 2) }}
                            </div>
                        </div>

                        <hr class="my-3 border-gray-300">

                        <!-- Total Payable Now -->
                        <div class="flex justify-between items-center text-2xl font-bold text-green-700 mt-2">
                            <div>Total Payable Now</div>
                            <div>₱{{ number_format($this->computePayableAmount(), 2) }}</div>
                        </div>


                     

                       

                    </div>
                @endif

                <!-- Navigation Buttons-->
                @if ($cartCollection->contains('type', 'room'))
                    <div class="mt-6 flex justify-between">

                        {{-- @if ($currentStep == 1)
                        <div> </div>
                        @endif --}}

                        <!-- Back button -->
                        @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                            <button type="button"
                                class="mt-4 block px-4 py-2 h-10 w-16 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150"
                                wire:loading.attr="disabled" wire:click="decreaseStep()">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading wire:target="decreaseStep()" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="decreaseStep()">
                                        Back
                                    </span>
                                </div>
                            </button>
                        @endif

                        <!-- Next button -->
                        @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3)
                            <x-button type="button" class="mt-4 block px-4 py-2 h-10 w-16"
                                wire:loading.attr="disabled" wire:click="increaseStep()">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading wire:target="increaseStep()" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="increaseStep()">
                                        Next
                                    </span>
                                </div>
                            </x-button>
                        @endif

                        <!-- Terms and Conditions Modal -->
                        @if ($currentStep == 4)
                            <div x-data="{ showModal: false, agreed: false }" x-init="$watch('showModal', value => document.body.classList.toggle('overflow-hidden', value))"
                                @keydown.escape.window="showModal = false">

                                <button type="button"
                                    class="mt-4 block px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                    @click="showModal = true">
                                    Confirm
                                </button>


                                <!-- Modal -->
                                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                                    x-show="showModal" x-transition style="display: none;">
                                    <div
                                        class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-xl font-bold mb-4 text-green-800">Terms and Conditions</h2>

                                        <div class="text-sm text-gray-800 space-y-3 text-justify">
                                            <p>
                                                By completing this reservation, you agree to abide by all property rules
                                                and
                                                regulations. Any damages incurred during your stay will be your
                                                responsibility and charged accordingly. Detailed payment information,
                                                cancellation policies, and other important terms will be provided upon
                                                confirmation. We look forward to hosting you and are committed to
                                                ensuring
                                                you have a pleasant and enjoyable stay.
                                            </p>
                                            <p>
                                                We reserve the right to deny entry to anyone violating these terms. For
                                                any
                                                questions or clarifications, please contact our support team <span
                                                    class="text-green-700">canopyfarm@gmail.com</span>
                                            </p>
                                        </div>

                                        <!-- Checkbox -->
                                        <div class="mt-4">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" x-model="agreed" wire:model="terms"
                                                    class="form-checkbox text-green-600">
                                                <span class="ml-2 text-sm text-gray-700">I agree to the Terms and
                                                    Conditions</span>
                                            </label>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex justify-between gap-2 mt-6">
                                            <button @click="showModal = false"
                                                class="mt-4 block px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150">
                                                Cancel
                                            </button>
                                            <button wire:loading.attr="disabled" type="button" :disabled="!agreed"
                                                @click="if (agreed) { $wire.register(); }"
                                                class="mt-4 block px-4 py-2  border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                                :class="agreed
                                                    ?
                                                    'bg-green-700 bg-opacity-85 hover:bg-green-700 cursor-pointer' :
                                                    'bg-green-400 cursor-not-allowed'">
                                                <div class="flex items-center justify-center">
                                                    <!-- Spinner -->
                                                    <span wire:loading wire:target="register" class="mr-2">
                                                        <svg class="animate-spin h-5 w-5 text-white"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                            </path>
                                                        </svg>
                                                    </span>

                                                    <!-- Button Text -->
                                                    <span wire:loading.remove wire:target="register">
                                                        Complete Reservation
                                                    </span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- End of Modal for showing the Terms and Conditions --}}

                        @if ($errors->has('terms'))
                            <span class="text-red-500 text-xs">{{ $errors->first('terms') }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <!------------------------------- Summary Tab Mobile View ---------------------------->
            @if ($cartCollection->isNotEmpty())
                <div x-data="{ expanded: false }"
                    class="lg:hidden fixed -bottom-1 left-0 right-0 bg-white shadow-lg border-t z-50">

                    <!-- Collapsed Bar -->
                    <div class="flex justify-between items-center p-4 cursor-pointer" @click="expanded = !expanded">
                        <div>
                            <div class="flex items-center justify-center space-x-3">
                                <p class="text-sm font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($this->getFormattedCheckInDate())->format('M d, Y') }}
                                </p>
                                <i class="fa-solid fa-arrow-right"></i>
                                @if ($check_out_date)
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($this->getFormattedCheckOutDate())->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-lg font-bold text-green-700">
                                ₱{{ number_format($this->computeTotalAmount(), 2) }}
                            </p>
                        </div>

                        <!-- Expand Button -->
                        <button class="text-gray-600">
                            <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Expandable Details -->
                    <div x-show="expanded" x-transition class="max-h-[70vh] overflow-y-auto border-t">
                        <div class="p-4 space-y-1">
                            <p
                                class="text-lg text-green-700 font-bold uppercase tracking-wide justify-center flex items-center">
                                Reservation Summary</p>
                            <div>
                                <p>Stay Duration: {{ $this->stayDuration }} night(s)</p>
                                <p>Total Guests: {{ $total_pax }}</p>
                            </div>

                            <div class="flex flex-col gap-2 mb-2 py-2">
                                <!-- Selected Rooms -->
                                @if ($cartCollection->contains('type', 'room'))
                                    @foreach ($cart as $item)
                                        @if ($item['type'] === 'room')
                                            <!-- Room Card -->
                                            <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
                                                wire:key="cart-item-{{ $item['room_id'] }}">
                                                <!-- Back Button -->
                                                <button type="button"
                                                    wire:click="removeFromCart('{{ $item['type'] }}', {{ $item['room_id'] }})"
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
                                                    <div class="flex justify-between items-start gap-1">

                                                        <!-- Labels -->
                                                        <div>
                                                            @if ($item['extra_charge'])
                                                                <div class="text-sm text-gray-600">Extra Person Charge:
                                                                </div>
                                                            @endif
                                                            <div class="text-sm text-gray-600">
                                                                {{ $item['roomRateName'] }} (x
                                                                {{ $this->stayDuration }} Night/s):
                                                            </div>
                                                        </div>

                                                        <!-- Amounts -->
                                                        <div class="text-right">
                                                            @if ($item['extra_charge'])
                                                                <div class="text-sm  text-gray-800">
                                                                    ₱{{ number_format($item['extra_charge'], 2) }}
                                                                </div>
                                                            @endif
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
                                            <div class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative"
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
                                                    <!-- Charges Breakdown -->
                                                    <div class="flex justify-between items-start gap-2">
                                                        <!-- Label and Quantity -->
                                                        <div class="text-sm text-gray-600">
                                                            Quantity: {{ $item['quantity'] }}
                                                        </div>
                                                        <!-- Amount -->
                                                        <div class="text-sm font-semibold text-gray-800">
                                                            ₱{{ number_format($item['amount'], 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                            <!-- Pet Fee Amount -->
                            @if ($this->computePetTotal())
                                <div
                                    class="bg-gray-100 py-3 px-2 rounded-xl shadow-sm border border-gray-200 flex-1 relative">
                                    <!-- Activity Details -->
                                    <div class="text-gray-800 flex flex-col justify-between mt-1">
                                        <!-- Charges Breakdown -->
                                        <div class="flex justify-between items-start gap-2">
                                            <!-- Label and Quantity -->
                                            <div class="text-md">
                                                <i class="fa-solid fa-paw"></i>
                                                <strong>Pet fee:</strong>
                                            </div>
                                            <!-- Amount -->
                                            <div class="text-sm font-semibold text-gray-800">
                                                ₱{{ number_format($this->computePetTotal(), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                               
                            </div>
                            @if ($cartCollection->contains('type', 'room'))
                                <div>
                                    <!-- Discount Code -->
                                    <hr class="my-1 border-gray-200">
                                    @if ($discountMessage)
                                        <p class="text-sm mt-1 text-green-600">{{ $discountMessage }}</p>
                                    @endif

                                    @if ($errorMessage)
                                        {{-- <x-toast-message type="danger">
                                {{ $errorMessage }}
                            </x-toast-message> --}}
                                        <p class="text-sm mt-1 text-red-500">{{ $errorMessage }}</p>
                                    @endif

                                    @php
                                        $hasCode = !empty($promoCode) && empty($discountMessage) === false;
                                    @endphp

                                    <div class="relative w-full mt-4">
                                        <input type="text" wire:model="promoCode"
                                            wire:key="promo-code-{{ $hasCode ? 'applied' : 'empty' }}"
                                            class="border rounded-md px-4 py-2 w-full pr-16 shadow-sm transition focus:outline-none focus:ring-1
                                {{ $hasCode ? 'border-green-500 ring-green-500 bg-green-50 text-green-800 font-semibold' : 'border-gray-300 focus:ring-green-500 focus:border-green-500' }}"
                                            placeholder="Enter Promo Code" autocomplete="off"
                                            {{ $hasCode ? 'disabled' : '' }}>

                                        @if ($discountMessage)
                                            <button wire:key="remove-promo-button" type="button"
                                                wire:click="removePromoCode"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-red-600 text-md font-medium focus:outline-none"
                                                title="Remove Promo Code">
                                                &times;
                                            </button>
                                        @else
                                            <button wire:key="apply-promo-button" type="button"
                                                wire:click="applyPromoCode"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-green-600 text-sm font-medium hover:underline focus:outline-none">
                                                Apply
                                            </button>
                                        @endif
                                    </div>

                                           <!-- Subtotal -->
                                            <div class="flex justify-between items-center text-sm text-gray-600 mt-3">
                                                <div>Subtotal</div>
                                                <div class="font-semibold flex flex-col items-end">
                                                    @if($discountMessage)
                                                        <!-- Original subtotal with strikethrough -->
                                                        <span class="line-through text-gray-400">
                                                            ₱{{ number_format($this->computeBaseSubtotal(), 2) }}
                                                        </span>
                                                        <!-- Subtotal after discount -->
                                                        <span class="text-green-700 font-semibold">
                                                            ₱{{ number_format($this->computeSubtotalAfterDiscount(), 2) }}
                                                        </span>
                                                    @else
                                                        <!-- No discount applied -->
                                                        <span>
                                                            ₱{{ number_format($this->computeSubtotalAmount(), 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Final Total (after discount) -->
                                            <div class="flex justify-between items-center text-lg font-semibold text-gray-800 mt-2">
                                                <div>Total</div>
                                                <div>₱{{ number_format($this->computeSubtotalAfterDiscount(), 2) }}</div>
                                            </div>

                                            <!-- Deposit (if enabled) -->
                                            @if ($this->enable_deposit_percentage)
                                                <div class="flex justify-between items-center text-sm text-yellow-700 mt-2">
                                                    <div>Required Deposit ({{ $this->deposit_percentage }}%)</div>
                                                    <div class="font-semibold">
                                                        ₱{{ number_format($this->deposit ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Convenience Fee -->
                                            <div class="flex justify-between items-center text-sm text-gray-600 mt-2">
                                                <div class="flex items-center gap-2">
                                                    <span>Convenience Fee (3%)</span>
                                                    <div class="relative group inline-block">
                                                        <i class="fas fa-info-circle text-gray-500 text-xs cursor-pointer"></i>
                                                        <!-- Tooltip -->
                                                        <div
                                                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-xs text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                                            A processing fee is applied for secure online payments.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="font-semibold">
                                                    ₱{{ number_format($this->computeConvenienceFee(), 2) }}
                                                </div>
                                            </div>

                                            <hr class="my-3 border-gray-300">

                                            <!-- Total Payable Now -->
                                            <div class="flex justify-between items-center text-2xl font-bold text-green-700 mt-2">
                                                <div>Total Payable Now</div>
                                                <div>₱{{ number_format($this->computePayableAmount(), 2) }}</div>
                                            </div>


                                </div>
                            @endif

                            <!-- Navigation/Checkout Button -->
                            <div class="mt-2 flex justify-between">
                                <!-- Back button -->
                                @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                                    <button type="button"
                                        class="mt-4 block px-4 py-2 h-10 w-16 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150"
                                        wire:loading.attr="disabled" wire:click="decreaseStep()">
                                        <div class="flex items-center justify-center">
                                            <!-- Spinner -->
                                            <span wire:loading wire:target="decreaseStep()" class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <!-- Button Text -->
                                            <span wire:loading.remove wire:target="decreaseStep()">
                                                Back
                                            </span>
                                        </div>
                                    </button>
                                @endif

                                <!-- Next button -->
                                @if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3)
                                    <x-button type="button" class="mt-4 block px-4 py-2 h-10 w-16"
                                        wire:loading.attr="disabled" wire:click="increaseStep()">
                                        <div class="flex items-center justify-center">
                                            <!-- Spinner -->
                                            <span wire:loading wire:target="increaseStep()" class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <!-- Button Text -->
                                            <span wire:loading.remove wire:target="increaseStep()">
                                                Next
                                            </span>
                                        </div>
                                    </x-button>
                                @endif

                                <!-- Terms and Conditions Modal -->
                                @if ($currentStep == 4)
                                    <div x-data="{ showModal: false, agreed: false }" x-init="$watch('showModal', value => document.body.classList.toggle('overflow-hidden', value))"
                                        @keydown.escape.window="showModal = false">

                                        <button type="button"
                                            class="mt-4 block px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                            @click="showModal = true">
                                            Confirm
                                        </button>


                                        <!-- Modal -->
                                        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                                            x-show="showModal" x-transition style="display: none;">
                                            <div
                                                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                                                <h2 class="text-xl font-bold mb-4 text-green-800">Terms and Conditions
                                                </h2>

                                                <div class="text-sm text-gray-800 space-y-3 text-justify">
                                                    <p>
                                                        By completing this reservation, you agree to abide by all
                                                        property rules
                                                        and
                                                        regulations. Any damages incurred during your stay will be your
                                                        responsibility and charged accordingly. Detailed payment
                                                        information,
                                                        cancellation policies, and other important terms will be
                                                        provided upon
                                                        confirmation. We look forward to hosting you and are committed
                                                        to
                                                        ensuring
                                                        you have a pleasant and enjoyable stay.
                                                    </p>
                                                    <p>
                                                        We reserve the right to deny entry to anyone violating these
                                                        terms. For
                                                        any
                                                        questions or clarifications, please contact our support team
                                                        <span class="text-green-700">canopyfarm@gmail.com</span>
                                                    </p>
                                                </div>

                                                <!-- Checkbox -->
                                                <div class="mt-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" x-model="agreed" wire:model="terms"
                                                            class="form-checkbox text-green-600">
                                                        <span class="ml-2 text-sm text-gray-700">I agree to the Terms
                                                            and
                                                            Conditions</span>
                                                    </label>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex justify-between gap-2 mt-6">
                                                    <button @click="showModal = false"
                                                        class="mt-4 block px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150">
                                                        Cancel
                                                    </button>
                                                    <button wire:loading.attr="disabled" type="button"
                                                        :disabled="!agreed"
                                                        @click="if (agreed) { $wire.register(); }"
                                                        class="mt-4 block px-4 py-2  border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                                        :class="agreed
                                                            ?
                                                            'bg-green-700 bg-opacity-85 hover:bg-green-700 cursor-pointer' :
                                                            'bg-green-400 cursor-not-allowed'">
                                                        <div class="flex items-center justify-center">
                                                            <!-- Spinner -->
                                                            <span wire:loading wire:target="register" class="mr-2">
                                                                <svg class="animate-spin h-5 w-5 text-white"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4">
                                                                    </circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                                    </path>
                                                                </svg>
                                                            </span>

                                                            <!-- Button Text -->
                                                            <span wire:loading.remove wire:target="register">
                                                                Complete Reservation
                                                            </span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- End of Modal for showing the Terms and Conditions --}}

                                @if ($errors->has('terms'))
                                    <span class="text-red-500 text-xs">{{ $errors->first('terms') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endif
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
