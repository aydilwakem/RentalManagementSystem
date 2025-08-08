<div class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-3xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-green-800 text-white text-2xl font-bold px-6 py-5 text-center">
                Review Your Reservation Details
            </div>

            <div class="p-8 space-y-8 text-gray-700">
                <!-- Booking dates -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Stay Details</h3>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div
                            class="flex flex-col items-center justify-center p-4 bg-white rounded-lg border border-green-200">
                            <p class="text-gray-600 text-sm mb-1">Check-in</p>
                            {{-- <p class="text-lg font-bold text-green-700">Jun 07, 2025</p> --}}
                            <p class="font-medium">{{ \Carbon\Carbon::parse($check_in_date)->format('M d, Y') }}</p>
                        </div>

                        <div class="flex items-center justify-center">
                            <i class="fas fa-arrow-right text-green-700 text-2xl"></i>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center p-4 bg-white rounded-lg border border-green-200">
                            <p class="text-gray-600 text-sm mb-1">Check-out</p>
                            {{-- <p class="text-lg font-bold text-green-700">Jun 07, 2025</p> --}}
                            <p class="font-medium">{{ \Carbon\Carbon::parse($check_out_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-center mt-6 p-4 bg-gray-50 rounded-lg shadow-sm">
                        <p class="text-gray-600 text-sm mb-1">Stay Duration</p>
                        {{-- <p class="font-bold text-lg text-green-700">2 night(s)</p> --}}
                        <p class="font-medium">{{ $this->getStayDurationProperty() }}</p>
                    </div>
                </div>

                <!-- Guest details -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Guest Information</h3>
                    <div class="grid md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Name</p>
                            {{-- <p class="font-semibold text-gray-800">Juan Dela Cruz</p> --}}
                            <p class="font-medium">{{ $first_name }} {{ $middle_name }} {{ $last_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Email</p>
                            {{-- <p class="font-semibold text-gray-800">juan.delacruz@example.com</p> --}}
                            <p class="font-medium">{{ $email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Contact Number</p>
                            {{-- <p class="font-semibold text-gray-800">0987654321</p> --}}
                            <p class="font-medium">{{ $contact_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Country</p>
                            {{-- <p class="font-semibold text-gray-800">Philippines</p> --}}
                            <p class="font-medium">{{ $country }}</p>
                        </div>
                        @if ($company_name)
                            <div class="md:col-span-2">
                                <p class="text-gray-600 text-sm mb-1">Company</p>
                                {{-- <p class="font-semibold text-gray-800">Larabelles</p> --}}
                                <p class="font-medium">{{ $company_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional guest -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Additional Guest Information</h3>
                    <div class="mt-6">
                        @if (count($guests) > 0)
                            <div class="space-y-4">
                                @foreach ($guests as $guest)
                                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                        <div class="grid md:grid-cols-2 gap-6">
                                            <div>
                                                <p class="text-gray-600 text-sm mb-1">Name</p>
                                                <p class="font-medium">
                                                    {{ $guest['guest_first_name'] }}
                                                    {{ $guest['guest_middle_name'] ?? '' }}
                                                    {{ $guest['guest_last_name'] }}
                                                    {{ $guest['guest_suffix'] ?? '' }}
                                                </p>
                                            </div>
                                            {{-- <div>
                                                <p class="text-gray-600 text-sm mb-1">Guest Type</p>
                                                <p class="font-medium">{{ ucfirst($guest['guest_type_id']) }}</p>
                                            </div> --}}
                                            <div>
                                                <p class="text-gray-600 text-sm mb-1">Gender</p>
                                                <p class="font-medium">{{ ucfirst($guest['guest_gender']) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-sm mb-1">Residency</p>
                                                <p class="font-medium">{{ ucfirst($guest['guest_residency']) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-sm mb-1">Country of Origin</p>
                                                <p class="font-medium">{{ $guest['guest_country_of_origin'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-6 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">No additional guests added.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Room details -->
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Room Details</h3>
                    @foreach ($cart as $item)
                        @if ($item['type'] === 'room')
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-4">
                                {{-- <p class="font-bold text-xl text-green-700 mb-4">Solah</p> --}}
                                <p class="font-semibold text-green-700">{{ $item['room_name'] }}</p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Length of Stay</p>
                                        {{-- <p class="font-medium text-gray-800">2 Night(s)</p> --}}
                                        <p>{{ $item['days'] }} Night(s)</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Room Rate</p>
                                        {{-- <p class="font-medium text-gray-800">₱8,500</p> --}}
                                        <p>₱{{ number_format($item['roomAmount'], 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Guests</p>
                                        {{-- <p class="font-medium text-gray-800">2 Adults, 2 Children</p> --}}
                                        <p>{{ $item['adults'] }} Adults, {{ $item['kids'] }} Children</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Extra Guests</p>
                                        {{-- <p class="font-medium text-gray-800">2 (₱1,800)</p> --}}
                                        <p>{{ $item['extra_guest'] }} (₱{{ number_format($item['extra_charge'], 2) }})
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Pet Fee</p>
                                        {{-- <p class="font-medium text-gray-800">₱8,500</p> --}}
                                        <p>₱{{ number_format($this->computePetTotal(), 2) }}</p>
                                    </div>
                                    <div class="md:col-span-2 pt-4 border-t border-gray-200 mt-4">
                                        <p class="text-gray-600 text-sm mb-1">Total Room Charge</p>
                                        {{-- <p class="font-bold text-lg text-green-700">₱8500</p> --}}
                                        <p class="font-semibold">₱{{ number_format($item['total_amount'], 2) }}</p>
                                    </div>

                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Activity details -->
                @if (collect($cart)->contains('type', 'activity'))
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Activities</h3>
                        @foreach ($cart as $item)
                            @if ($item['type'] === 'activity')
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-4">
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Activity</p>
                                            {{-- <p class="font-semibold text-gray-800">Nature Walk</p> --}}
                                            <p class="font-medium">{{ $item['activity_name'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Quantity</p>
                                            {{-- <p class="font-semibold text-gray-800">2</p> --}}
                                            <p>{{ $item['quantity'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Time</p>
                                            <p class="font-medium">
                                                @if (!empty($item['activity_datetime']))
                                                    {{ \Carbon\Carbon::parse($item['activity_datetime'])->format('g:i A') }}
                                                @else
                                                    No schedule
                                                @endif
                                            </p>
                                        </div>
                                        <div class="md:col-span-2 pt-4 border-t border-gray-200 mt-4">
                                            <p class="text-gray-600 text-sm mb-1">Total Activity Charge</p>
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
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Pets</h3>
                        @foreach ($pets as $index => $pet)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Breed</p>
                                        <p class="font-medium">{{ $pet['breed'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm mb-1">Quantity</p>
                                        <p>1</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @php
                            $totalPetFee = number_format($this->computePetTotal(), 2);
                        @endphp

                        <div class="pt-4 border-t border-gray-200 mt-4">
                            <p class="text-gray-600 text-sm mb-1">Total Pet Fee</p>
                            <p class="font-bold text-lg text-green-700">₱{{ $totalPetFee }}</p>
                        </div>
                    </div>
                @endif

                <!-- Display array of Special Requests -->
                @if (!empty($special_requests))
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Special Requests</h3>
                        <ul class="list-disc list-inside space-y-2">
                            @foreach ($special_requests as $request)
                                <li class="text-gray-700">
                                    {{ $request['request'] ?? '' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                {{-- Disclaimer - flat rate --}}
                {{-- <div style="
    background-color: #fffacd;
    border: 1px solid #e6b300;
    border-left: 5px solid #e6b300;
    border-radius: 5px;
    padding: 15px;
    margin-top: 10px;
    margin-bottom: 25px;
    color: #333333;
    font-size: 15px;
    line-height: 1.4;">
                    <strong>Note:</strong> All online payments are subject to a <strong>3% convenience fee</strong> to
                    cover processing costs from our payment provider (PayMongo). This fee is automatically added to your
                    total during checkout.

                    <br><br>
                    This applies to all online payment options, including:
                    <ul style="margin-left: 20px; margin-top: 8px;">
                        <li>Credit/Debit Card</li>
                        <li>GCash / GrabPay</li>
                        <li>Bank Transfer (Instapay)</li>
                    </ul>

                    <br><br>
                    If you don’t want to pay the additional online payment fee, you can choose to pay through a manual
                    bank transfer instead.
                    After completing this reservation, we will send a link to your email where you can upload your proof
                    of payment.

                </div> --}}

                <!-- Summary -->
                <div class="border-t-2 border-gray-200 pt-6">
                    <div class="flex flex-col">

                        <!-- Subtotal -->
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg text-gray-700">Subtotal</h3>
                            <div class="text-lg">₱{{ number_format($this->computeSubtotalAmount(), 2) }}</div>
                        </div>

                        <!-- Subtotal without discount -->
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg text-gray-700">Subtotal</h3>
                            <div class="text-lg">₱{{ number_format($this->computeBaseSubtotal(), 2) }}</div>
                        </div>

                        @if ($this->promoCode)
                            <!-- Promo Code Applied -->
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg text-gray-700">Promo Code Applied - {{ $this->promoCode }}</h3>
                                <div class="text-lg">₱{{ number_format($this->promo_discount_amount ?? 0, 2) }}</div>
                            </div>

                            <!-- Subtotal with discount -->
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg text-gray-700">Subtotal with discount</h3>
                                <div class="text-lg">₱{{ number_format($this->computeSubtotalAmount(), 2) }}</div>
                            </div>
                        @endif

                        <!-- Convenience Fee -->
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg text-gray-700">Convenience Fee</h3>
                            <div class="text-lg">₱{{ number_format($this->computeConvenienceFee(), 2) }}</div>
                        </div>


                        <!-- Total Amount -->
                        <div class="flex justify-between items-center font-semibold text-green-700 mb-1">
                            <p class="text-2xl font-bold text-green-700">Total Amount</p>
                            <p class="text-2xl font-bold text-green-700">
                                ₱{{ number_format($this->computeTotalAmount(), 2) }}
                            </p>
                        </div>

                        <hr class="py-2">

                        <!-- If deposit percentage is enabled -->
                        @if ($enable_deposit_percentage && $this->deposit > 0)
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg text-gray-700">Required Deposit</h3>
                                {{-- <p class="text-xl font-bold text-green-700">₱4500</p> --}}
                                <p class="text-2xl">₱{{ number_format($this->deposit ?? 0, 2) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

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

                                <div class="text-sm text-gray-800 space-y-3 text-justify">
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
        </div>
    </div>
</div>