<div class="flex items-center justify-center min-h-screen p-1">
    <div class="w-full max-w-5xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-green-800 text-white text-xl md:text-2xl font-bold px-6 py-5 text-center">
                Review Your Reservation Details
            </div>

            <div class="space-y-6 p-3">

                <!-- Stay Details -->
                <div class="rounded-lg bg-gray-50 p-4 border border-gray-200 mt-4 max-w-md mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-center space-x-3 mb-4 text-green-700">
                        <i class="fa-solid fa-calendar"></i>
                        <h2 class="text-xl font-bold">Stay Details</h2>
                    </div>

                    <div class="flex items-center justify-center text-center gap-6 md:gap-10">
                        <!-- Check-in -->
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Check-in</div>
                            {{-- <div class="text-base text-gray-900 font-medium">September 9, 2025</div> --}}
                            <div class="text-base text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($check_in_date)->format('M d, Y') }}
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="flex items-center justify-center">
                            <i class="fas fa-arrow-right text-green-700 text-lg sm:text-xl md:text-2xl"></i>
                        </div>

                        <!-- Check-out -->
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Check-out</div>
                            {{-- <div class="text-base text-gray-900 font-medium">September 10, 2025</div> --}}
                            <div class="text-base text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($check_out_date)->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="flex items-center justify-center mt-4">
                        <div class="text-center">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Stay Duration</div>
                            <div class="text-gray-900 font-medium">{{ $this->getStayDurationProperty() }} Night(s)</div>
                        </div>
                    </div>
                </div>

                <!-- Guest Details -->
                <div
                    class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out">
                    <h2 class="text-green-700 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-user"></i>
                        Guest Details
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-bold">John Doe (+63) 0987654321</p>
                                    {{-- <p class="font-bold">{{ $first_name }} {{ $middle_name }} {{ $last_name }} {{
                                        $contact_number }}</p> --}}
                                </div>
                                <div class="mt-2 sm:mt-0 flex gap-2">
                                    <span class="border border-gray-500 text-gray-700 text-xs px-2 py-0.5 rounded">
                                        Primary Guest
                                    </span>
                                </div>
                            </div>

                            {{-- <p class="text-sm text-gray-600">
                                Philippines
                            </p> --}}
                            <p class="text-sm text-gray-600">
                                {{ $country }}
                            </p>
                            <p class="text-sm text-gray-600">
                                arasdump@gmail.com
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $email }}
                            </p>
                            <hr class="my-3">
                            {{-- <div class="mt-3 flex gap-6 text-sm text-gray-700">
                                <div>
                                    <span class="font-semibold">{{ $item['extra_guest'] }} Extra Guests</span>
                                </div>
                                <div>
                                    <span class="font-semibold">{{ $item['kids'] + $item['adults'] }} Total Pax</span>
                                </div>
                            </div> --}}


                            @if ($company_name)
                                <p class="text-sm text-gray-600">
                                    Company: {{ $company_name }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Additional Guest Details -->
                @if (count($guests) > 0)
                    <div
                        class="border rounded-md bg-white p-4 mt-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out">
                        <h2 class="text-green-700 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-users"></i>
                            Additional Guest Details
                        </h2>
                        @foreach ($guests as $guest)
                            <div class="mt-1 space-y-1 text-sm text-gray-700 border-b pb-3 mb-3">
                                <p><span class="font-medium text-gray-900">Name:</span>
                                    {{ $guest['guest_first_name'] }}
                                    {{ $guest['guest_middle_name'] ?? '' }}
                                    {{ $guest['guest_last_name'] }}
                                    {{ $guest['guest_suffix'] ?? '' }}
                                </p>
                                <p><span class="font-medium text-gray-900">Gender:</span>
                                    {{ ucfirst($guest['guest_gender']) }}</p>
                                <p><span class="font-medium text-gray-900">Residency:</span>
                                    {{ ucfirst($guest['guest_residency']) }}</p>
                                <p><span class="font-medium text-gray-900">Country of Origin:</span>
                                    {{ $guest['guest_country_of_origin'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Service details -->
                @if (collect($cart)->contains('type', 'service'))
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Services</h3>
                        @foreach ($cart as $item)
                            @if ($item['type'] === 'service')
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-4">
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Service</p>
                                            {{-- <p class="font-semibold text-gray-800">Nature Walk</p> --}}
                                            <p class="font-medium">{{ $item['service_name'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Quantity</p>
                                            {{-- <p class="font-semibold text-gray-800">2</p> --}}
                                            <p>{{ $item['quantity'] }}</p>
                                        </div>
                                        <div class="md:col-span-2 pt-4 border-t border-gray-200 mt-4">
                                            <p class="text-gray-600 text-sm mb-1">Total Service Charge</p>
                                            {{-- <p class="font-bold text-lg text-green-700">₱500</p> --}}
                                            <p class="font-semibold">₱{{ number_format($item['amount'], 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif


                <!-- Pet Details -->
                @if (!empty($pets))
                    <div
                        class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out mt-4">
                        <h2 class="text-green-700 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-paw"></i>
                            Pet Details
                        </h2>
                        @foreach ($pets as $index => $pet)
                            <div class="mt-1 space-y-1 text-sm text-gray-700 border-b pb-3 mb-3">
                                <p><span class="font-medium text-gray-900">Breed:</span> {{ $pet['breed'] }}</p>
                                <p><span class="font-medium text-gray-900">Quantity:</span> 1</p>
                            </div>
                        @endforeach

                        @php
                            $totalPetFee = number_format($this->computePetTotal(), 2);
                        @endphp

                        <div class="flex justify-between">
                            <p class="font-semibold">Pet Fee Subtotal:</p>
                            <p class="font-semibold">₱{{ $totalPetFee }}</p>
                        </div>
                    </div>
                @endif

                <!-- Rooms Section -->
                <div
                    class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out">
                    <h3 class="text-green-700 font-semibold flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-bed"></i>
                        Room Details
                    </h3>

                    <!-- Room Item -->
                    @foreach ($cart as $item)
                        @if ($item['type'] === 'room')
                            <div class="flex gap-4 items-start border-b pb-3 mb-3">
                                <img src="{{ asset('images/rms-default.png') }}" alt="Room Image"
                                    class="w-20 h-20 object-cover rounded-md">
                                <div class="flex-1">
                                    {{-- <p class="text-sm font-medium text-gray-800">Solah</p>
                                    <p class="text-xs text-gray-500">2 Extra Guest(s)</p>
                                    <p class="text-xs text-gray-500">3 Night(s)</p> --}}
                                    <p class="text-sm font-medium text-gray-800">{{ $item['room_name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['extra_guest'] }} Extra Guest(s)</p>
                                    <p class="text-xs text-gray-500">{{ $item['days'] }} Night(s)</p>
                                </div>
                                <div class="text-right text-sm">
                                    {{-- <p class="text-gray-700">₱2,400</p>
                                    <p class="text-gray-500">₱1,000</p>
                                    <p class="text-gray-500">x3</p> --}}
                                    <p class="text-gray-700">₱{{ number_format($item['roomAmount'], 2) }}</p>
                                    <p class="text-gray-500">₱{{ number_format($item['extra_charge'], 2) }}</p>
                                    <p class="text-gray-500">x{{ $item['days'] }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <p class="font-semibold">Room Subtotal:</p>
                                {{-- <p class="font-semibold">₱10,000</p> --}}
                                <p class="font-semibold">₱{{ number_format($item['total_amount'], 2) }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Activities Section -->
                @if (collect($cart)->contains('type', 'activity'))
                    <div
                        class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out">
                        <h3 class="text-green-700 font-semibold flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-person-swimming"></i>
                            Activity Details
                        </h3>

                        <!-- Activity Item -->
                        @foreach ($cart as $item)
                            @if ($item['type'] === 'activity')
                                <div class="flex gap-4 items-start border-b pb-3 mb-3">
                                    <img src="{{ asset('images/rms-default.png') }}" alt="Activity Image"
                                        class="w-20 h-20 object-cover rounded-md">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">{{ $item['activity_name'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if (!empty($item['activity_datetime']))
                                                {{ \Carbon\Carbon::parse($item['activity_datetime'])->format('g:i A') }}
                                            @else
                                                No schedule
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right text-sm">
                                        <p class="text-gray-700">₱{{ number_format($item['activity_rate'], 2) }}</p>
                                        <p class="text-gray-500">x{{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <p class="font-semibold">Activity Subtotal:</p>
                                    <p class="font-semibold">₱{{ number_format($item['amount'], 2) }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($requests))
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 border-b pb-2">Special Requests</h3>
                        <div class="text-gray-700">
                            {{ $requests }}
                        </div>
                    </div>
                @endif




                <!-- Discount Notice -->
                <div class="md:px-12">
                    <div
                        class="bg-yellow-100 border border-yellow-500 border-l-4 rounded-md p-4 mb-6 text-gray-800 text-sm leading-relaxed text-justify">
                        <strong>Note:</strong> Senior citizens and PWD guests are entitled to a
                        <strong>20% discount</strong> on their share of the base price.
                        This discount will be applied upon check-in
                        <strong>with presentation of a valid Senior Citizen or PWD ID</strong>.
                        Please ensure to bring the required ID to avail of the discount.
                    </div>
                </div>

                <!-- Total Summary -->
                <div
                    class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-300 ease-in-out">
                    <h3 class="text-xl font-semibold mb-3 text-green-700">Summary</h3>
                    {{-- <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Room Fees</span>
                        <span class="text-gray-800">₱2,000</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Activity Fees</span>
                        <span class="text-gray-800">₱1,300</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Pet Fee</span>
                        <span class="text-gray-800">₱1,300</span>
                    </div> --}}


                    <!-- Subtotal Amount -->
                    <div class="flex flex-col text-sm text-gray-600 mt-3 space-y-1">
                        <!-- Original Subtotal -->
                        @if($discountMessage)
                            <div class="flex justify-between w-full">
                                <span>Subtotal</span>
                                <span
                                    class="line-through text-gray-400">₱{{ number_format($this->computeBaseSubtotal(), 2) }}</span>
                            </div>

                            <!-- Promo Code Discount -->
                            @if($this->promoCode && ($promo_discount_amount ?? 0) > 0)
                                <div class="flex justify-between w-full text-green-600">
                                    <span>Promo Code Discount ({{ $this->promoCode }})</span>
                                    <span>-₱{{ number_format($promo_discount_amount, 2) }}</span>
                                </div>
                            @endif

                            <!-- Subtotal After Discount -->
                            <div class="flex justify-between w-full font-semibold text-green-700">
                                <span>Subtotal after Discount</span>
                                <span>₱{{ number_format($this->computeSubtotalAfterDiscount(), 2) }}</span>
                            </div>
                        @else
                            <!-- No Discount Applied -->
                            <div class="flex justify-between w-full font-semibold">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($this->computeSubtotalAmount(), 2) }}</span>
                            </div>
                        @endif
                    </div>



                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Payment Processing Fee</span>
                        <span class="text-gray-800">₱{{ number_format($this->computeConvenienceFee(), 2) }}</span>
                    </div>



                    <hr class="my-3">
                    <div class="flex justify-between text-xl font-bold text-green-700">
                        <span>Grand Total</span>
                        <span>₱{{ number_format($this->computeTotalAmount(), 2) }}</span>
                    </div>
                    @if ($enable_deposit_percentage && $this->deposit > 0)
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Required Deposit</span>
                            <span class="text-gray-800">₱{{ number_format($this->deposit ?? 0, 2) }}</span>
                        </div>
                    @endif

                    <div class="text-center justify-between flex pt-4">
                        <!-- Back button -->
                        @if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4)
                            <x-ghost-button type="button" wire:loading.attr="disabled" wire:click="decreaseStep()">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading wire:target="decreaseStep()" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
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
                            </x-ghost-button>
                        @endif
                        {{-- <x-button
                            class="bg-green-600 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-lg text-lg transition duration-300 ease-in-out">
                            Proceed to Payment
                        </x-button> --}}
                        <div x-data="{ showModal: false, agreed: false }"
                            x-init="$watch('showModal', value => document.body.classList.toggle('overflow-hidden', value))"
                            @keydown.escape.window="showModal = false">

                            <x-button type="button" icon="fas fa-check-circle" @click="showModal = true">
                                Proceed to Payment
                            </x-button>


                            <!-- Modal -->
                            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
                                x-show="showModal" x-transition style="display: none;">
                                <div
                                    class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                                    <h2 class="text-xl font-bold mb-4 text-green-800">Terms and Conditions</h2>

                                    {{-- <div class="text-sm text-gray-800 space-y-3 text-justify">
                                        <p>
                                            By completing this reservation, you agree to abide by all property rules and
                                            regulations. Any damages incurred during your stay will be your
                                            responsibility and charged accordingly. Detailed payment information,
                                            cancellation policies, and other important terms will be provided upon
                                            confirmation. We look forward to hosting you and are committed to ensuring
                                            you have a pleasant and enjoyable stay.
                                        </p>

                                        <p>
                                            We reserve the right to deny entry to anyone violating these terms. For any
                                            questions or clarifications, please contact our support team <span
                                                class="text-green-700">canopyfarm@gmail.com</span>
                                        </p>
                                    </div> --}}

                                    <div class="prose max-w-none">
                                        {!! $terms_and_conditions !!}
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
                                                'bg-gray-400 cursor-not-allowed'">
                                            <div class="flex items-center justify-center">
                                                <!-- Spinner -->
                                                <span wire:loading wire:target="register" class="mr-2">
                                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
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
                    </div>
                </div>
                <div>
                    {{-- space --}}
                </div>
            </div>
        </div>
    </div>
</div>