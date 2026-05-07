
<!-- STEP 4: Payment Receipt -->
{{--  --}}


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