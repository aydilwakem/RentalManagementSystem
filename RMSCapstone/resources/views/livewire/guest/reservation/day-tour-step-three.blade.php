<div class="space-y-8">

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-green-700 border-b border-gray-100 pb-3 mb-6">
            Guest Details
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-md">
            <div>
                <p class="text-gray-600 mb-1">Name:</p>
                <p class="font-semibold text-gray-800">{{ $first_name }} {{ $last_name }}</p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Contact:</p>
                <p class="font-semibold text-gray-800"> {{ $contact_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Email:</p>
                <p class="font-semibold text-gray-800">{{ $email }}</p>
            </div>
            @if ($company_name)
                <div>
                    <p class="text-gray-600 mb-1">Company:</p>
                    <p class="font-semibold text-gray-800">{{ $company_name }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-green-700 border-b border-gray-100 pb-3 mb-6">
            Tour & Guest Summary
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <p class="text-gray-600 mb-1 font-medium">Tour Name:</p>
                <p class="font-semibold text-gray-800 text-base mb-3">{{ $selectedTour->name }}</p>

                <p class="text-gray-600 mb-1 font-medium">Rate:</p>
                <p class="font-semibold text-gray-800">{{ $selectedRate->rate_name }}</p>
            </div>

            <div>
                <p class="text-gray-600 mb-1 font-medium">Tour Date:</p>
                <p class="font-semibold text-gray-800 text-base mb-3">
                    {{ \Carbon\Carbon::parse($tourDate)->format('F d, Y') }}
                </p>

                <p class="text-gray-600 mb-1 font-medium">Date Booked:</p>
                <p class="font-semibold text-gray-800">{{ now()->format('M d, Y') }}</p>
            </div>

            <div>
                <p class="text-gray-600 mb-1 font-medium">Adults ({{ $adultCount }} @
                    ₱{{ number_format($selectedRate->adult_rate, 2) }}):</p>
                <p class="font-semibold text-gray-800 mb-2">
                    ₱{{ number_format($adultCount * $selectedRate->adult_rate, 2) }}</p>

                @if ($kidCount > 0)
                    <p class="text-gray-600 mb-1 font-medium">Children ({{ $kidCount }} @
                        ₱{{ number_format($selectedRate->kid_rate, 2) }}):</p>
                    <p class="font-semibold text-gray-800 mb-2">
                        ₱{{ number_format($kidCount * $selectedRate->kid_rate, 2) }}</p>
                @endif

                <div class="border-t border-gray-200 pt-2 mt-2">
                    <p class="text-gray-600 mb-1 font-medium">Total Guests:</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $adultCount + $kidCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-green-700 border-b border-gray-100 pb-3 mb-6">
            Payment Summary
        </h2>
        <div class="space-y-2">

            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal (Tour Price):</span>
                <span class="font-semibold text-gray-800">₱{{ number_format($this->subtotal, 2) }}</span>
            </div>

            <div class="flex justify-between text-sm">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Convenience Fee (3%):</span>
                    <div class="relative group inline-block">
                        <i class="fas fa-info-circle text-gray-500 text-xs cursor-pointer"></i>
                        <div
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-xs text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                            A processing fee is applied for secure online payments.
                        </div>
                    </div>
                </div>
                <span class="font-semibold text-gray-800">₱{{ number_format($this->convenience_fee, 2) }}</span>
            </div>

            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                <span class="text-gray-800">Total Amount Due:</span>
                <span class="text-green-700 text-2xl">₱{{ number_format($this->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-green-700 border-b border-gray-100 pb-3 mb-6">
            Terms & Conditions
        </h2>

        <div
            class="text-sm text-gray-800 space-y-3 text-justify p-4 border border-gray-200 rounded-lg bg-gray-50 max-h-60 overflow-y-auto mb-6">
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
            {{-- {!! $terms_and_conditions !!} --}}

        </div>

        <label class="flex items-center cursor-pointer mb-6">
            <input type="checkbox" wire:model.live="terms"
                class="mr-3 h-4 w-4 text-green-700 border-gray-300 rounded focus:ring-green-600">
            <span class="text-gray-700 font-medium text-base">
                I confirm that I have read and agree to the Terms and Conditions
            </span>
        </label>
        @error('terms')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
        @enderror

        <div class="mt-6 flex justify-center">
            <button wire:click="register" wire:loading.attr="disabled" {{ !$terms ? 'disabled' : '' }}
                class="px-4 py-2 rounded-lg font-bold text-sm transition duration-150 ease-in-out shadow-md uppercase
               {{-- FIX: Use a ternary check to explicitly set colors based on the state --}}
               {{ $terms ? 'bg-green-700 text-white hover:bg-green-800 cursor-pointer' : 'bg-gray-400 text-gray-700 cursor-not-allowed' }}">

                <div wire:loading wire:target="register" class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Processing Reservation...
                </div>
                <div wire:loading.remove wire:target="register">
                    Complete Reservation
                </div>
            </button>
        </div>
    </div>
</div>