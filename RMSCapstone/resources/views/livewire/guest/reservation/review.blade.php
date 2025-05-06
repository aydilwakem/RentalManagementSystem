<div class=" flex">
    <div class="w-full px-4">
        <div class="w-full rounded-xl shadow bg-gray-50 overflow-hidden">
            <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl text-center">
                Pay Using Your Preferred Method
            </div>
            <div class="px-6 pt-6 text-gray-700 text-md">
                Please select your preferred payment method below, scan the QR code, and take a screenshot of your payment. You’ll need to upload the receipt in the next step to confirm your reservation.
            </div>
            <div class="p-6 space-y-6">
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
                                    <input type="radio" name="option" value="{{ $optionId }}"
                                        x-model="selected" class="text-green-600" />
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
            </div>
        </div>
    </div>
</div>
